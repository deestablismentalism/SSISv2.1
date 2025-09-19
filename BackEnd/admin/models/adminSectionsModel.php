<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSectionsModel {
    protected $conn;

    //TODO: 
    // IMPROVE MODEL TYPE DECLARATION
    //Handle PDOExceptions(try catch)

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function insertSections($sectionName, $gradeLevel) : bool {
        try {
             $sql = "INSERT INTO sections(Section_Name, Grade_Level_Id) VALUES (:section_name, :grade_level)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':section_name', $sectionName);
            $stmt->bindParam(':grade_level', $gradeLevel);

            $result = $stmt->execute();
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Inserting sections failed', 0, $e);
        }
    }
    public function getAllTeachers() : array {
        try {
            $sql = "SELECT Staff_Id,Staff_First_Name, Staff_Middle_Name, Staff_Last_Name FROM staffs WHERE Staff_type = 2";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;            
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all the teachers failed', 0, $e);
        }
    }
    public function getAvailableStudents($id) : array {
        try {
            $sql = "SELECT s.Student_Id, s.First_Name, s.Last_Name, s.Middle_Name FROM students AS s 
                INNER JOIN sections AS sec ON s.Grade_Level_Id = sec.Grade_Level_Id
                WHERE sec.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result =$stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }    
        catch(PDOException $e) {
            throw new DatabaseException('Fetching available students failed', 0, $e);
        }
    }
    public function getCheckedStudents($id) : array { //gets the id of all students from an array of students that are checked
        try {
            $sql = "SELECT Student_Id FROM students WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching checked students failed', 0, $e);
        }
    }
    public function checkCurrentAdviser($id) : ?array{
        try {
            $sql = "SELECT Staff_Id FROM section_advisers WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ?: null;  
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching current adviser failed', 0, $e);
        }
    }
    public function getSectionMaleStudents($id) : array{
        try {
            $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name FROM sections AS s 
                LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Male'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all male students failed', 0, $e);
        }
    }
    public function getSectionFemaleStudents($id) : array {
        try {
            $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name FROM sections AS s 
                    LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                    LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Female'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all female students failed', 0, $e);
        }
    }
    public function getSectionAdviserName($id) : ?array {
        try {
            $sql = "SELECT staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name FROM section_advisers AS sa
                    LEFT JOIN staffs AS staff ON sa.Staff_Id = staff.Staff_Id WHERE sa.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch Adviser name', 0, $e);
        }
    }
    public function getSectionName($id) : ?array {
        try {
            $sql = "SELECT Section_Name, gl.Grade_Level FROM sections INNER JOIN grade_level AS gl ON sections.Grade_Level_Id = gl.Grade_Level_Id WHERE sections.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch section name', 0, $e);
        }

    }
    public function getSectionsListInformation() : array { //should return section name, adviser, subjects, and the students in this section.
        try {
            $sql = "SELECT s.*, gl.Grade_Level, sa.Staff_Id, staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name, COUNT(DISTINCT st.Student_Id) AS Students FROM sections s 
                LEFT JOIN grade_level AS gl ON s.Grade_Level_Id = gl.Grade_Level_Id LEFT JOIN students As st ON st.Section_Id = s.Section_Id 
                LEFT JOIN section_advisers AS sa ON s.Section_Id = sa.Section_Id 
                LEFT JOIN staffs as staff ON sa.Staff_Id = staff.Staff_Id GROUP BY s.Section_Id, gl.Grade_Level, s.Section_Name;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOExceptin $e) {
            throw new DatabaseException('Failed to fetch section list information', 0, $e);
        }
    }
    public function checkIfSectionNameExists($sectionName, ?int $id = null) : bool {
        try {
            $sql = "SELECT 1 FROM sections WHERE Section_Name = :sectionName";
            if($id !== null) {
                $sql .= " AND Section_Id != :id";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionName', $sectionName);
            if($id !== null) {
                $stmt->bindParam(':id', $id);
            }
            $stmt->execute();
            $result = $stmt->fetchColumn();

            return (bool) $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to check if section name exists', 0, $e);
        }    
    }
    public function updateSectionName($id, $sectionName) : bool {
        try {
            $sql = "UPDATE sections SET Section_Name = :sectionName WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':sectionName', $sectionName);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to update section name', 0 ,$e);
        }
    }
    public function updateAdviser($id, $staffId) : bool{
        try {
             $sql = "INSERT INTO section_advisers(Section_Id, Staff_Id) VALUES(:id, :staffId) 
                ON DUPLICATE KEY UPDATE Staff_Id = VALUES(Staff_Id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':staffId', $staffId);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to update section adviser', 0, $e);
        }
    }
    public function checkIfSubjectTeacherExists($staffId) : ?array {
        try {
            $sql = "SELECT Subject_Id FROM section_subjects WHERE Staff_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $staffId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to check the subject teacher', 0, $e);
        }
    }
}