<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSubjectsModel {

    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //private checker method
    private function checkIfSubjectInSameGradeLevelExists(string $subjectName, int $gradeLevelId) : bool {
        try {
            $sql = "SELECT 1 FROM grade_level_subjects AS gls
                    INNER JOIN subjects AS s ON gls.Subject_Id = s.Subject_Id 
                    WHERE LOWER(TRIM(REGEXP_REPLACE(s.Subject_Name, '[[:space:]]+', ''))) = :subjectName AND gls.Grade_Level_Id = :gradeLevelId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjectName', $subjectName);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            return (bool)$result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to check an existing subject in the same grade level',0,$e);
        }
    }
    //insertSubjectAndLevel helper function 1
    private function insertSubject($subject) : int {
        try {
            $sql = "INSERT INTO subjects(Subject_Name) VALUES (:subjects) ON DUPLICATE KEY UPDATE Subject_Id = LAST_INSERT_ID(Subject_Id) ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjects', $subject);
            $result = $stmt->execute();
            if(!$result) {
                throw new Exception('Insert subject failed');
            }
            return (int) $this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert subject', 0,$e);
        }

    }
    //insertSubjectAndLevel helper function 2
    private function insertGradeLevelSubjects(int $subjectId, $gradeLevelId) : bool  {
        try {
            $sql = "INSERT INTO grade_level_subjects(Subject_Id, Grade_Level_Id) VALUES (:subjectId, :gradeLevelId)
                    ON DUPLICATE KEY UPDATE Subject_Id  = Subject_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId, PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert the subject for grade level',0,$e);
        }
    }
    private function reconcileSectionSubjectsByGradeLevel(int $gradeLevelId) : void {
        try {
            $sql = "INSERT INTO section_subjects (Subject_Id, Section_Id)
                SELECT s.Subject_Id, sec.Section_Id
                FROM grade_level_subjects s
                JOIN sections sec ON sec.Grade_Level_Id = s.Grade_Level_Id
                LEFT JOIN section_subjects ss 
                    ON ss.Subject_Id = s.Subject_Id AND ss.Section_Id = sec.Section_Id
                WHERE s.Grade_Level_Id = :gradeLevelId AND ss.Subject_Id IS NULL";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId, PDO::PARAM_INT);
            $stmt->execute();
        } catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to reconcile section_subjects', 0, $e);
        }
    }
    public function insertSubjectAndLevel(string $subjectName, array $gradeLevelIds): array {
        //function flow
        //1 validate the subject name in respsective inputted grade levels
        //2 Separate valid from invalid by grade level IDs
        //3 insert the subject name once if valid
        //4 insert the subject ID and valid grade level IDs
        //5 get all the sections that exist based on inputted grade level IDs
        //6 create relationship with the sections and subjects (insertToSectionSubjects) 
        $results = [    //array to check existing subjects in the same grade level, check successfull inserts and failed ones
            'success' => [],
            'failed' => [],
            'existing' => []
        ];
        try {
            $this->conn->beginTransaction();
            $normalizedName = strtolower(trim(preg_replace('/\s+/','',$subjectName)));
            // First check all grade levels for existing subjects
            $validGradeLevels = [];
            foreach ($gradeLevelIds as $gradeLevelId) {
                $convertedId = (int)$gradeLevelId;
                if ($this->checkIfSubjectInSameGradeLevelExists($normalizedName, $convertedId)) {
                    $results['existing'][] = $gradeLevelId;
                } else {
                    $validGradeLevels[] = $gradeLevelId;
                }
            }
            // If no valid grade levels, return early
            if (empty($validGradeLevels)) {
                $this->conn->rollBack();
                return $results;
            }
            // Insert the subject (only once)
            $subjectId = $this->insertSubject($subjectName);
            // Process each valid grade level
            foreach ($validGradeLevels as $gradeLevelId) {
                $convertedId = (int)$gradeLevelId;
                try {
                    // Link subject to grade level
                    if (!$this->insertGradeLevelSubjects($subjectId, $convertedId)) {
                        $results['failed'][] = $convertedId;
                        continue;
                    }
                    //CREATE A RECORD OF EACH SUBJECT AND SECTION BASED ON RELATED GRADE LEVEL
                    $this->reconcileSectionSubjectsByGradeLevel($convertedId);
                    // Link subject to all sections in this grade level
                    $sectionIds = $this->getAllRelatedSectionIdByGradeLevel($convertedId);
                    $results['success'][] = $convertedId;
                    
                } catch (PDOException $e) {
                    $results['failed'][] = $convertedId;
                    error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
                }
            }
            // Commit if we have any successes, otherwise rollback
            if (!empty($results['success'])) {
                $this->conn->commit();
            } else {
                $this->conn->rollBack();
            }
            return $results;
        } catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            $this->conn->rollBack();
            throw new DatabaseException('Failed to insert subject and grade levels', 0, $e);
        }
    }
    public function getSubjectsPerSection() : array{
        try {
            $sql = "SELECT
                    ss.Section_Subjects_Id,
                    s.Subject_Name,
                    se.Section_Name,
                    g.Grade_Level,
                    st.Staff_First_Name, st.Staff_Last_Name, st.Staff_Middle_Name FROM section_subjects AS ss
                    INNER JOIN sections AS se ON ss.Section_Id = se.Section_Id
                    INNER JOIN grade_level AS g ON g.Grade_Level_Id = se.Grade_Level_Id 
                    INNER JOIN subjects AS s ON ss.Subject_Id = s.Subject_Id
                    LEFT JOIN staffs AS st ON ss.Staff_Id = st.Staff_Id
                    ORDER BY g.Grade_Level_Id, se.Section_Name, s.Subject_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch the subjects per level',0,$e);
        }
    }
    public function getSubjectsGrouped() : array {
        try {
            $sql = "SELECT 
                    s.Subject_Id,
                    s.Subject_Name,
                    COUNT(DISTINCT ss.Section_Subjects_Id) as Section_Count
                    FROM subjects AS s
                    INNER JOIN section_subjects AS ss ON s.Subject_Id = ss.Subject_Id
                    GROUP BY s.Subject_Id, s.Subject_Name
                    ORDER BY s.Subject_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch grouped subjects',0,$e);
        }
    }
    public function getSectionsBySubjectId(int $subjectId) : array {
        try {
            $sql = "SELECT
                    ss.Section_Subjects_Id,
                    se.Section_Name,
                    g.Grade_Level,
                    g.Grade_Level_Id,
                    st.Staff_Id,
                    st.Staff_First_Name,
                    st.Staff_Middle_Name,
                    st.Staff_Last_Name
                    FROM section_subjects AS ss
                    INNER JOIN sections AS se ON ss.Section_Id = se.Section_Id
                    LEFT JOIN section_subject_teachers AS sst ON sst.Section_Subjects_Id = ss.Section_Subjects_Id
                    INNER JOIN grade_level AS g ON se.Grade_Level_Id = g.Grade_Level_Id
                    LEFT JOIN staffs AS st ON st.Staff_Id = sst.Staff_Id
                    WHERE ss.Subject_Id = :subjectId
                    ORDER BY g.Grade_Level_Id, se.Section_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch sections by subject',0,$e);
        }
    }
    private function getActiveSchoolYear() : ?array {
        try {
            $sql = "SELECT School_Year_Details_Id, start_year, end_year 
                    FROM school_year_details 
                    WHERE Is_Expired = 0 
                    ORDER BY School_Year_Details_Id DESC 
                    LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch active school year', 0, $e);
        }
    }
    public function insertSubjectTeacher(int $sectionSubjectId, int $staffId) : bool {
        try {
            $this->conn->beginTransaction();
            $schoolYear = $this->getActiveSchoolYear();
            $schoolYearId = $schoolYear ? (int)$schoolYear['School_Year_Details_Id'] : null;
            if(is_null($schoolYearId)) {
                throw new DatabaseException("Cannot insert. No valid academic year found");
            }
            $sql = "INSERT INTO section_subject_teachers(Staff_Id,Section_Subjects_Id,School_Year_Details_Id) 
            VALUES(:staffId,:sectionSubjectId,:syId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId,':sectionSubjectId'=>$sectionSubjectId,':syId'=>$schoolYearId]);
            if($stmt->rowCount()===0) {
                return false;
            }
            $this->conn->commit();
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to associate teacher with subject',0,$e);        
        }
    }
}