<?php
declare(strict_types = 1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class userStudentsModel {

    protected $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //function to display all the user enrollees that are inserted in the students table
    public function getUserStudents(int $userId) : array { //used in user_all_enrolled
        try {
            $sql = "SELECT s.Student_Id, s.Enrollee_Id, e.Student_First_Name, e.Student_Last_Name, e.Student_Middle_Name, e.User_Id 
            FROM students AS s INNER JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id WHERE e.User_Id = :userId ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch user students',0,$e);
        }
    }
}