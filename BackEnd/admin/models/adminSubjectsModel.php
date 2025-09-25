<?php
declare(strict_types=1);

include_once __DIR__ . '/../../core/dbconnection.php';

class adminSubjectsModel {

    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function insertSubject($subject) {
        $sql = "INSERT INTO subjects(Subject_Name) VALUES (:subjects)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':subjects', $subject);
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        else {
            return "error ";
        }

    }
    public function insertGradeLevelSubjects($subjectId, $gradeLevelId) {
        $sql = "INSERT INTO grade_level_subjects(Subject_Id, Grade_Level_Id) VALUES (:subjectId, :gradeLevelId)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':subjectId', $subjectId);
        $stmt->bindParam(':gradeLevelId', $gradeLevelId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function insertSubjectAndLevel($subjectName, $gradeLevelId) {
        try {
            $this->conn->beginTransaction();

            $subjectId = $this->insertSubject($subjectName);
            
            if (!is_numeric($subjectId)) {
                throw new PDOException("Failed to insert subject: " . $subjectId);
            }

            $result = $this->insertGradeLevelSubjects($subjectId, $gradeLevelId);
            
            if (!$result) {
                throw new PDOException("Failed to associate subject with grade level");
            }

            $this->conn->commit();

            return "succesfully inserted";
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    public function getSubjectsPerGradeLevel() {
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
            return "Error" . $e->getMessage();
        }
    }

}