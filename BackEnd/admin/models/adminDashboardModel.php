<?php
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
date_default_timezone_set('Asia/Manila');
class adminDashboardModel {
    protected $conn;

    function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function EnrolleesByDays(int $days) : array {
        try {
            $sql = "SELECT DATE(Enrolled_At) as day, COUNT(*) as count 
                FROM enrollee e
                JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id
                WHERE Enrolled_At >= DATE_SUB(CURDATE(), INTERVAL ($days -1) DAY) 
                AND s.Is_Expired = 0 
                GROUP BY DATE(Enrolled_At) 
                ORDER BY day DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = [];
            for($i = 0; $i < $days ; $i++) {
                $day = date('Y-m-d', strtotime("-$i days"));
                $count[$day] = 0;
            }
            foreach($result as $rows) {
                $count[$rows['day']] = (int)$rows['count'];
            }
            return $count;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch enrollees count by day', 0, $e);
        }
    }
    public function countTotalEnrollees() : int {
        try {
            $sql = "SELECT COUNT(*) AS enrollee_count FROM enrollee e JOIN school_year_details 
            JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id 
            WHERE s.Is_Expired = 0;";
            $stmt= $this->conn->prepare($sql);
            $stmt->execute();
            $totalEnrollees = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$totalEnrollees['enrollee_count'];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count total enrollees', 0, $e);
        }
    }
    public function countTotalDeniedFollowUp() : int {
        try {
            $sql = "SELECT COUNT(*) AS total_count FROM enrollment_transactions et JOIN (
                              SELECT Enrollee_Id, MIN(Enrollment_Transaction_Id) AS LatestTransaction
                              FROM enrollment_transactions
                              GROUP BY Enrollee_Id
                              ) latest_et ON et.Enrollee_Id = latest_et.Enrollee_Id
                              AND et.Enrollment_Transaction_Id = latest_et.LatestTransaction;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $totalDeniedFollowedUp = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$totalDeniedFollowedUp['total_count'];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count denied and followed up',0,$e);
        }
    }
    public function EnrolleeStatuses() : ?array {
        try {
            $sql = "SELECT 
                    SUM(CASE WHEN Enrollment_Status = 1 THEN 1 ELSE 0 END)AS enrolled_count, 
                    SUM(CASE WHEN Enrollment_Status = 2 THEN 1 ELSE 0 END) AS denied_count,
                    SUM(CASE WHEN Enrollment_Status = 3 THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN Enrollment_Status = 4 THEN 1 ELSE 0 END) AS follow_up_count
                                  FROM enrollee e JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id 
                                  WHERE Is_Expired = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $enrolleeCount = $stmt->fetch(PDO::FETCH_ASSOC);

            return $enrolleeCount ?: null;
        }
        catch(PDOException  $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count enrollee statuses');
        }
    }
    public function EnrolleeGradeLevels() : ?array {
        try {
            $sql = "SELECT SUM(CASE WHEN grade_level.Grade_Level = 'Kinder I' THEN 1 ELSE 0 END ) AS Kinder1,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Kinder II' THEN 1 ELSE 0 END ) AS Kinder2,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 1' THEN 1 ELSE 0 END ) AS Grade1,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 2' THEN 1 ELSE 0 END ) AS Grade2,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 3' THEN 1 ELSE 0 END ) AS Grade3,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 4' THEN 1 ELSE 0 END ) AS Grade4,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 5' THEN 1 ELSE 0 END ) AS Grade5,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 6' THEN 1 ELSE 0 END ) AS Grade6
                    FROM enrollee e JOIN educational_information ON e.Educational_Information_Id = educational_information.Educational_Information_Id 
                    JOIN grade_level ON educational_information.Enrolling_Grade_Level = grade_level.Grade_Level_Id
                    JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id
                    WHERE Is_Expired = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch counts per grade level', 0 , $e);
        }
    }
    public function EnrolleeBiologicalSex() : ?array {
        try {
            $sql = "SELECT SUM(CASE WHEN Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                           SUM(CASE WHEN Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM enrollee e JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id
                    WHERE s.Is_Expired = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null ;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch counts per biological sex', 0 ,$e);
        }
    }
    public function countTotalStudents() : int {
        try {
            $sql = "SELECT COUNT(*) AS TotalStudents FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$result['TotalStudents'];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count total students', 0 ,$e);
        }
    }
    public function StudentStatuses() : ?array {
        try {
            $sql = "SELECT  SUM(CASE WHEN Student_Status = 0 THEN 1 ELSE 0 END) AS  Waiting,
                            SUM(CASE WHEN Student_Status = 1 THEN 1 ELSE 0 END) AS ActiveStudents,
                           SUM(CASE WHEN Student_Status = 2 THEN 1 ELSE 0 END) AS InactiveStudents,
                           SUM(CASE WHEN Student_Status = 3 THEN 1 ELSE 0 END) AS DroppedStudents,
                           SUM(CASE WHEN Student_Status = 4 THEN 1 ELSE 0 END) AS Transferred,
                           SUM(CASE WHEN Student_Status = 5 THEN 1 ELSE 0 END) AS Graduated
                    FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch counts per student status', 0 ,$e);
        }
    }
    public function PendingEnrolleesInformation() : array {
        try {
            $sql_get_pending_enrollees = "SELECT enrollee.Enrollee_Id,
                                        enrollee.Learner_Reference_Number,
                                        enrollee.Student_First_Name,
                                        enrollee.Student_Middle_Name,
                                        enrollee.Student_Last_Name,
                                        enrollee.Student_Extension,

                                        enrolling_level.Grade_Level AS E_Grade_Level
                                        FROM enrollee
                                        JOIN educational_information ON enrollee.Educational_Information_Id = educational_information.Educational_Information_Id
                                        INNER JOIN grade_level as enrolling_level ON enrolling_level.Grade_Level_Id = educational_information.Enrolling_Grade_Level
                                        WHERE Enrollment_Status = 3 AND Is_Handled = 0
                                        ORDER BY Enrollee_Id DESC
                                        LIMIT 5";
            $get_pending_enrollees = $this->conn->prepare($sql_get_pending_enrollees);
            $get_pending_enrollees->execute();
            $pending_enrollees = $get_pending_enrollees->fetchAll(PDO::FETCH_ASSOC);
            return $pending_enrollees;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch pending enrollees information', 0 , $e);
        }
    }
    // New methods for student grade level distribution
    public function StudentGradeLevels() : ?array {
        try {
            $sql = "SELECT SUM(CASE WHEN Grade_Level_Id = 1 THEN 1 ELSE 0 END) AS Kinder1,
                           SUM(CASE WHEN Grade_Level_Id = 2 THEN 1 ELSE 0 END) AS Kinder2,
                           SUM(CASE WHEN Grade_Level_Id = 3 THEN 1 ELSE 0 END) AS Grade1,
                           SUM(CASE WHEN Grade_Level_Id = 4 THEN 1 ELSE 0 END) AS Grade2,
                           SUM(CASE WHEN Grade_Level_Id = 5 THEN 1 ELSE 0 END) AS Grade3,
                           SUM(CASE WHEN Grade_Level_Id = 6 THEN 1 ELSE 0 END) AS Grade4,
                           SUM(CASE WHEN Grade_Level_Id = 7 THEN 1 ELSE 0 END) AS Grade5,
                           SUM(CASE WHEN Grade_Level_Id = 8 THEN 1 ELSE 0 END) AS Grade6
                    FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count student per grade level', 0 ,$e);
        }
    }
    public function StudentsBiologicalSex() : ?array {
        try {
            $sql = "SELECT SUM(CASE WHEN Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                           SUM(CASE WHEN Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null; 
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to count students per biological sex', 0 ,$e);
        }
    }
    
}
?>