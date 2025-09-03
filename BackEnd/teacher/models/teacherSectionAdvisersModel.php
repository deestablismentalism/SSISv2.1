<?php

declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';

class teacherSectionAdvisersModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function checkIfAdvisory($id, $sectionId) {
        $sql = "SELECT Staff_Id, Section_Id FROM section_advisers WHERE Staff_Id = :id AND Section_Id = :sectionId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':sectionId', $sectionId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function checkIfAdviser($id) {
        $sql = "SELECT Staff_Id, Section_Id FROM section_advisers WHERE Staff_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getSectionName($id) {
        $sql = "SELECT Section_Name, gl.Grade_Level FROM sections INNER JOIN grade_level AS gl ON sections.Grade_Level_Id = gl.Grade_Level_Id WHERE sections.Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getSectionAdviserName($id) {
        $sql = "SELECT staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name FROM section_advisers AS sa
                LEFT JOIN staffs AS staff ON sa.Staff_Id = staff.Staff_Id WHERE sa.Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getSectionStudents($id) {
        $sql = "SELECT Student_Id, First_name, Last_Name, Middle_Name FROM students WHERE Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
        
    }
    public function getSectionMaleStudents($id) {
        $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name FROM sections AS s 
                LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Male'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getSectionFemaleStudents($id) {
        $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name FROM sections AS s 
                LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Female'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
}