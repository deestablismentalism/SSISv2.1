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
            $sql = "SELECT Staff_Id FROM section_subject_teachers WHERE Section_Subjects_Id = :sectionSubjectsId";
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
    private function getCurrentSchoolYearId():?int {
        try {   
            $sql = "SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 0 
            ORDER BY Starting_Date LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['School_Year_Details_Id'] ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details ID',0,$e);
        }
    }
    public function upsertSubjectTeacherToSectionSubjects(int $staffId, int $sectionSubjectsId) : bool {
        try {
            $this->conn->beginTransaction();
            $id = $this->getCurrentSchoolYearId();
            if(is_null($id)) {
                $this->conn->rollBack();
                return false;
            }
            $sql = "INSERT INTO section_subject_teachers(Staff_Id,Section_Subjects_Id,School_Year_Details_Id) 
                    VALUES(:staffId,:sectionSubjectsId,:syId) ON DUPLICATE KEY UPDATE Staff_Id = VALUES(Staff_Id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId);
            $stmt->bindParam(':sectionSubjectsId', $sectionSubjectsId);
            $stmt->bindParam(':syId',$id);
            $stmt->execute();
            if($stmt->rowCount()===0) {
                $this->conn->commit();
                return false;
            }
            $this->conn->commit();
            return true;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert teacher to section subject',0,$e);
        }
    }
    private function insertToStaff(string $fName, string $mName, string $lName, string $email, string $cpNumber) : ?int {
        try {
            $sql = "INSERT INTO 
            staffs(Staff_First_Name, Staff_Middle_Name, Staff_Last_Name, Staff_Email, Staff_Contact_Number)
            VALUES(:fname, :mname, :lname, :email, :cpnumber)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fname', $fName);
            $stmt->bindParam(':mname', $mName);
            $stmt->bindParam(':lname', $lName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpnumber', $cpNumber);
            $result = $stmt->execute();

            return (int)$this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            if ($e->errorInfo[1] === 1062) {
                throw new DatabaseException('Duplicate entry: The number you entered is already registered.',0,$e);
            }
            else {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert teacher',0,$e);
            }
        }

    }
    public function insertToStaffAndUser(string $fname, string $mname, string $lname, string $email, 
    string $cpNumber, string $password) : bool{
        $insert = true;
        try {
            $this->conn->beginTransaction();
            $staffId = $this->insertToStaff($fname, $mname, $lname, $email, $cpNumber);
            $type = 2;

            $sql = "INSERT INTO users(Password, User_Type, Staff_Id) VALUES(:password, :userType, :staffId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':userType', $type);
            $stmt->bindParam(':staffId', $staffId);
            $result = $stmt->execute();
            if(!$result) {
                $insert = false;
            }
            $this->conn->commit();
            return $insert;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
}
 