<?php
declare(strict_types=1);
require_once __DIR__ . '/./models/staffEnrolleesModel.php';

class staffEnrollmentStatusView {
    protected $conn;
    protected $getEnrollees;
    public const ENROLLED = 1;
    public const DENIED = 2;
    public const PENDING = 3;

    public function stringEquivalent(int $value): string {
       switch($value) {
            case self::ENROLLED:
                return "ENROLLED";
            case self::DENIED:
                return "DENIED";
            case self::PENDING:
                return "PENDING";
        }
    }
    public function __construct(){
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->getEnrollees = new staffEnrolleesModel();
    }

    public function displayCount() {
        try {
            $count = $this->getEnrollees->countEnrollees();
            echo "<h2>" . htmlspecialchars($count) . "</h2>";
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }
    public function displayEnrollees() {
    
        try {
    
            $data = $this->getEnrollees->getPendingEnrollees();
            foreach($data as $rows) {   
                $studentMiddleInitial = !empty($rows['Student_Middle_Name']) ? substr($rows['Student_Middle_Name'], 0, 1) . "." : "";
                $lrn = $rows['Learner_Reference_Number'] === 0 ? "No LRN" : $rows['Learner_Reference_Number'];
                echo '<tr class="enrollee-row"> 
                        <td>' . $lrn . '</td>
    
                        <td>' .htmlspecialchars($rows['Student_Last_Name']) . ', ' 
                        .htmlspecialchars($rows['Student_First_Name']) . ' ' 
                        .htmlspecialchars($studentMiddleInitial) . '</td>
                        <td>' . $rows['Age'] . '</td>
                        <td>' . htmlspecialchars($rows['Birth_Date']) . '</td> 
                        <td>' . htmlspecialchars($rows['Sex']) . '</td>
                        <td> <button class="view-button" data-id="' . $rows['Enrollee_Id'] . '"> <img src="../../assets/imgs/edit-white.png" loading="lazy" alt="edit"></button></td>
                        </tr>';
            }
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }
    
}
