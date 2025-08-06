<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';

class staffEnrollmentTransactionsModel {
    protected $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

     // function to insert any enrollment transaction
     public function insertEnrolleeTransaction($id , $transactionCode , $enrollmentStatus, $staffId, $remarks, $isApproved) { // used by postUpdateEnrolleeStatus
        // TODO: update if the database is changed, remove reason and description; replace with remarks
        $sql ="INSERT INTO enrollment_transactions(Enrollee_Id,Transaction_Code, Enrollment_Status, Staff_Id, Remarks, Is_Approved)
            VALUES (:enrollee_id, :transaction_code, :enrollment_status, :staff_Id,:remarks, :isApproved)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':enrollee_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':transaction_code', $transactionCode);
        $stmt->bindParam(':staff_Id', $staffId, PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_status', $enrollmentStatus);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':isApproved', $isApproved);
        if($stmt->execute()) {
            return ['success'=> true, 'message'=> 'Transaction inserted successfully'];
        }
        else {
            return ['success'=> false, 'message'=> 'Transaction insertion failed'];
        }
    } 
}