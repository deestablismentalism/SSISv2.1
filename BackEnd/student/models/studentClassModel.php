<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
class studentClassModel {
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
            header('Location: ../../../FrontEnd/pages/errorPage/500.php?from=user/user_all_students.php');
            die();
        }
    }
    //GETTERS
    public function getThisStudentsSubjects(int $studentId):array {
        try {
            $sql = "SELECT s.Subject_Id, s.Subject_Name 
                    FROM section_subjects ss
                    JOIN subjects s ON s.Subject_Id = ss.Subject_Id
                    WHERE ss.Section_Id = (SELECT Section_Id FROM students WHERE Student_Id = :studentId)
                    ORDER BY s.Subject_Name;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException("Failed to fetch this student's subjects",$e->getCode(),$e);
        }
    }
    public function getThisStudentsSimpleDetails(int $studentId):array {
        try {
            $sql = "SELECT LRN, First_Name,Last_Name,Middle_Name FROM students WHERE Student_Id = :studentId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException("Failed to fetch this Student's LRN",0,$e);
        }
    }
    public function getStudentClassSchedules(int $studentId):array {
        try {
            $sql = "SELECT 
                    TIME_FORMAT(
                        SEC_TO_TIME(
                            FLOOR(
                                TIME_TO_SEC(ss.Time_Start) / (60 * 60)
                            ) * (60 * 60)
                        ), '%H:%i'
                    ) AS Start_Time,
                    TIME_FORMAT(
                        SEC_TO_TIME(
                            FLOOR(
                                TIME_TO_SEC(ss.Time_End) / (60 * 60)
                            ) * (60*60)
                        ), '%H:%i'
                    ) as End_Time,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 1 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Monday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 2 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Tuesday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 3 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Wednesday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 4 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Thursday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 5 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Friday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 6 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Saturday,
                    GROUP_CONCAT(DISTINCT CASE WHEN ss.Schedule_Day = 7 THEN 
                    CONCAT(COALESCE(su.Subject_Name, 'No Subject'), ' (', COALESCE(st.Staff_First_Name, 'No Teacher Yet'), ')') ELSE NULL END) as Sunday
                FROM section_schedules AS ss
                LEFT JOIN section_subjects AS ssu ON ssu.Section_Subjects_Id = ss.Section_Subjects_Id
                LEFT JOIN subjects AS su ON su.Subject_Id = ssu.Subject_Id
                LEFT JOIN staffs AS st ON st.Staff_Id = ssu.Staff_Id
                LEFT JOIN sections AS se ON se.Section_Id = ssu.Section_Id
                WHERE se.Section_Id = (SELECT Section_Id FROM students WHERE Student_ID = :studentId)
                GROUP BY 
                    FLOOR(TIME_TO_SEC(ss.Time_Start)/ (60*60)),
                    FLOOR(TIME_TO_SEC(ss.Time_End)/(60*60))
                ORDER BY ss.Time_Start";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch student schedules',0,$e);
        }
    }
    public function getStudentGrades(int $studentId):array {
        try {
            $sql = "SELECT 
                    ss.Section_Subjects_Id,
                    su.Subject_Name,
                    MAX(CASE WHEN Quarter = 1 THEN Grade_Value END) AS Q1,
                    MAX(CASE WHEN Quarter = 2 THEN Grade_Value END) AS Q2,
                    MAX(CASE WHEN Quarter = 3 THEN Grade_Value END) AS Q3,
                    MAX(CASE WHEN Quarter = 4 THEN Grade_Value END) AS Q4
                    FROM student_grades AS sg
                    INNER JOIN section_subjects AS ss ON ss.Section_Subjects_Id = sg.Section_Subjects_Id
                    INNER JOIN subjects AS su ON su.Subject_Id = ss.Subject_Id
                    WHERE sg.Student_Id = :studentId
                    GROUP BY  su.Subject_Name,Section_Subjects_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch student grades',0,$e);
        }
    }
    public function getStudentSectionName(int $studentId):?string {
        try {
            $sql = "SELECT Section_Name FROM sections 
                WHERE Section_Id = (SELECT Section_Id FROM students WHERE Student_id = :studentId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result == null) {
                return null;
            }
            return (string)$result['Section_Name'];
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException("Failed to fetch Student's Section name",0,$e);
        }
    }
    public function getStudentSectionClassmates(int $studentId):array {
        try {
            $sql = "SELECT s2.Student_Id, s2.First_Name,s2.Last_Name,s2.Middle_Name
                    FROM students AS s2
                    INNER JOIN sections AS se ON se.Section_Id = s2.Section_Id
                    WHERE s2.Section_Id IN 
                    (
                    SELECT Section_Id
                    FROM students
                    WHERE Student_Id = :studentId)
                    ORDER BY s2.Last_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':studentId'=>$studentId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]". $e->getMessage()."\n",3,__DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch student section details',0,$e);
        }
    }
    //HELPERS
    //OPERATIONS
}