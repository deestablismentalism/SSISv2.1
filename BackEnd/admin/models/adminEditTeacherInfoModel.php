<?php
    require_once __DIR__ . '/../../core/dbconnection.php';
    session_start();

    class adminEditInformation {
        protected $conn;
        //automatically run and connect database
        public function __construct() {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }

        public function editTeacherInformation($statusInput, $Position, $Staff_Id) {
            $Status = null;
            switch ($statusInput) {
                case 'Active':
                    $Status = 1;
                    break;
                case 'Retired':
                    $Status = 2;
                    break;
                case 'Transferred Out':
                    $Status = 3;
                    break;
                default:
                    return [
                        'success' => false,
                        'message' => 'Invalid status provided.',
                    ]; 
            }

            $sql_update_information = "UPDATE staffs SET
                                    Staff_Status = :Status,
                                    Position = :Position
                                    WHERE Staff_Id = :Staff_Id";
            $update_information = $this->conn->prepare($sql_update_information);
            $update_information->bindParam(':Status', $Status);
            $update_information->bindParam(':Position', $Position);
            $update_information->bindParam(':Staff_Id', $Staff_Id);

            try {
                if ($update_information->execute()) {
                    return [
                        'success' => true,
                        'message' => 'Teacher Information Updated Successfully!',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Failed to update the information.',
                    ];
                }
            } catch (PDOException $e) {
                return [
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage(),
                ];
            }
        }
    }
    
?>