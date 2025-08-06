<?php
require_once __DIR__ . '/../../core/dbconnection.php';
date_default_timezone_set('Asia/Manila');
class adminDashboardModel {
    protected $conn;

    function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function EnrolleesByDays(int $days) {
        $sql = "SELECT DATE(Enrolled_At) as day, COUNT(*) as count 
                FROM enrollee 
                WHERE Enrolled_At >= DATE_SUB(CURDATE(), INTERVAL $days DAY) 
                GROUP BY DATE(Enrolled_At) 
                ORDER BY day DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = [];
        for($i = 0; $i < $days; $i++) {
            $day = date('Y-m-d', strtotime("-$i days"));
            $count[$day] = 0;
        }
        foreach($result as $rows) {
            $count[$rows['day']] = (int)$rows['count'];
        }
        return $count;
    }
    public function TotalEnrollees() {
        try {
            $sql_get_total_enrollees = "SELECT COUNT(*) AS enrollee_count FROM enrollee;";
            $get_total_enrollees = $this->conn->prepare($sql_get_total_enrollees);
            $get_total_enrollees->execute();
            $total_enrollees = $get_total_enrollees->fetch(PDO::FETCH_ASSOC);

            return (int)$total_enrollees['enrollee_count'];
        }
        catch(PDOException $e) {
            return['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    public function TotalDeniedFollowUp() {
        try {
            $sql_get_total_denied_follow_up = "SELECT COUNT(*) AS total_count FROM enrollment_transactions et JOIN (
                              SELECT Enrollee_Id, MIN(Enrollment_Transaction_Id) AS LatestTransaction
                              FROM enrollment_transactions
                              GROUP BY Enrollee_Id
                              ) latest_et ON et.Enrollee_Id = latest_et.Enrollee_Id
                              AND et.Enrollment_Transaction_Id = latest_et.LatestTransaction;";
            $get_total_denied_follow_up = $this->conn->prepare($sql_get_total_denied_follow_up);
            $get_total_denied_follow_up->execute();
            $total_denied_follow_up = $get_total_denied_follow_up->fetch(PDO::FETCH_ASSOC);
            return (int)$total_denied_follow_up['total_count'];
        }
        catch(PDOException $e) {
            return['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    public function EnrolleeStatuses(){
        try {
            $sql_get_enrolled = "SELECT SUM(CASE WHEN Enrollment_Status = 1 THEN 1 ELSE 0 END)AS enrolled_count, 
                                        SUM(CASE WHEN Enrollment_Status = 2 THEN 2 ELSE 0 END) AS denied_count,
                                        SUM(CASE WHEN Enrollment_Status = 3 THEN 3 ELSE 0 END) AS pending_count,
                                        SUM(CASE WHEN Enrollment_Status = 4 THEN 4 ELSE 0 END) AS follow_up_count
                                  FROM enrollee;";
            $get_enrolled = $this->conn->prepare($sql_get_enrolled);
            $get_enrolled->execute();
            $enrollee_count = $get_enrolled->fetch(PDO::FETCH_ASSOC);

            if(!$enrollee_count) {
                throw new PDOException('enrollee statuses not found');
            }
            return $enrollee_count;
        }
        catch(PDOException  $e) {
            return['succes'=> false ,'message' => $e->getMessage()];
        }
    }
    public function EnrolleeGradeLevels() {
        try {
            $sql = "SELECT SUM(CASE WHEN grade_level.Grade_Level = 'Kinder I' THEN 1 ELSE 0 END ) AS Kinder1,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Kinder II' THEN 1 ELSE 0 END ) AS Kinder2,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 1' THEN 1 ELSE 0 END ) AS Grade1,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 2' THEN 1 ELSE 0 END ) AS Grade2,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 3' THEN 1 ELSE 0 END ) AS Grade3,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 4' THEN 1 ELSE 0 END ) AS Grade4,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 5' THEN 1 ELSE 0 END ) AS Grade5,
                           SUM(CASE WHEN grade_level.Grade_Level = 'Grade 6' THEN 1 ELSE 0 END ) AS Grade6
                    FROM enrollee JOIN educational_information ON enrollee.Educational_Information_Id = educational_information.Educational_Information_Id 
                    JOIN grade_level ON educational_information.Enrolling_Grade_Level = grade_level.Grade_Level_Id;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$result) {
                throw new PDOException('enrollee grade levels not found');
            }
            return $result;
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message' => $e->getMessage()];
        }
    }
    public function EnrolleeBiologicalSex() {
        try {
            $sql = "SELECT SUM(CASE WHEN Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                           SUM(CASE WHEN Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM enrollee;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$result) {
                throw new PDOException('enrollee biological sex not found');
            }
            return $result;
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    public function countTotalStudents() {
        try {
            $sql = "SELECT COUNT(*) AS TotalStudents FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (int)$result['TotalStudents'];
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    public function StudentStatuses() {
        try {
            $sql = "SELECT SUM(CASE WHEN Student_Status = 1 THEN 1 ELSE 0 END) AS ActiveStudents,
                           SUM(CASE WHEN Student_Status = 2 THEN 2 ELSE 0 END) AS InactiveStudents,
                           SUM(CASE WHEN Student_Status = 3 THEN 3 ELSE 0 END) AS DroppedStudents
                    FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$result) {
                throw new PDOException('student statuses not found');
            }
            return $result;
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    public function PendingEnrolleesInformation() {
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
                                        WHERE Enrollment_Status = 3 AND Is_Handled = 0;
                                        ORDER BY Enrollee_Id DESC
                                        LIMIT 5";
        $get_pending_enrollees = $this->conn->prepare($sql_get_pending_enrollees);
        $get_pending_enrollees->execute();
        $pending_enrollees = $get_pending_enrollees->fetchAll(PDO::FETCH_ASSOC);
        return $pending_enrollees;
    }
    
    // New methods for student grade level distribution
    public function StudentGradeLevels() {
        try {
            $sql = "SELECT SUM(CASE WHEN Grade_Level_Id = 1 THEN 1 ELSE 0 END) AS Kinder1,
                           SUM(CASE WHEN Grade_Level_Id = 2 THEN 2 ELSE 0 END) AS Kinder2,
                           SUM(CASE WHEN Grade_Level_Id = 3 THEN 3 ELSE 0 END) AS Grade1,
                           SUM(CASE WHEN Grade_Level_Id = 4 THEN 4 ELSE 0 END) AS Grade2,
                           SUM(CASE WHEN Grade_Level_Id = 5 THEN 5 ELSE 0 END) AS Grade3,
                           SUM(CASE WHEN Grade_Level_Id = 6 THEN 6 ELSE 0 END) AS Grade4,
                           SUM(CASE WHEN Grade_Level_Id = 7 THEN 7 ELSE 0 END) AS Grade5,
                           SUM(CASE WHEN Grade_Level_Id = 8 THEN 8 ELSE 0 END) AS Grade6
                    FROM students;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$result) {
                throw new PDOException('student grade levels not found');
            }
            return $result;
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message' => $e->getMessage()];
        }
    }
    
    public function StudentsBiologicalSex() {
        try {
            $sql = "SELECT SUM(CASE WHEN Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                           SUM(CASE WHEN Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM students JOIN enrollee on enrollee.Enrollee_Id = students.Enrollee_Id;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$result) {
                throw new PDOException('students biological sex not found');
            }
            return $result; 
        }
        catch(PDOException $e) {
            return ['success'=> false, 'message'=> $e->getMessage()];
        }
    }
    
}

?>