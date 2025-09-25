<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';

class teacherSubjectsModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getTeacherSubjectsHandled($id) {
        $sql = "SELECT su.Subject_Name, s.Section_Name FROM section_subjects AS ss 
                INNER JOIN sections AS s ON ss.Section_Id = s.Section_Id
                INNER JOIN subjects AS su ON ss.Subject_Id = su.Subject_Id WHERE Staff_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

       return $result;
    }

    public function getSectionSubjects($id) {
        $sql = "SELECT su.Subject_Name, s.Section_Name, ss.*, ssc.* FROM section_subjects AS ss
                JOIN section_schedules AS ssc ON ss.Section_Subjects_Id = ssc.Section_Subjects_Id
                JOIN sections AS s ON ss.Section_Id = s.Section_Id
                JOIN subjects AS su ON ss.Subject_Id = su.Subject_Id WHERE ss.Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}