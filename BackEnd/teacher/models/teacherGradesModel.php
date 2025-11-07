<?php

declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherGradesModel {

    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    
    public function getConnection() {
        return $this->conn;
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
            $sql = "SELECT s.Student_Id, s.First_Name, s.Last_Name, s.Middle_Name 
                    FROM section_subjects AS ss 
                    INNER JOIN students AS s ON s.Section_Id = ss.Section_Id 
                    WHERE ss.Section_Subjects_Id = :section_subject_id 
                    AND ss.Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->bindValue(':section_subject_id', $sectionSubjectId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($result)) {
                return [];
            }
            
            // Fetch existing grades for each student
            $studentsWithGrades = [];
            foreach ($result as $student) {
                try {
                    $grades = $this->getStudentGrades($sectionSubjectId, (int)$student['Student_Id']);
                    $student['grades'] = $grades;
                } catch (Exception $e) {
                    // If grades can't be fetched, set empty array
                    $student['grades'] = [];
                }
                $studentsWithGrades[] = $student;
            }
            
            return $studentsWithGrades;
        }
        catch(PDOException $e) {
            error_log('getStudentsOfSectionSubject Error: ' . $e->getMessage());
            throw new DatabaseException('Failed to fetch students in sections: ' . $e->getMessage(), 212, $e);
        }
    }
    public function saveOrUpdateGrade(int $studentId, int $sectionSubjectId, int $quarter, float $gradeValue): bool {
        try {
            // Check if grade already exists using composite key
            $checkSql = "SELECT 1 FROM student_grades 
                        WHERE Student_Id = :studentId 
                        AND Section_Subjects_Id = :sectionSubjectId 
                        AND Quarter = :quarter
                        LIMIT 1";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $checkStmt->bindParam(':sectionSubjectId', $sectionSubjectId, PDO::PARAM_INT);
            $checkStmt->bindParam(':quarter', $quarter, PDO::PARAM_INT);
            $checkStmt->execute();
            $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update existing grade using composite key
                $sql = "UPDATE student_grades 
                        SET Grade_Value = :gradeValue 
                        WHERE Student_Id = :studentId 
                        AND Section_Subjects_Id = :sectionSubjectId 
                        AND Quarter = :quarter";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':gradeValue', $gradeValue, PDO::PARAM_STR);
                $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                $stmt->bindParam(':sectionSubjectId', $sectionSubjectId, PDO::PARAM_INT);
                $stmt->bindParam(':quarter', $quarter, PDO::PARAM_INT);
            } else {
                // Insert new grade
                $sql = "INSERT INTO student_grades 
                        (Student_Id, Section_Subjects_Id, Quarter, Grade_Value) 
                        VALUES (:studentId, :sectionSubjectId, :quarter, :gradeValue)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
                $stmt->bindParam(':sectionSubjectId', $sectionSubjectId, PDO::PARAM_INT);
                $stmt->bindParam(':quarter', $quarter, PDO::PARAM_INT);
                $stmt->bindParam(':gradeValue', $gradeValue, PDO::PARAM_STR);
            }

            $result = $stmt->execute();
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log('saveOrUpdateGrade execute failed. Error: ' . print_r($errorInfo, true));
                error_log('SQL: ' . $sql);
                error_log('Params: studentId=' . $studentId . ', sectionSubjectId=' . $sectionSubjectId . ', quarter=' . $quarter . ', gradeValue=' . $gradeValue);
                throw new PDOException('Execute failed: ' . ($errorInfo[2] ?? 'Unknown error'));
            }
            
            return $result;
        }
        catch(PDOException $e) {
            $errorInfo = $e->errorInfo ?? [];
            error_log('saveOrUpdateGrade PDOException: ' . $e->getMessage());
            error_log('Error Code: ' . $e->getCode());
            error_log('SQL State: ' . ($errorInfo[0] ?? 'N/A'));
            error_log('Driver Error: ' . ($errorInfo[1] ?? 'N/A'));
            error_log('Error Message: ' . ($errorInfo[2] ?? $e->getMessage()));
            $sqlState = $errorInfo[0] ?? 'N/A';
            throw new DatabaseException('Failed to save grade: ' . $e->getMessage() . ' (SQL State: ' . $sqlState . ')', 0, $e);
        }
    }

    public function getStudentGrades(int $sectionSubjectId, int $studentId): array {
        try {
            $sql = "SELECT Quarter, Grade_Value FROM student_grades 
                    WHERE Section_Subjects_Id = :sectionSubjectId 
                    AND Student_Id = :studentId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionSubjectId', $sectionSubjectId, PDO::PARAM_INT);
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $grades = [];
            foreach ($result as $row) {
                $grades[$row['Quarter']] = (float)$row['Grade_Value'];
            }
            return $grades;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch student grades', 0, $e);
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