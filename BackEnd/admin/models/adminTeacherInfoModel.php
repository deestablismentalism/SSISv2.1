<?php
    require_once __DIR__ . '/../../core/dbconnection.php';

    class adminTeacherInformationModel {
        protected $conn;
        protected $result;
    
        //automatically run and connect database
        public function __construct() {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }

        public function getAllResults($ID) {
            $sql_select_all_teachers = "SELECT *, 
                                        s.Staff_Address_Id AS staff_address_id,
                                        sa.Staff_Address_Id AS staff_address_staff_address_id,
                                        s.Staff_Identifier_Id AS staff_identifier_id,
                                        si.Staff_Identifier_Id AS staff_identifier_staff_identifier_id
                                        FROM staffs s
                                        LEFT JOIN staff_address sa ON s.Staff_Address_Id = sa.Staff_Address_Id
                                        LEFT JOIN staff_Identifiers si ON s.Staff_Identifier_Id = si.Staff_Identifier_Id
                                        WHERE s.Staff_Id = :Staff_Id";
            $select_all_teachers = $this->conn->prepare($sql_select_all_teachers);
            $select_all_teachers->bindparam(':Staff_Id', $ID);
            if ($select_all_teachers->execute()) {
                $this->result = $select_all_teachers->fetch(PDO::FETCH_ASSOC);
                return $this->result;
            } else {
                return false;
            }
        }
    }
    
?>