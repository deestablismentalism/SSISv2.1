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
    public function getSubjectsToGrade(int $staffId) : array {
        try {
            $sql = "SELECT s.Subject_Name, se.Section_Name, COUNT(st.Student_Id) AS Student_Count FROM section_subjects AS ss 
                LEFT JOIN subjects AS s ON s.Subject_Id = ss.Subject_Id
                LEFT JOIN sections AS se ON se.Section_Id = ss.Section_Id
                LEFT JOIN students AS st ON st.Section_Id = ss.Section_Id
                WHERE ss.Staff_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $staffId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch subjects to grade',0,$e);
        }
    }
}