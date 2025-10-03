<?php

require_once __DIR__ .'/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminEnrollmentTransactionsModel {
    protected $conn;
    protected $Data;
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getFollowedUpTransactions() : array {
        try {
            $sql = "SELECT et.*,
                              e.Student_First_Name,
                              e.Student_Last_Name,
                              e.Student_Middle_Name,
                              e.Learner_Reference_Number,
                              s.Staff_First_Name,
                              s.Staff_Last_Name,
                              s.Staff_Middle_Name,
                              DATE(et.Created_At) AS Date
                        FROM enrollment_transactions et
                        JOIN enrollee e ON et.Enrollee_Id = e.Enrollee_Id
                        JOIN staffs s ON et.Staff_Id = s.Staff_Id
                        WHERE et.Enrollment_Status = 4 AND Is_Approved = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to get all the followed up transactions',0,$e);
        }
    } 
    public function getDeniedTransactions() : array{
        try {
            $sql = "SELECT et.*, 
                        e.Student_First_Name,
                        e.Student_Last_Name,
                        e.Student_Middle_Name,
                        e.Learner_Reference_Number,
                        s.Staff_First_Name,
                        s.Staff_Last_Name,
                        s.Staff_Middle_Name,
                        DATE(et.Created_At) AS Date
                        FROM enrollment_transactions et 
                        JOIN enrollee e ON et.Enrollee_Id = e.Enrollee_Id
                        JOIN staffs s ON et.Staff_Id = s.Staff_Id
                        WHERE et.Enrollment_Status = 2 AND Is_Approved = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to get all the denied transactions',0,$e);
        }
    }
    public function getEnrolledTransactions() : array {
        try {
            $sql = "SELECT et.*, 
                        e.Student_First_Name,
                        e.Student_Last_Name,
                        e.Student_Middle_Name,
                        e.Learner_Reference_Number,
                        s.Staff_First_Name,
                        s.Staff_Last_Name,
                        s.Staff_Middle_Name,
                        DATE(et.Created_At) AS Date
                        FROM enrollment_transactions et 
                        JOIN enrollee e ON et.Enrollee_Id = e.Enrollee_Id
                        JOIN staffs s ON et.Staff_Id = s.Staff_Id
                        WHERE et.Enrollment_Status = 1 AND Is_Approved = 0;";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch the enrolled transactions',0,$e);
        }
    }
    public function updateNeededAction($id, $transaction) : bool{
        try {
            $sql = "UPDATE enrollment_transactions SET Transaction_Status = :transaction WHERE Enrollment_Transaction_Id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':transaction', $transaction);
            $stmt->bindParam(':id', $id);
            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to update the transaction status',0,$e);
        }
    }
}
?>