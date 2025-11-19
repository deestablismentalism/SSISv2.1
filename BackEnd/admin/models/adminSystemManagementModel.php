<?php 
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
date_default_timezone_set('Asia/Manila');
class adminSystemManagementModel {
    protected $conn;

    public function __construct() {
        $this->checkConnection();
    }
    private function checkConnection():void {
        try {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }
        catch(DatabaseConnectionException $e) {
            header("Location: ../../../FrontEnd/pages/errorPage/500.php?from=admin/admin_dashboard.php");
            die();
        }
    }
    //GETTERS
    public function getSchoolYearDateFormat():array {
        try {
            $sql = "SELECT School_Year_Details_Id, 
                    Starting_Date, Ending_Date
                FROM school_year_details WHERE Is_Expired = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?:[];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getSchoolYearDetails():array {
        try {
            $sql = "SELECT School_Year_Details_Id, 
                 DATE_FORMAT(Starting_Date, '%W, %M %e, %Y') AS start_date,
                 DATE_FORMAT(Ending_Date, '%W, %M %e, %Y') AS end_date
                FROM school_year_details WHERE Is_Expired = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?:[];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getPartialTeacherLoginActivity():array {
        try {
            $sql = "SELECT st.Staff_First_Name, DATE_FORMAT(Logged_At, '%W, %M %e, %Y')AS readable_date, TIME_FORMAT(Logged_At,'%h:%i %p') AS readable_time 
            FROM teacher_logs AS tl
            INNER JOIN users AS u ON u.User_Id = tl.User_Id
            INNER JOIN staffs AS st ON st.Staff_Id = u.Staff_Id 
            ORDER BY Logged_At DESC LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getAllTeacherLoginActivity():array {
        try {
            $sql = "SELECT st.Staff_First_Name, DATE_FORMAT(Logged_At, '%W, %M %e, %Y')AS readable_date, TIME_FORMAT(Logged_At,'%h:%i %p') AS readable_time 
            FROM teacher_logs AS tl
            INNER JOIN users AS u ON u.User_Id = tl.User_Id
            INNER JOIN staffs AS st ON st.Staff_Id = u.Staff_Id  
            ORDER BY Logged_At DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getPartialUserLoginActivity():array {
        try {
            $sql = "SELECT r.First_Name,DATE_FORMAT(Logged_At, '%W, %M %e, %Y')AS readable_date, TIME_FORMAT(Logged_At,'%h:%i %p') AS readable_time 
            FROM user_logs AS ul
            INNER JOIN users AS u ON u.User_Id = ul.User_Id
            INNER JOIN registrations AS r ON r.Registration_id = u.Registration_Id
            ORDER BY Logged_At DESC LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getAlllUserLoginActivity():array {
        try {
            $sql = "SELECT r.First_Name, DATE_FORMAT(Logged_At, '%W, %M %e, %Y')AS readable_date, TIME_FORMAT(Logged_At,'%h:%i %p') AS readable_time 
            FROM user_logs 
            INNER JOIN users AS u ON u.User_Id = ul.User_Id
            INNER JOIN registrations AS r ON r.Registration_id = u.Registration_Id
            ORDER BY Logged_At DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch school year details',0,$e);
        }
    }
    public function getArchivedStudents() : array{
        try {
            $sql = "SELECT  s.*,
                            CASE s.Student_Status
                            WHEN 1 THEN 'Active'
                            WHEN 2 THEN 'Inactive'
                            WHEN 3 THEN 'Dropped'
                            WHEN 4 THEN 'Transferred'
                            WHEN 5 THEN 'Graduated'
                            ELSE 'Unknown'
                            END AS Status,
                            g.Grade_Level,
                            se.Section_Name
                    FROM students AS s
                    LEFT JOIN grade_level AS g ON s.Grade_Level_Id = g.Grade_Level_Id
                    LEFT JOIN sections AS se ON s.Section_Id = se.Section_Id
                    WHERE s.Is_Archived = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch all the students',0,$e);
        }
    }
    //HELPERS
    public function getSchoolYearDetailsId() {
        try {
            $sql = "SELECT School_Year_Details_Id
                        FROM school_year_details
                        WHERE Is_Expired = 0
                        ORDER BY Starting_Date DESC
                        LIMIT 1;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseExcetpion("Failed to fetch this year's school year id",0,$e);
        }
    }
    public function getArchivedSubjects() {
        try {
            $sql = "SELECT * FROM subjects WHERE Is_Archived = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseExcetpion("Failed to fetch this year's school year id",0,$e);
        }
    }
    //OPERATIONS
    public function upsertSchoolYearDetails(string $startDate,string $endDate):bool {
        try {
            $sql = "INSERT INTO school_year_details (Starting_Date, Ending_Date)
                        VALUES (:startDate, :endDate)
                        ON DUPLICATE KEY UPDATE
                        Starting_Date = VALUES(Starting_Date),
                        Ending_Date = VALUES(Ending_Date)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':startDate'=>$startDate,':endDate'=>$endDate]);
            if($stmt->rowCount()===0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert/update school year details',0,$e);
        }
    }
    public function getArchivedSections() {
        try {
            $sql = "SELECT * FROM sections WHERE Is_Archived = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseExcetpion("Failed to fetch this year's school year id",0,$e);
        }
    }
    public function getArchivedTeachers():array {
        try {
            $sql = "SELECT * FROM staffs WHERE Is_Archived = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
    public function getArchivedAdvisers():array {
        try {
            $sql = "SELECT acs.* FROM archive_section_adivsers acs 
                    JOIN school_year_details sy ON sy.School_Year_Details_ID = acs.School_Year_Details_Id 
                    ORDER BY sy.end_year ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
    public function getArchivedSubjectTeachers():array {
        try {
            $sql = "SELECT * FROM archive_section_subject_teachers ast
                    JOIN school_year_details sy ON sy.School_Year_Details_ID = ast.School_Year_Details_Id 
                    ORDER BY sy.end_year ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }

    public function deleteArchivedAdviser(int $staffId) {
        try {
            $sql = "DELETE FROM archive_section_advisers WHERE Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId]);
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
    public function deleteArchivedSubjectTeacher(int $staffId) {
        try {
            $sql = "DELETE FROM archive_section_subject_teachers WHERE Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId]);
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage() . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert to users',0,$e);
        }
    }
}