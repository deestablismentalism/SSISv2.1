<?php
    declare(strict_types =1);
    require_once __DIR__ . '/./models/userEnrolleesModel.php';
class displayEnrollmentForms {
    protected $conn;
    protected $enrollee;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->enrollee = new userEnrolleesModel();
    }

    public function displaySubmittedForms() {
        try {
            if(isset($_SESSION['User']['User-Id'])) {
                $id = $_SESSION['User']['User-Id'];
    
                $data = $this->enrollee->getUserEnrollees($id);
                foreach($data as $rows) {
                    $studentMiddleInitial = !empty($rows['Student_Middle_Name']) ? substr($rows['Student_Middle_Name'], 0, 1) . "." : "";
                    echo '<tr> 
                            <td>'   .htmlspecialchars($rows['Student_Last_Name']) . ', ' 
                                    .htmlspecialchars($rows['Student_First_Name']) . ' ' 
                                    .htmlspecialchars($studentMiddleInitial) 
                                    . '</td>
                            <td class = "button"> <a class= "Check-Status" href="../user/user_enrollment_status.php?id='. $rows['Enrollee_Id'] .'"> Check Status </a></td>
                        </tr>';
                }       
            }
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

