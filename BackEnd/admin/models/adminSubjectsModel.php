<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSubjectsModel {

    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //insertSubjectAndLevel helper function 1
    private function insertSubject($subject) : int {
        try {
            $sql = "INSERT INTO subjects(Subject_Name) VALUES (:subjects)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjects', $subject);
            $result = $stmt->execute();
            if(!$result) {
                throw new Exception('Insert subject failed');
            }

            return (int) $this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to insert subject', 0,$e);
        }

    }
    //insertSubjectAndLevel helper function 2
    private function insertGradeLevelSubjects($subjectId, $gradeLevelId) : bool  {
        try {
            $sql = "INSERT INTO grade_level_subjects(Subject_Id, Grade_Level_Id) VALUES (:subjectId, :gradeLevelId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId, PDO::PARAM_INT);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to insert the subject for grade level',0,$e);
        }
    }
    public function insertSubjectAndLevel($subjectName, array $gradeLevelId) : array {
        $results = [
            'success'=> [],
            'failed'=> []
        ];
        try {
            $this->conn->beginTransaction();
            //helper function subject insert
            $subjectId = $this->insertSubject($subjectName);
            
            if (!is_numeric($subjectId)) {
                throw new DatabaseException("Failed to insert subject: " . $subjectId);
            }

            foreach($gradeLevelId as $ids) {
                try {
                    $result = $this->insertGradeLevelSubjects($subjectId, $ids);
                    if (!$result) {
                        $results['failed'][] = $ids;
                    }
                    $results['success'][] = $ids;
                }
                catch(PDOException $e) {
                    $results['failed'][] = $ids;
                }
            }
            $this->conn->commit();

            return $results;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            throw new DatabaseException('Failed to update the subject and level',0,$e);
        }
    }
    public function getSubjectsPerGradeLevel() : array{
        try {
            $sql = "SELECT
                    s.Subject_Id,
                    s.Subject_Name,
                    g.Grade_Level,
                    st.Staff_First_Name, st.Staff_Last_Name, st.Staff_Middle_Name FROM grade_level_subjects AS gl
                    LEFT JOIN section_subjects AS ss ON gl.Subject_Id = ss.Subject_Id
                    LEFT JOIN grade_level AS g ON gl.Grade_Level_Id = g.Grade_Level_Id 
                    LEFT JOIN subjects AS s ON gl.Subject_Id = s.Subject_Id
                    LEFT JOIN staffs AS st ON ss.Staff_Id = st.Staff_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch the subjects per level',0,$e);
        }
    }
    public function insertSubjectTeacher($subjectId, $staffId) : bool {
        $sql = "INSERT INTO section_subjects()";
    }
}