<?php

declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherGradesModel {

    protected $conn;

    public function __construct(?PDO $connection =null) {
        if($connection){
            $this->conn = $connection;
        }
        else {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }
    }
    //GETTERS
    public function getSubjectsToGrade(int $staffId) : array { // F 2.1.1
        try {
            $sql = "SELECT ss.Section_Subjects_Id, s.Subject_Name, se.Section_Name, COUNT(st.Student_Id) AS Student_Count FROM section_subjects AS ss 
                LEFT JOIN subjects AS s ON s.Subject_Id = ss.Subject_Id
                LEFT JOIN sections AS se ON se.Section_Id = ss.Section_Id
                LEFT JOIN students AS st ON st.Section_Id = ss.Section_Id
                WHERE ss.Staff_Id = :id 
                GROUP BY ss.Section_Subjects_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$staffId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch subjects to grade',211,$e);
        }
    }
    public function getStudentsOfSectionSubject(int $sectionSubjectId, int $staffId, int $quarter) : array { //F 2.1.2
        try {
            $sql = "SELECT s.Student_Id,s.First_Name, s.Last_Name, s.Middle_Name, sg.Grade_Value AS existing_grade, sg.Quarter FROM section_subjects AS ss 
                    INNER JOIN students AS s ON s.Section_Id = ss.Section_Id
                    LEFT JOIN student_grades AS sg ON sg.Section_Subjects_Id =ss.Section_Subjects_Id
                        AND sg.Student_Id = s.Student_Id
                        AND sg.Quarter = :quarter_value
                    WHERE ss.Section_Subjects_Id = :section_subject_id
                    AND ss.Staff_Id = :staffId
                    ORDER BY s.Last_Name, s.First_Name;";
            $stmt =$this->conn->prepare($sql);
            $stmt->bindValue(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->bindValue(':section_subject_id', $sectionSubjectId, PDO::PARAM_INT);
            $stmt->bindValue(':quarter_value',$quarter, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch students in section',212,$e);
        }
    }
    //HELPERS
    private function checkIfGradeUnchanged(float $gradeValue, int $studentId, int $secSubId, int $quarter):bool {//F 2.2.1
        try {
            $sql = "SELECT 1 FROM student_grades WHERE Grade_Value = :gradeVal 
            AND Student_Id = :studId
            AND Section_Subjects_Id = :sectionSubjectId
            AND Quarter = :quarter";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':gradeVal'=>$gradeValue,':studId'=>$studentId, ':sectionSubjectId'=> $secSubId, ':quarter'=> $quarter]);
            $result = $stmt->fetchColumn();
           
            return $result !== false;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            return false;
        }
    }
    private function upsertQueryStudentGrades(array $data):bool { //F 2.2.2
        try {
            $sql = "INSERT INTO student_grades (Section_Subjects_Id, Student_Id, Quarter, Grade_Value) 
                    VALUES (:section_subject_id, :student_id, :quarter, :grade_value)
                    ON DUPLICATE KEY UPDATE 
                    Grade_Value = :grade_value";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':section_subject_id', $data['sec-sub-id'], PDO::PARAM_INT);
            $stmt->bindValue(':student_id', $data['student-id'], PDO::PARAM_INT);
            $stmt->bindValue(':quarter', $data['quarter'], PDO::PARAM_INT);
            $stmt->bindValue(':grade_value', $data['grade-value']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            return false; //RETURN FALSE INSTEAD TO PINPOINT FAILED OP
        }
    }
    //OPERATIONS
    public function upsertStudentGrades(array $data):array { //F 2.3.1
        //TODO: FIX LOGIC
        $result = [
            'is_unchanged'=> [],
            'no_value'=> [],
            'success'=> [],
            'failed'=> [],
            'all-failed'=> false,
            'details'=> ''
        ];
        try { 
            $this->conn->beginTransaction();
            foreach($data as $index => $rows) {   
                $secSubId = (int)$rows['sec-sub-id'];
                $studId = (int)$rows['student-id'];
                $gradeVal = (float)$rows['grade-value'];
                $quarter = (int)$rows['quarter'];
                if($gradeVal === 0.0) {
                    $result['no_value'][] = $index + 1;
                    continue;
                }
                if($this->checkIfGradeUnchanged($gradeVal,$studId,$secSubId,$quarter)) {
                    $result['is_unchanged'][] = $index + 1;
                    continue;
                }
                if(!$this->upsertQueryStudentGrades($rows)) {
                    $result['failed'][] = $studId;
                }
                else {
                    $result['success'][] = $studId;
                }
            } 
            //COUNT IF RESULTS MATCH THE DATA PASSED
            $count = count($result['is_unchanged']) + count($result['no_value']) + count($result['success']) + count($result['failed']);
            $noChanges = count($result['is_unchanged']) + count($result['no_value']);
            if($count === count($data)) {
                $result['all-failed'] = count($result['failed']) === count($data);
                if($result['all-failed']) {
                    $result['details'] = 'No operation was successful';
                }
                else if($noChanges === count($data)) {
                    $result['details'] =  'Nothing was changed';
                }
                else {
                    $result['details'] = 'Operations completed';
                }
                $this->conn->commit();
            }
            else {
                $this->conn->rollBack();
                $result['all-failed'] = true;
                $result['details']= 'The data passed did not match the number of students. Excpected: ' . count($data) . ', Got: ' . $count;
            }
            return $result;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseExcepiton('Failed to insert student grades',231,$e);
        }
    }
}