<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';

class adminSectionsModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function insertSections($sectionName, $gradeLevel) {
        $sql = "INSERT INTO sections(Section_Name, Grade_Level_Id) VALUES (:section_name, :grade_level)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':section_name', $sectionName);
        $stmt->bindParam(':grade_level', $gradeLevel);

        $result = $stmt->execute();

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getAllTeachers() {
        $sql = "SELECT Staff_Id,Staff_First_Name, Staff_Middle_Name, Staff_Last_Name FROM staffs WHERE Staff_type = 2";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getAvailableStudents($id) {
        $sql = "SELECT s.Student_Id, s.First_Name, s.Last_Name, s.Middle_Name FROM students AS s 
                INNER JOIN sections AS sec ON s.Grade_Level_Id = sec.Grade_Level_Id
                WHERE sec.Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result =$stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function getCheckedStudents($id) {
        $sql = "SELECT Student_Id FROM students WHERE Section_Id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function checkCurrentAdviser($id) {
        $sql = "SELECT Staff_Id FROM section_advisers WHERE Section_Id = :id";

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
    public function getSectionsListInformation() { //should return section name, adviser, subjects, and the students in this section.
        $sql = "SELECT s.*, gl.Grade_Level, ss.Staff_Id, staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name, COUNT(DISTINCT st.Student_Id) AS Students FROM sections s 
                LEFT JOIN grade_level AS gl ON s.Grade_Level_Id = gl.Grade_Level_Id LEFT JOIN students As st ON st.Section_Id = s.Section_Id 
                LEFT JOIN section_subjects AS ss ON s.Section_Id = ss.Section_Id 
                LEFT JOIN staffs as staff ON ss.Staff_Id = staff.Staff_Id GROUP BY s.Section_Id, gl.Grade_Level, s.Section_Name;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        } 
        else {
            return false;
        }
    }
    public function checkIfSectionNameExists($sectionName, ?int $id = null) {

        if($id === null) {
            $sql = "SELECT COUNT(*) FROM sections WHERE Section_Name = :sectionName";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionName', $sectionName);
        }
        else {
            $sql = "SELECT COUNT(*) FROM sections WHERE Section_Name = :sectionName AND Section_Id != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionName', $sectionName);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        
        $stmt->execute();
        $result = $stmt->fetchColumn();

        return (int) $result;
    }
    public function updateSectionName($id, $sectionName) {
        $sql = "UPDATE sections SET Section_Name = :sectionName WHERE Section_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':sectionName', $sectionName);

        $result = $stmt->execute();

        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
    public function updateAdviser($id, $staffId) {
        $sql = "INSERT INTO section_advisers(Section_Id, Staff_Id) VALUES(:id, :staffId) 
                ON DUPLICATE KEY UPDATE Staff_Id = VALUES(Staff_Id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':staffId', $staffId);

        $result = $stmt->execute();
        if($result) {
            return $result;
        }
        else {
            return false;
        }
    }
}