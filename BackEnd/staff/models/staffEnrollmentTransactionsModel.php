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
    // function to insert any enrollment transaction
    public function insertEnrolleeTransaction(int $enrolleeId,string $transactionCode , 
    int $enrollmentStatus,int $staffId,string $remarks,int $isApproved):bool{ // F 4.3.1
        try {
            $sql ="INSERT INTO enrollment_transactions(Enrollee_Id,Transaction_Code, Enrollment_Status, Staff_Id, Remarks, Is_Approved)
                    VALUES (:enrollee_id, :transaction_code, :enrollment_status, :staff_Id,:remarks, :isApproved)";
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
            throw new DatabaseException('Failed to insert enrollee transaction',431,$e);
        }
    } 
}