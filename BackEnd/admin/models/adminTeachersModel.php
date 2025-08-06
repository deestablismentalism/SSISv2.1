<?php
    require_once __DIR__ . '/../../core/dbconnection.php';
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
    }
?> 