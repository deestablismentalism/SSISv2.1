<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSchedulesModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function insertSectionSchedule(int $sectionSubjectId, $day,$timeStart, $timeEnd) : bool {
        try {
            $sql = "INSERT INTO section_schedules(Section_Subjects_Id, Schedule_Day, Time_Start, Time_End) 
                    VALUES(:sectionSubjectId, :scheduleDay, :timeStart, :timeEnd)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionSubjectId', $sectionSubjectId);
            $stmt->bindParam(':scheduleDay', $day);
            $stmt->bindParam(':timeStart',$timeStart);
            $stmt->bindParam(':timeEnd', $timeEnd);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert section schedule',0,$e);
        }
    }
    public function getAllSchedules() : array {
        try {
            $sql = "SELECT ss.Section_Subjects_Id, ss.Schedule_Day, 
                    DATE_FORMAT(Time_Start, '%H:%i') AS Time_Start,
                    DATE_FORMAT(Time_End, '%H:%i') AS Time_End,    
                    s.Section_Name, su.Subject_Name FROM section_schedules AS ss 
                    JOIN section_subjects AS ssu ON ss.Section_Subjects_Id = ssu.Section_Subjects_Id
                    JOIN sections AS s ON ssu.Section_Id = s.Section_Id
                    JOIN subjects AS su ON ssu.Subject_Id = su.Subject_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new Exception('Failed to fetch schedules',0,$e);
        }
    }
    public function getAllSectionSubjects() : array {
        try {
            $sql = "SELECT ss.Section_Subjects_Id, s.Section_Name, su.Subject_Name FROM section_subjects AS ss 
                    JOIN sections AS s ON ss.Section_Id = s.Section_Id 
                    JOIN subjects AS su ON ss.Subject_Id = su.Subject_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch section subjects',0,$e);
        }
    }
}