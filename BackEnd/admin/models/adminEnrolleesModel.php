<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminEnrolleesModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getEnrollmentInformation($id) : array {
        try {
            $sql = "SELECT enrollee_parents.*,
                        enrollee.*,
                        educational_information.*,
                        educational_background.*,
                        enrollee_address.*,
                        disabled_student.*,
                        parent_information.*,

                        enrolling_level.Grade_Level AS E_Grade_Level,
                        last_level.Grade_Level AS L_Grade_Level

                FROM enrollee_parents
                INNER JOIN enrollee ON enrollee_parents.Enrollee_Id = enrollee.Enrollee_Id
                INNER JOIN educational_information ON  enrollee.Educational_Information_Id = educational_information.Educational_Information_Id
                INNER JOIN grade_level AS enrolling_level ON enrolling_level.Grade_Level_Id = educational_information.Enrolling_Grade_Level
                INNER JOIN grade_level AS last_level ON last_level.Grade_Level_Id = educational_information.Last_Grade_Level 
                INNER JOIN educational_background ON enrollee.Educational_Background_Id = educational_background.Educational_Background_Id
                INNER JOIN enrollee_address ON enrollee.Enrollee_Address_Id = enrollee_address.Enrollee_Address_Id
                INNER JOIN disabled_student ON enrollee.Disabled_Student_Id = disabled_student.Disabled_Student_Id
                INNER JOIN parent_information ON enrollee_parents.Parent_Id = parent_information.Parent_Id 
                WHERE enrollee_parents.Enrollee_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch enrollment information', 0 ,$e);
        }
    }

    public function countEnrollees() : int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM enrollee WHERE Enrollment_Status = 3";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$result['total'];   
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to count enrollee', 0,$e);
        }
    }

    public function getPsaImg($id) : string {
        try {
            $sql = " SELECT Psa_directory.directory FROM enrollee 
                INNER JOIN Psa_directory ON enrollee.Psa_Image_Id = Psa_directory.Psa_Image_Id
                WHERE Enrollee_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result) {
                return (string)$result['directory'];
            }
            else {
                return "";
            }
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch PSA directory', 0 ,$e);
        }
    }
    // function to update the enrollee table status upon any completed enrollment transaction 
    public function updateEnrollee($id, $status) : bool{ // used by postUpdateEnrolleeStatus
        try {
            $sql = "UPDATE enrollee SET Enrollment_Status = :status WHERE Enrollee_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to update enrollee status', 0,$e);
        }
    }
    public function getAllPartialEnrollees() : array{
        try {
            $sql = "SELECT Learner_Reference_Number,
                        Student_First_Name,
                        Student_Last_Name,
                        Student_Middle_Name,
                        Enrollment_Status   
                FROM enrollee";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch enrollee partial information', 0,$e);
        }
    }
    public function getEnrolled() : array {
        try {
            $sql = "SELECT * FROM enrollee_parents
                INNER JOIN enrollee ON enrollee_parents.Enrollee_Id = enrollee.Enrollee_Id
                INNER JOIN educational_information ON  enrollee.Educational_Information_Id = educational_information.Educational_Information_Id 
                INNER JOIN grade_level AS enrolling_level ON enrolling_level.Grade_Level_Id = educational_information.Enrolling_Grade_Level
                INNER JOIN grade_level AS last_level ON last_level.Grade_Level_Id = educational_information.Last_Grade_Level
                INNER JOIN parent_information ON enrollee_parents.Parent_Id = parent_information.Parent_Id 
                WHERE parent_information.Parent_Type = 'Guardian' AND Enrollment_Status = 1;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch the enrolled students',0,$e);
        }
    }
    public function countEnrolled() : int {
        try {
            $sql = "SELECT COUNT(*) AS total FROM enrollee WHERE Enrollment_Status = 1;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to count the enrolled',0,$e);
        }
    }
    public function searchEnrollees($query) : array {
        try {
            $query = "%$query%";
            $sql = "SELECT * FROM enrollee
                    WHERE Enrollment_Status = 3 AND Student_First_Name LIKE :search
                    OR Student_Last_Name LIKE :search";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':search', $query);

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to search for enrollee',0,$e);        
        }
    }
    public function getAllEnrollees() : array {
        try {
            $sql = "SELECT  e.Enrollee_Id,
                        e.Learner_Reference_Number,
                        e.Student_First_Name,
                        e.Student_Last_Name,
                        e.Student_Middle_Name,
                        enrolling_level.Grade_Level AS E_Grade_Level,
                        e.Enrollment_Status,     
                        p.First_Name,
                        p.Last_Name,
                        p.Middle_Name,
                        p.Contact_Number              
                FROM enrollee_parents
                INNER JOIN enrollee AS e ON enrollee_parents.Enrollee_Id = e.Enrollee_Id
                INNER JOIN educational_information ON e.Educational_Information_Id = educational_information.Educational_Information_Id 
                INNER JOIN grade_level AS enrolling_level ON enrolling_level.Grade_Level_Id = educational_information.Enrolling_Grade_Level
                INNER JOIN grade_level AS last_level ON last_level.Grade_Level_Id = educational_information.Last_Grade_Level
                INNER JOIN parent_information AS p ON enrollee_parents.Parent_Id = p.Parent_Id 
                WHERE p.Parent_Type = 'Guardian'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to get the enrollees',0,$e);
        }
    }
    public function countAllEnrollees():int{
        try {
            $sql = "SELECT COUNT(*) AS total FROM enrollee";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['total'];
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to count all the enrollees',0,$e);
        }
    }
    public function sendTransactionStatus($id) : array{
        try {
            $sql = "SELECT et.*, e.Enrollment_Status FROM enrollment_transactions AS et 
                LEFT JOIN enrollee AS e ON et.Enrollee_Id = e.Enrollee_Id WHERE et.Enrollee_Id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch the transaction status',0,$e);
        }
    }
   
}