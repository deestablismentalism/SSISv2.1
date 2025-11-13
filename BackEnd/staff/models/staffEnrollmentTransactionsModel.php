<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
class staffEnrollmentTransactionsModel {
    protected $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
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
    // function to insert any enrollment transaction
    public function insertEnrolleeTransaction(int $enrolleeId,string $transactionCode , 
    int $enrollmentStatus,int $staffId,string $remarks,int $isApproved):bool{ // F 4.3.1
        try {
            $this->conn->beginTransaction();
            // Get active school year details
            $schoolYear = $this->getActiveSchoolYear();
            $schoolYearId = $schoolYear ? (int)$schoolYear['School_Year_Details_Id'] : null;
            if(is_null($schoolYearId)) {
                throw new PDOException("Cannot insert.");
            }
            $sql ="INSERT INTO enrollment_transactions(Enrollee_Id,Transaction_Code, Enrollment_Status, Staff_Id, Remarks, Is_Approved,School_Year_Details_Id)
                    VALUES (:enrollee_id, :transaction_code, :enrollment_status, :staff_Id,:remarks, :isApproved,:syId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':enrollee_id', $enrolleeId, PDO::PARAM_INT);
            $stmt->bindParam(':transaction_code', $transactionCode);
            $stmt->bindParam(':staff_Id', $staffId, PDO::PARAM_INT);
            $stmt->bindParam(':enrollment_status', $enrollmentStatus);
            $stmt->bindParam(':remarks', $remarks);
            $stmt->bindParam(':isApproved', $isApproved);
            $stmt->execute();
            
            if($stmt->rowCount() === 0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]".$e->getMessage()."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to insert enrollee transaction',431,$e);
        }
    } 
    public function updateIsApprovedToTrue(int $enrolleeId,int $status):bool {
        try {
            $sql = "UPDATE enrollment_transactions SET Is_Approved = :status WHERE Enrollee_Id = :enrolleeId";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':status'=>$status,':enrolleeId'=>$enrolleeId]);
            if($stmt->rowCount()===0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]".$e->getMessage()."\n",3, __DIR__ . '/../../errorLogs.txt');
            throw new DatabaseException('Failed to update approved flag',0,$e);
        }
    }
}