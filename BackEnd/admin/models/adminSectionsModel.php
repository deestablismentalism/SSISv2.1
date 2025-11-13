<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSectionsModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //private helper1
    private function insertSection($sectionName, $gradeLevel) : int {
        try {
            $sql = "INSERT INTO sections(Section_Name, Grade_Level_Id) VALUES (:section_name, :grade_level)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':section_name', $sectionName);
            $stmt->bindParam(':grade_level', $gradeLevel);
            $stmt->execute();

            return (int)$this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            throw new DatabaseException('Inserting sections failed', 0, $e);
        }
    }
    //RECONCILIATION QUERY: ALWAYS CREATE MISSED RELATIONS OF SUBJECTS
    private function reconcileSectionSubjectsByGradeLevel(int $gradeLevelId) : void {
        try {
            $sql = "INSERT INTO section_subjects (Subject_Id, Section_Id)
                SELECT s.Subject_Id, sec.Section_Id
                FROM grade_level_subjects s
                JOIN sections sec ON sec.Grade_Level_Id = s.Grade_Level_Id
                LEFT JOIN section_subjects ss 
                    ON ss.Subject_Id = s.Subject_Id AND ss.Section_Id = sec.Section_Id
                WHERE s.Grade_Level_Id = :gradeLevelId AND ss.Subject_Id IS NULL
                ";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':gradeLevelId', $gradeLevelId, PDO::PARAM_INT);
            $stmt->execute();
        } catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to reconcile section_subjects', 0, $e);
        }
    }
    private function checkIfSectionNameExists($sectionName, ?int $id = null) : bool {
        try {
            $sql = "SELECT 1 FROM sections WHERE Section_Name = :sectionName";
            if($id !== null) {
                $sql .= " AND Section_Id != :id";
            }
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':sectionName', $sectionName);
            if($id !== null) {
                $stmt->bindParam(':id', $id);
            }
            $stmt->execute();
            $result = $stmt->fetchColumn();

            return (bool) $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to check if section name exists', 0, $e);
        }    
    }
    public function insertSectionAndSectionSubjects(string $sectionName, int $gradeLevelId) : array {
        $results = [
            'success'=> [],
            'failed'=>[],
            'existing'=> false
        ];
        try {
            $this->conn->beginTransaction();
            $hasExistingName = $this->checkIfSectionNameExists($sectionName);
            //return early if existing
            if($hasExistingName) {
                $results['existing'] = true;
                return $results;
            }
            $sectionId = $this->insertSection($sectionName, $gradeLevelId);
            try {
                $this->reconcileSectionSubjectsByGradeLevel($gradeLevelId);
                $results['success'][] = $sectionId;
            }
            catch(DatabaseException $e) {
                $results['failed'][] = $sectionId;
            }
            $this->conn->commit();
            return $results;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("[".date('Y-m-d H:i:s')."] ".$e->getMessage()."\n", 3, __DIR__.'/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert section', 0 ,$e);
        }
    }
    public function getAllTeachers() : array {
        try {
            $sql = "SELECT Staff_Id,Staff_First_Name, Staff_Middle_Name, Staff_Last_Name FROM staffs WHERE Staff_type = 2";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;            
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all the teachers failed', 0, $e);
        }
    }
    public function getApplicableSubjectsByGradeLevel(int $sectionId) : array {
        try {
            $sql = "SELECT s.Subject_Name, ss.Schedule_Day, 
                    DATE_FORMAT(ss.Time_Start, '%H:%i') AS Time_Start, DATE_FORMAT(ss.Time_End, '%H:%i') AS Time_End,
                    staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name
                    FROM grade_level_subjects AS gls
                    JOIN sections AS se ON se.Grade_Level_Id = gls.Grade_Level_Id
                    JOIN subjects AS s ON s.Subject_Id = gls.Subject_Id
                    LEFT JOIN section_subjects AS ssu ON ssu.Subject_Id = gls.Subject_Id AND ssu.Section_Id = se.Section_Id
                    LEFT JOIN section_subject_teachers AS sst ON sst.Section_Subjects_Id = ssu.Section_Subjects_Id
                    LEFT JOIN section_schedules AS ss ON ss.Section_Subjects_Id = ssu.Section_Subjects_Id
                    LEFT JOIN staffs AS staff ON staff.Staff_Id = sst.Staff_Id
                    WHERE se.Section_Id = :id GROUP BY s.Subject_Name, staff.Staff_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $sectionId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch applicable subjects',0,$e);
        }
    }
    public function checkCurrentAdviser(int $sectionId) : ?int {
        try {
            $sql = "SELECT Staff_Id FROM section_advisers WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $sectionId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (int)$result['Staff_Id'] : null;  
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching current adviser failed', 0, $e);
        }
    }
    public function getSectionMaleStudents($id) : array{
        try {
            $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name, 
                    e.Student_Email, e.Learner_Reference_Number, st.Student_Status
                    FROM sections AS s 
                LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Male'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all male students failed', 0, $e);
        }
    }
    public function getSectionFemaleStudents($id) : array {
        try {
            $sql = "SELECT s.Section_Id, st.Section_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name,
                    e.Student_Email, e.Learner_Reference_Number, st.Student_Status
                    FROM sections AS s 
                    LEFT JOIN students AS st ON s.Section_Id = st.Section_Id 
                    LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id WHERE st.Section_Id = :id And st.Sex = 'Female'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all female students failed', 0, $e);
        }
    }
    
    public function getSectionAllStudents($id) : array {
        try {
            $sql = "SELECT st.Student_Id, e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name,
                    e.Student_Email, e.Learner_Reference_Number, st.Student_Status, st.Sex
                    FROM students AS st 
                    LEFT JOIN enrollee AS e ON st.Enrollee_Id = e.Enrollee_Id 
                    WHERE st.Section_Id = :id 
                    ORDER BY e.Student_Last_Name, e.Student_First_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Fetching all students failed', 0, $e);
        }
    }
    
    public function getSectionStats($id) : ?array {
        try {
            $sql = "SELECT 
                    COUNT(DISTINCT st.Student_Id) AS Total_Students,
                    COUNT(DISTINCT CASE WHEN st.Sex = 'Male' THEN st.Student_Id END) AS Boys,
                    COUNT(DISTINCT CASE WHEN st.Sex = 'Female' THEN st.Student_Id END) AS Girls,
                    COUNT(DISTINCT ss.Subject_Id) AS Total_Subjects
                    FROM sections s
                    LEFT JOIN students st ON s.Section_Id = st.Section_Id
                    LEFT JOIN section_subjects ss ON s.Section_Id = ss.Section_Id
                    WHERE s.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch section stats', 0, $e);
        }
    }
    public function getSectionAdviserName($id) : ?array {
        try {
            $sql = "SELECT staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name FROM section_advisers AS sa
                    INNER JOIN staffs AS staff ON sa.Staff_Id = staff.Staff_Id WHERE sa.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch Adviser name', 0, $e);
        }
    }
    public function getSectionName($id) : ?array {
        try {
            $sql = "SELECT Section_Name, gl.Grade_Level FROM sections INNER JOIN grade_level AS gl ON sections.Grade_Level_Id = gl.Grade_Level_Id WHERE sections.Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch section name', 0, $e);
        }

    }
    public function getSectionsListInformation() : array { //should return section name, adviser, subjects, and the students in this section.
        try {
            $sql = "SELECT s.*, gl.Grade_Level, sa.Staff_Id, staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name, COUNT(DISTINCT st.Student_Id) AS Students FROM sections s 
                LEFT JOIN grade_level AS gl ON s.Grade_Level_Id = gl.Grade_Level_Id LEFT JOIN students As st ON st.Section_Id = s.Section_Id 
                LEFT JOIN section_advisers AS sa ON s.Section_Id = sa.Section_Id 
                LEFT JOIN staffs as staff ON sa.Staff_Id = staff.Staff_Id GROUP BY s.Section_Id, gl.Grade_Level, s.Section_Name;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOExceptin $e) {
            throw new DatabaseException('Failed to fetch section list information', 0, $e);
        }
    }
    
    public function getSectionsByGradeLevelWithCounts() : array {
        try {
            $sql = "SELECT 
                gl.Grade_Level_Id,
                gl.Grade_Level,
                s.Section_Id,
                s.Section_Name,
                sa.Staff_Id,
                staff.Staff_First_Name,
                staff.Staff_Last_Name,
                staff.Staff_Middle_Name,
                COUNT(DISTINCT CASE WHEN st.Sex = 'Male' THEN st.Student_Id END) AS Boys,
                COUNT(DISTINCT CASE WHEN st.Sex = 'Female' THEN st.Student_Id END) AS Girls,
                COUNT(DISTINCT st.Student_Id) AS Total,
                (
                    SELECT COUNT(*) FROM students st2 WHERE st2.Grade_Level_Id = gl.Grade_Level_Id
                    AND st2.Section_Id IS NULL
                ) AS Unassigned
            FROM grade_level gl
            LEFT JOIN sections s ON gl.Grade_Level_Id = s.Grade_Level_Id
            LEFT JOIN students st ON s.Section_Id = st.Section_Id
            LEFT JOIN section_advisers sa ON s.Section_Id = sa.Section_Id
            LEFT JOIN staffs staff ON sa.Staff_Id = staff.Staff_Id
            GROUP BY gl.Grade_Level_Id, gl.Grade_Level, s.Section_Id, s.Section_Name, sa.Staff_Id, staff.Staff_First_Name, staff.Staff_Last_Name, staff.Staff_Middle_Name
            ORDER BY gl.Grade_Level_Id, s.Section_Name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch sections by grade level with counts', 0, $e);
        }
    }
    public function getSectionsListByGradeLevelId(int $gradeLevel):array {
        try {
            $sql  = "SELECT * FROM sections WHERE Grade_Level_Id = :grade_level";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':grade_level'=>$gradeLevel]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch sections by grade level with counts', 0, $e);
        }
    }
    private function updateSectionName(string $sectionName, int $sectionId) : bool {
        try {
            $sql = "UPDATE sections SET Section_Name = :sectionName WHERE Section_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $sectionId);
            $stmt->bindParam(':sectionName', $sectionName);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to update section name', 0 ,$e);
        }
    }
    public function updateSectionNameResult(string $sectionName, int $sectionId) : array {
        $result = [
            'success'=> true,
            'existing'=> false
        ];
        try {
            $isExisting = $this->checkIfSectionNameExists($sectionName, $sectionId);
            if($isExisting) {
                $result['existing'] = true;
            }
            $update = $this->updateSectionName($sectionName, $sectionId);
            if(!$update) {
                $result['success']=false;
            }

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to execute update of section name',0,$e);
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
            throw new DatabaseException('Failed to fetch active school year', 0, $e);
        }
    }
    public function upsertAdviser(int $sectionId, int $staffId) : bool{
        try {
            $this->conn->beginTransaction();
            // Get active school year details
            $schoolYear = $this->getActiveSchoolYear();
            $schoolYearId = $schoolYear ? (int)$schoolYear['School_Year_Details_Id'] : null;
            if(is_null($schoolYearId)) {
                throw new DatabaseException("Cannot insert. No valid academic year found");
            }
            if($this->checkIfSectionAdviserExists($staffId,$schoolYearId)) {
                throw new DatabaseException("Staff assigned is already an adviser");
            }
            $sql = "INSERT INTO section_advisers(Section_Id,Staff_Id,School_Year_Details_Id)
                    VALUES(:sectionId,:staffId,:syId) ON DUPLICATE KEY UPDATE Staff_Id = VALUES(Staff_Id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':sectionId'=>$sectionId,':staffId'=>$staffId,':syId'=>$schoolYearId]);
            if($stmt->rowCount()===0) {
                return false;
            }
            $this->conn->commit();
            return true;
        }
        catch(PDOException $e) {
            $this->conn->rollBack();
            throw new DatabaseException('Failed to update section adviser: ' . $e->getMessage(), 0, $e);
        }
    }
    private function checkIfSectionAdviserExists(int $staffId, $syId):bool {
        try {
            $sql = "SELECT 1 FROM section_advisers WHERE Staff_Id = :staffId AND School_Year_Details_Id = :syId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId,':syId'=>$syId]);
            return (bool)$stmt->fetchColumn();
        }   
        catch(PDOException $e) {
            throw new DatabaseException('Section adviser check failed',0,$e);
        }
    }
    public function checkIfSubjectTeacherExists($staffId) : ?array {
        try {
            $sql = "SELECT Subject_Id FROM section_subjects WHERE Staff_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $staffId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ?: null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to check the subject teacher', 0, $e);
        }
    }
}