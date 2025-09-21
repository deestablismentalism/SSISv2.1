<?php
    
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
class adminTeachersModel {
    protected $conn;

    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function selectAllTeachers() {
        $sql_select_all_teachers = "SELECT Staff_Id, Staff_First_Name, Staff_Middle_Name, Staff_Last_Name, Staff_Contact_Number, Position FROM staffs WHERE Staff_Type = 2";
        $stmt = $this->conn->prepare($sql_select_all_teachers);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function updateSubjectTeacherToSectionSubjects(int $staffId, int $sectionSubjectsId) : bool {
        try {
            $sql = "UPDATE section_subjects SET Staff_Id = :staffId WHERE Section_Subjects_Id = :sectionSubjectsId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':staffId', $staffId);
            $stmt->bindParam(':sectionSubjectsId', $sectionSubjectsId);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] " . $e->getMessage . "\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert teacher to section subject',0,$e);
        }
    }
}
 