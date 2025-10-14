<?php
    
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
class adminTeachersModel {
    protected $conn;

    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //Fetching all teachers
    public function selectAllTeachers() : array {
        try {
            $sql = "SELECT Staff_Id, Staff_First_Name, Staff_Middle_Name, Staff_Last_Name, Staff_Contact_Number, Position FROM staffs WHERE Staff_Type = 2";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch all teachers',0,$e);
        }
    }
    public function checkCurrentSubjectTeacherOfSectionSubject(int $sectionSubjectsId) : ?int {
        try {
            $sql = "SELECT Staff_Id FROM section_subjects WHERE Section_Subjects_Id = :sectionSubjectsId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionSubjectsId', $sectionSubjectsId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result['Staff_Id'] ?? null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to check current Subject teacher',0,$e);
        }
    }
    public function updateSubjectTeacherToSectionSubjects(int $staffId, int $sectionSubjectsId) : bool {
        try {
            $sql = "UPDATE section_subjects SET Staff_Id = :staffId WHERE Section_Subjects_Id = :sectionSubjectsId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId);
            $stmt->bindParam(':sectionSubjectsId', $sectionSubjectsId);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert teacher to section subject',0,$e);
        }
    }
    private function insertToStaff(string $fName, string $mName, string $lName, string $email, string $cpNumber,int $status, int $type) : ?int {
        try {
            $sql = "INSERT INTO 
            staffs(Staff_First_Name, Staff_Middle_Name, Staff_Last_Name, Staff_Email, Staff_Contact_Number, Staff_Status, Staff_Type)
            VALUES(:fname, :mname, :lname, :email, :cpnumber,:status: type)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fname', $fName);
            $stmt->bindParam(':mname', $mName);
            $stmt->bindParam(':lname', $lName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpnumber', $cpNumber);
            $stmt->bindParam(':status'. $status);
            $stmt->bindParam(':type', $type);
            $result = $stmt->execute();

            return (int)$this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to insert teacher',0,$e);
        }
    }
    public function insertToStaffAndUser(string $fname, string $mname, string $lname, string $email, 
    string $cpNumber, int $status, int $type, string $password) : bool{
        $insert = true;
        try {
            $staffId = $this->insertToStaff($fname, $mname, $lname, $email, $cpNumber, $status, $type);

            $sql = "INSERT INTO users(Password, User_Type, Staff_Id) VALUES(:password, :userType, :staffId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':userType', $type);
            $stmt->bindParam(':staffId', $staffId);
            $result = $stmt->execute();
            if(!$result) {
                $insert = false;
            }
            return $insert;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
}
 