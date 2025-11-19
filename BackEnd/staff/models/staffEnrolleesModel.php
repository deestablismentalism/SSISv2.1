<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class staffEnrolleesModel {
    protected $conn;
    public function __construct() {
        $this->init();
    }
    private function init():void {
        try{
            $db = new Connect();
            $this->conn = $db->getConnection();
        }
        catch(DatabaseConnectionException $e) {
            header("Location: ../../../FrontEnd/pages/errorPage/500.php?from=staff/staff_pending_enrollments.php");
        }
    }
    //GETTERS
    public function  getThisTeacherSectionAdvisoryId(PDO $conn,int $staffId):?int {
        try {
            $sql = "SELECT Section_Id FROM section_advisers WHERE Staff_Id = :staffId";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['Section_Id'] ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException("Failed to fetch  this Teacher's section ID",0,$e);
        }
    }
    public function getPendingEnrolleesByTeacherAdvisoryLevel(int $staffId):array{
        try {
            $sql = "SELECT e.Enrollee_Id, e.Learner_Reference_Number, e.Student_First_Name, e.Student_Last_Name, e.Student_Middle_Name,
                e.Age, e.Sex, gl.Grade_Level 
                FROM enrollee e 
                INNER JOIN educational_information ei ON e.Educational_Information_Id = ei.Educational_Information_Id
                INNER JOIN grade_level gl ON ei.Enrolling_Grade_Level = gl.Grade_Level_Id
                INNER JOIN sections s ON s.Grade_Level_Id = gl.Grade_Level_Id
                INNER JOIN section_advisers sa ON sa.Section_Id =  s.Section_Id  
                WHERE e.Enrollment_Status = 3 AND e.Is_Handled = 0 AND sa.Staff_Id = :staffId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':staffId'=>$staffId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e){
            error_log("[".date('Y-m-d H:i:s')."]" .$e->getMessage() ."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch pending enrollees');
        }
    }
    //HELPERS
    //OPERATIONS
}