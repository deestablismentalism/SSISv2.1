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

        return $stmt->execute();
    }
    public function getSections() {
        $sql = "SELECT s.Section_Id, s.Section_Name, g.Grade_Level FROM sections s 
        JOIN grade_level g ON s.Grade_Level_Id = g.Grade_Level_Id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    //get the whole section information
    public function getSectionInformation() { //should return section name, adviser, subjects, and the students in this section.

    }
}