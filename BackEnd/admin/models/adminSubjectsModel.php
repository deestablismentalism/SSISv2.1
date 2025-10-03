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
                    WHERE TRIM(REGEXP_REPLACE(s.Subject_Name, '[[:space:]]+', '')) = :subjectName AND gls.Grade_Level_Id = :gradeLevelId";
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
            $sql = "INSERT INTO grade_level_subjects(Subject_Id, Grade_Level_Id) VALUES (:subjectId, :gradeLevelId)";
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
    private function getAllRelatedSectionIdByGradeLevel(int $gradeLevelId) : array {
        try {
            $sql = "SELECT Section_Id FROM sections WHERE Grade_Level_Id = :gradeLevelId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to get related sections to subject',0,$e);
        }
    }
    //insertSubejctAndLevel helper function 3
    private function insertToSectionSubjects(int $subjectId, int $sectionId) :bool { //insert to section_subjects for each grade level section if successful insert subject and level
        try {
            $sql = "INSERT INTO section_subjects(Subject_Id, Section_Id) VALUES(:subjectId, :sectionId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->bindParam(':sectionId', $sectionId);
            $result = $stmt->execute();
            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to assocciate subject to section',0,$e);
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
            $normalizedName = trim(preg_replace('/\s+/','',$subjectName));
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
                $convertedId = (int) $gradeLevelId;
                try {
                    // Link subject to grade level
                    if (!$this->insertGradeLevelSubjects($subjectId, $convertedId)) {
                        $results['failed'][] = $convertedId;
                        continue;
                    }
                    
                    // Link subject to all sections in this grade level
                    $sectionIds = $this->getAllRelatedSectionIdByGradeLevel($convertedId);
                    $allSectionsLinked = true;
                    
                    foreach ($sectionIds as $sectionId) {
                        $convertedSectionId = (int)$sectionId;
                        if (!$this->insertToSectionSubjects($subjectId, $convertedSectionId)) {
                            $allSectionsLinked = false;
                            break;
                        }
                    }
                    
                    if ($allSectionsLinked) {
                        $results['success'][] = $convertedId;
                    } else {
                        $results['failed'][] = $convertedId;
                    }
                    
                } catch (PDOException $e) {
                    $results['failed'][] = $convertedId;
                    // Log the error if needed
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
                    LEFT JOIN sections AS se ON ss.Section_Id = se.Section_Id
                    LEFT JOIN grade_level_subjects AS gl ON ss.Subject_Id = gl.Subject_Id
                    LEFT JOIN grade_level AS g ON gl.Grade_Level_Id = g.Grade_Level_Id 
                    LEFT JOIN subjects AS s ON gl.Subject_Id = s.Subject_Id
                    LEFT JOIN staffs AS st ON ss.Staff_Id = st.Staff_Id";
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
    public function insertSubjectTeacher(int $subjectId, int $staffId) : bool {
        try {
            $sql = "INSERT INTO section_subjects(Staff_ID) VALUES(:staffId) WHERE Subject_Id = :subjectId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId',$staffId);
            $stmt->bindParam(':subjectId',$subjectId);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__  . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to associate teacher with subject',0,$e);        
        }
    }
}