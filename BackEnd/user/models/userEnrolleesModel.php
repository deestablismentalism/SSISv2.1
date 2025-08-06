<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';

class userEnrolleesModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getPendingEnrollees(){
        $sql = "SELECT * FROM enrollee WHERE Enrollment_Status = 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getEnrollmentInformation($id) {
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

    public function countEnrollees():string {
        $sql = "SELECT COUNT(*) AS total FROM enrollee WHERE Enrollment_Status = 3";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (string)$result['total'];
    }

    public function getPsaImg($id) {
        $sql = " SELECT Psa_directory.filename FROM enrollee 
                INNER JOIN Psa_directory ON enrollee.Psa_Image_Id = Psa_directory.Psa_Image_Id
                WHERE Enrollee_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result) {
            return (string)$result['filename'];
        }
        else {
            return "";
        }
    }

    public function updateEnrollee($id, $status) {
        $sql = "UPDATE enrollee SET Enrollment_Status = :status WHERE Enrollee_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function insertEnrolleeTransaction($id , $transactionCode , $staffId, $reason,$description) {
        $sql ="INSERT INTO enrollment_transactions(Enrollee_Id,Transaction_Code, Staff_Id, Reason,Description)
            VALUES (:enrollee_id, :transaction_code, :staff_Id,:reason, :description)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':enrollee_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':transaction_code', $transactionCode);
        $stmt->bindParam(':staff_Id', $staffId, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':description', $description);
        if($stmt->execute()) {
            return ['success'=> true, 'message'=> 'query successful'];
        }
        else {
            return ['success'=> false, 'message'=> 'query failed'];
        }
    } 
    public function getUserEnrollees($id) {
        $sql = "SELECT * FROM enrollee WHERE User_Id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT );
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getUserStatus($userId, $enrolleeId) {
        $sql = "SELECT Enrollment_Status FROM enrollee WHERE User_Id = :userId AND Enrollee_Id = :enrolleeId";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':enrolleeId', $enrolleeId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['Enrollment_Status'];
    }
    public function getAllPartialEnrollees() {
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
    public function getEnrolled() {
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
    public function countEnrolled():string {
        $sql = "SELECT COUNT(*) AS total FROM enrollee WHERE Enrollment_Status = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (string)$result['total'];
    }
    public function searchEnrollees($query) {
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
        echo "<td> Error: " . $e->getMessage() . "<td>";
       }
    }

    public function getAllEnrollees() {
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
    
    public function countAllEnrollees():string {
        $sql = "SELECT COUNT(*) AS total FROM enrollee";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (string)$result['total'];
    }

    public function sendTransactionStatus($id) {
        $sql = "SELECT et.*, e.Enrollment_Status FROM enrollment_transactions AS et 
                LEFT JOIN enrollee AS e ON et.Enrollee_Id = e.Enrollee_Id WHERE et.Enrollee_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
   
}