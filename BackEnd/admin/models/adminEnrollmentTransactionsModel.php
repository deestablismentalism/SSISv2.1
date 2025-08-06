<?php

require_once __DIR__ .'/../../core/dbconnection.php';

class adminEnrollmentTransactionsModel {
    protected $conn;
    protected $Data;
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function getFollowedUpTransactions() {
        $sql_get_data = "SELECT et.*,
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
        $get_get_data = $this->conn->prepare($sql_get_data);
        $get_get_data->execute();
        $result = $get_get_data->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getDeniedTransactions() {
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
    public function getEnrolledTransactions() {
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
    public function updateNeededAction($id, $isResubmit, $isConsult) {
        $sql = "UPDATE enrollment_transactions SET Can_Resubmit = :isResubmit, Need_Consultation = :isConsult WHERE Enrollment_Transaction_Id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':isResubmit', $isResubmit);
        $stmt->bindParam(':isConsult', $isConsult);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();

        if($result) {
            return['success' => true];
        }
        else {
            return['success'=> false];
        }
    }
}
?>