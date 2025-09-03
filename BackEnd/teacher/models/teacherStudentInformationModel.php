<?php

declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
class teacherStudentInformationModel {
    protected $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getStudentInformation($studentId) {
        $sql = "SELECT * FROM students WHERE Student_Id = :studentId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function getStudentAddress($studentId) {
        $sql = "SELECT ea.* FROM students AS s
                INNER JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id
                INNER JOIN enrollee_address AS ea ON s.Enrollee_Address_Id = ea.Enrollee_Address_Id WHERE Student_Id = :studentId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        } else {
            return false;
        }
    }
    public function getEducationalInformation($studentId) {
        $sql = "SELECT ei.* FROM students AS s 
                INNER JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id
                INNER JOIN educational_information AS ei ON e.Educational_Information_Id = ei.Educational_Information_Id WHERE Student_Id = :studentId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        } else {
            return false;
        }
    }
    
    public function getStudentParents($studentId) {
        $sql = "SELECT ep.* FROM students AS s 
                INNER JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id
                INNER JOIN enrollee_parents AS ep ON e.Enrollee_Id = ep.Enrollee_Id WHERE Student_Id = :studentId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        } else {
            return false;
        }
    }
}