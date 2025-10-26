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
            $stmt->bindParam(':id', $staffId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch subjects to grade',211,$e);
        }
    }
    public function getStudentsOfSectionSubject(int $sectionSubjectId, int $staffId) : array { //F 2.1.2
        try {
            $sql = "SELECT s.Student_Id,s.First_Name, s.Last_Name, s.Middle_Name FROM section_subjects AS ss 
                    INNER JOIN students AS s ON s.Section_Id = ss.Section_Id 
                    WHERE ss.Section_Subjects_Id = :section_subject_id 
                    AND ss.Staff_Id = :staffId";
            $stmt =$this->conn->prepare($sql);
            $stmt->bindValue(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->bindvalue(':section_subject_id', $sectionSubjectId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch students in sections',212,$e);
        }
    }
    //HELPERS
    //OPERATIONS
}