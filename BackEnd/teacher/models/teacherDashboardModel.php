<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherDashboardModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getSubjectsCount(int $staffId): int {
        try {
            $sql = "SELECT COUNT(DISTINCT ss.Section_Subjects_Id) AS count 
                    FROM section_subjects AS ss
                    INNER JOIN section_subject_teachers AS sst ON sst.Section_Subjects_Id = ss.Section_Subjects_Id 
                    WHERE sst.Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch subjects count', 0, $e);
        }
    }

    public function getTotalStudentsToGrade(int $staffId): int {
        try {
            $sql = "SELECT COUNT(DISTINCT st.Student_Id) AS count 
                    FROM section_subjects AS ss 
                    INNER JOIN section_subject_teachers AS sst ON sst.Section_Subjects_Id = ss.Section_Subjects_Id
                    LEFT JOIN students AS st ON st.Section_Id = ss.Section_Id 
                    WHERE sst.Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch students count', 0, $e);
        }
    }

    public function getLockerFilesCount(int $staffId): int {
        try {
            $sql = "SELECT COUNT(*) AS count 
                    FROM locker_files 
                    WHERE Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch locker files count', 0, $e);
        }
    }

    public function isAdviser(int $staffId): bool {
        try {
            $sql = "SELECT 1 FROM section_advisers WHERE Staff_Id = :staffId LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to check if adviser', 0, $e);
        }
    }

    public function getAdvisorySectionId(int $staffId): ?int {
        try {
            $sql = "SELECT Section_Id FROM section_advisers WHERE Staff_Id = :staffId LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result ? (int)$result : null;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to get advisory section ID', 0, $e);
        }
    }

    // Chart data methods
    public function getStudentsBiologicalSexByTeacher(int $staffId): array {
        try {
            $sql = "SELECT 
                        SUM(CASE WHEN s.Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                        SUM(CASE WHEN s.Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM students AS s
                    INNER JOIN sections AS sec ON s.Section_Id = sec.Section_Id
                    INNER JOIN section_subjects AS ss ON ss.Section_Id = sec.Section_Id
                    INNER JOIN section_subject_teachers AS sst ON sst.Section_Subjects_Id = ss.Section_Subjects_Id
                    WHERE sst.Staff_Id = :staffId 
                    AND s.Student_Status = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['Male' => 0, 'Female' => 0];
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch students biological sex distribution', 0, $e);
        }
    }

    public function getEnrolleesGradeLevelDistribution(): array {
        try {
            $sql = "SELECT 
                        SUM(CASE WHEN grade_level.Grade_Level = 'Kinder I' THEN 1 ELSE 0 END) AS Kinder1,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Kinder II' THEN 1 ELSE 0 END) AS Kinder2,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 1' THEN 1 ELSE 0 END) AS Grade1,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 2' THEN 1 ELSE 0 END) AS Grade2,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 3' THEN 1 ELSE 0 END) AS Grade3,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 4' THEN 1 ELSE 0 END) AS Grade4,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 5' THEN 1 ELSE 0 END) AS Grade5,
                        SUM(CASE WHEN grade_level.Grade_Level = 'Grade 6' THEN 1 ELSE 0 END) AS Grade6
                    FROM enrollee e 
                    JOIN educational_information ON e.Educational_Information_Id = educational_information.Educational_Information_Id
                    JOIN grade_level ON educational_information.Enrolling_Grade_Level = grade_level.Grade_Level_Id
                    JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id
                    WHERE s.Is_Expired = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
                'Kinder1' => 0, 'Kinder2' => 0, 'Grade1' => 0, 'Grade2' => 0,
                'Grade3' => 0, 'Grade4' => 0, 'Grade5' => 0, 'Grade6' => 0
            ];
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch enrollees grade level distribution', 0, $e);
        }
    }

    public function getEnrolleesBiologicalSex(): array {
        try {
            $sql = "SELECT 
                        SUM(CASE WHEN Sex = 'Male' THEN 1 ELSE 0 END) AS Male,
                        SUM(CASE WHEN Sex = 'Female' THEN 1 ELSE 0 END) AS Female
                    FROM enrollee e
                    JOIN school_year_details AS s ON s.School_Year_Details_Id = e.School_Year_Details_Id
                    WHERE s.Is_Expired = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['Male' => 0, 'Female' => 0];
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch enrollees biological sex distribution', 0, $e);
        }
    }
}

