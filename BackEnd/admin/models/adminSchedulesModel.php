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
    //GETTERS
    public function getSelectedSection(int $sectionId):?string {
        try {
            $sql = "SELECT Section_Name FROM sections WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$sectionId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return (string)$result['Section_Name'] ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new Exception('Failed to current section',0,$e);
        }
    }
    public function getSectionSubjectsTimetable(int $sectionId) : array {
        try {
            $sql = "SELECT 
                        CONCAT(DATE_FORMAT(ss.Time_Start, '%H:%i'), ' - ', DATE_FORMAT(ss.Time_End, '%H:%i')) AS Time_Range,
                        MAX(CASE WHEN ss.Schedule_Day = 1 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Monday,
                        MAX(CASE WHEN ss.Schedule_Day = 2 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Tuesday,
                        MAX(CASE WHEN ss.Schedule_Day = 3 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Wednesday,
                        MAX(CASE WHEN ss.Schedule_Day = 4 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Thursday,
                        MAX(CASE WHEN ss.Schedule_Day = 5 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Friday,
                        MAX(CASE WHEN ss.Schedule_Day = 6 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Saturday,
                        MAX(CASE WHEN ss.Schedule_Day = 7 
                            THEN CONCAT(su.Subject_Name, ' (', COALESCE(st.Staff_First_Name, 'No teacher'), ')') END) AS Sunday
                    FROM section_schedules AS ss
                    JOIN section_subjects AS ssu 
                        ON ssu.Section_Subjects_Id = ss.Section_Subjects_Id
                    JOIN subjects AS su 
                        ON su.Subject_Id = ssu.Subject_Id
                    LEFT JOIN section_subject_teachers AS sst 
                        ON sst.Section_Subjects_Id = ssu.Section_Subjects_Id
                    LEFT JOIN staffs AS st 
                        ON st.Staff_Id = sst.Staff_Id
                    WHERE ssu.Section_Id = :id
                    GROUP BY ss.Time_Start, ss.Time_End
                    ORDER BY ss.Time_Start;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$sectionId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch section time table',0,$e);
        }
    }
    public function getSchedulesGroupedByDay(int $sectionSubjectId):array {
        try {
            $sql = "SELECT
                    ss.Section_Subjects_Id,
                    CASE ssc.Schedule_Day
                        WHEN 1 THEN 'Monday'
                        WHEN 2 THEN 'Tuesday'
                        WHEN 3 THEN 'Wednesday'
                        WHEN 4 THEN 'Thursday'
                        WHEN 5 THEN 'Friday'
                        WHEN 6 THEN 'Saturday'
                        WHEN 7 THEN 'Sunday'
                    END AS Day,
                    ssc.Time_Start,
                    ssc.Time_End
                FROM section_subjects AS ss
                LEFT JOIN section_schedules AS ssc 
                    ON ssc.Section_Subjects_Id = ss.Section_Subjects_Id
                WHERE ss.Section_Subjects_Id = :sectionSubjectsId
                ORDER BY ssc.Schedule_Day;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':sectionSubjectsId'=>$sectionSubjectId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException("Failed to fetch this section subject's weekly scheduling",0,$e);
        }
    }
    public function getSectionSubjectsAndSchedulesById(int $sectionId) : array {
        try {
            $sql = "SELECT 
                        ssu.Section_Subjects_Id,
                        s.Section_Name,
                        su.Subject_Name,
                        COUNT(
                            DISTINCT ss.Schedule_Day
                        ) AS Scheduled_Days
                    FROM section_subjects AS ssu
                    JOIN sections AS s 
                        ON ssu.Section_Id = s.Section_Id
                    JOIN subjects AS su 
                        ON ssu.Subject_Id = su.Subject_Id
                    LEFT JOIN section_schedules AS ss 
                        ON ss.Section_Subjects_Id = ssu.Section_Subjects_Id
                    WHERE s.Section_Id = :id
                    GROUP BY ssu.Section_Subjects_Id, s.Section_Name, su.Subject_Name
                    ORDER BY su.Subject_Name;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id'=>$sectionId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch section subjects',0,$e);
        }
    }
    public function getSectionsGroupedBySchedulesCount():array {
        try {
            $sql = "SELECT 
                    gl.Grade_Level_Id,
                    gl.Grade_Level,
                    s.Section_Id,
                    s.Section_Name,
                    COUNT(DISTINCT ss.Section_Subjects_Id) AS Total_Subjects,
                    COUNT(DISTINCT sch.Section_Schedules_Id) AS Scheduled_Subjects
                FROM grade_level AS gl
                LEFT JOIN sections AS s ON gl.Grade_Level_Id = s.Grade_Level_Id
                LEFT JOIN section_subjects AS ss ON s.Section_Id = ss.Section_Id
                LEFT JOIN section_schedules AS sch ON ss.Section_Subjects_Id = sch.Section_Subjects_Id
                GROUP BY gl.Grade_Level_Id, s.Section_Id
                ORDER BY gl.Grade_Level_Id, s.Section_Name;";
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
    //HELPERS
    private function getSchoolYearId():?int {
        try {
            $sql = "SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 0 ORDER BY Starting_Date LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return !empty($result) ? (int)$result['School_Year_Details_Id'] : null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch section time table',0,$e);
        }
    }
    private function mapDayToNumber(string|int $day): int {
        if (is_numeric($day)) return (int)$day;
        $map = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        ];
        return $map[$day] ?? 0; // Return 0 if invalid day
    }
        /**
     * Returns:
     *  'inserted' => successfully inserted/updated
     *  'overlap'  => skipped due to overlapping schedule
     *  'failed'   => failed to insert/update
     */
    private function returnUpsertSectionSchedule(int $sectionSubjectId, int $day, string $timeStart, string $timeEnd, int $schoolYearDetailsId): string {
        try {
            // 1️⃣ Check for overlapping schedule
            $sqlCheck = "SELECT 1 FROM section_schedules
                        WHERE Section_Subjects_Id = :sectionSubjectId
                        AND Schedule_Day = :day
                        AND School_Year_Details_Id = :sydId
                        AND (
                            (:timeStart BETWEEN Time_Start AND Time_End)
                            OR (:timeEnd BETWEEN Time_Start AND Time_End)
                            OR (Time_Start BETWEEN :timeStart AND :timeEnd)
                        )
                        LIMIT 1";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([
                ':sectionSubjectId' => $sectionSubjectId,
                ':day' => $day,
                ':timeStart' => $timeStart,
                ':timeEnd' => $timeEnd,
                ':sydId' => $schoolYearDetailsId
            ]);

            if ($stmtCheck->fetchColumn()) {
                return 'overlap'; // Schedule overlaps, skip insertion
            }

            // 2️⃣ Insert or update schedule
            $sql = "INSERT INTO section_schedules
                    (Section_Subjects_Id, Schedule_Day, Time_Start, Time_End, School_Year_Details_Id) 
                    VALUES(:sectionSubjectId, :scheduleDay, :timeStart, :timeEnd, :sydId)
                    ON DUPLICATE KEY UPDATE 
                    Time_Start = VALUES(Time_Start),
                    Time_End = VALUES(Time_End)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':sectionSubjectId', $sectionSubjectId, PDO::PARAM_INT);
            $stmt->bindValue(':scheduleDay', $day, PDO::PARAM_INT);
            $stmt->bindValue(':timeStart', $timeStart, PDO::PARAM_STR);
            $stmt->bindValue(':timeEnd', $timeEnd, PDO::PARAM_STR);
            $stmt->bindValue(':sydId', $schoolYearDetailsId, PDO::PARAM_INT);
            $stmt->execute();

            return 'inserted';
        }
        catch(PDOException $e) {
            error_log("[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", 3, __DIR__ . '/../../errorLogs.txt');
            return 'failed';
        }
    }
    /**
     * Upsert schedules with overlap checks
     */
    public function upsertSectionSchedule(int $sectionSubjectId, array $schedules): array {
        $inserted = [];
        $skipped = [];
        $failed = [];

        if (empty($schedules)) {
            return [
                'success' => false,
                'message' => 'No schedules provided.',
                'inserted' => [],
                'skipped' => [],
                'failed' => []
            ];
        }

        try {
            $this->conn->beginTransaction();
            $schoolYearDetailsId = $this->getSchoolYearId();
            if (is_null($schoolYearDetailsId)) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'Cannot insert. No valid academic year found',
                    'inserted' => [],
                    'skipped' => [],
                    'failed' => []
                ];
            }
            $hasChanges = false;
            foreach ($schedules as $schedule) {
                $dayName = $schedule['day'] ?? '';
                $day = $this->mapDayToNumber($dayName);
                $timeStart = trim($schedule['timeStart'] ?? '');
                $timeEnd = trim($schedule['timeEnd'] ?? '');

                if ($day === 0 || empty($timeStart) || empty($timeEnd)) {
                    $skipped[] = $dayName ?: 'Invalid';
                    continue;
                }
                $result = $this->returnUpsertSectionSchedule($sectionSubjectId, $day, $timeStart, $timeEnd, $schoolYearDetailsId);
                switch($result) {
                    case 'inserted':
                        $inserted[] = $dayName;
                        $hasChanges = true;
                        break;
                    case 'overlap':
                        $skipped[] = $dayName . ' (overlap)';
                        break;
                    case 'failed':
                        $failed[] = $dayName;
                        break;
                }
            }
            if (!$hasChanges && empty($inserted) && empty($skipped)) {
                $this->conn->rollBack();
                return [
                    'success' => false,
                    'message' => 'No valid schedule changes detected.',
                    'inserted' => $inserted,
                    'skipped' => $skipped,
                    'failed' => $failed
                ];
            }
            $this->conn->commit();
            $messageParts = [];
            if ($inserted) $messageParts[] = 'Saved: ' . implode(', ', $inserted);
            if ($skipped) $messageParts[] = 'Skipped: ' . implode(', ', $skipped);
            if ($failed) $messageParts[] = 'Failed: ' . implode(', ', $failed);
            return [
                'success' => true,
                'message' => implode(' | ', $messageParts),
                'inserted' => $inserted,
                'skipped' => $skipped,
                'failed' => $failed
            ];

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", 3, __DIR__ . '/../../errorLogs.txt');
            return [
                'success' => false,
                'message' => 'Database error occurred.',
                'inserted' => $inserted,
                'skipped' => $skipped,
                'failed' => $failed
            ];
        }
    }
}