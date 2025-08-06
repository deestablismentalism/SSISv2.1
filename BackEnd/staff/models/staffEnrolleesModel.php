<?php

declare(strict_types=1);

require_once __DIR__ . '/../../core/dbconnection.php';

class staffEnrolleesModel {
    protected $conn;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getPendingEnrollees(){
        $sql = "SELECT * FROM enrollee WHERE Enrollment_Status = 3 AND Is_Handled = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function setIsHandledStatus($id, $status) {

        $sql = "UPDATE enrollee SET Is_Handled = :status WHERE Enrollee_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
        return $result;
    }
}