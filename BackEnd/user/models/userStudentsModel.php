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
            $sql = "SELECT s.Student_Id, s.Enrollee_Id, s.First_Name, s.Last_Name, s.Middle_Name, s.Student_Status, e.User_Id 
            FROM students AS s INNER JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id WHERE e.User_Id = :userId ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]".$e->getMessage() ."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch user students',0,$e);
        }
    }
    public function reEnrollStudent(int $studentId, int $status):bool {
        try {
            $sql = "UPDATE students SET Student_Status = :status  WHERE Student_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status'=>$status,':id'=>$studentId]);
            if($stmt->rowCount()===0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]".$e->getMessage() ."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to re-enroll students',0,$e);
        }
    }
}