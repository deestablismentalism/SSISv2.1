<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/adminEnrolleesModel.php';

class adminAllEnrolleesView {
    protected $conn;
    protected $getAllEnrollees;
    protected $getAllEnrolleesCount;
    public const ENROLLED = 1;
    public const DENIED = 2;
    public const PENDING = 3;
    public const TO_FOLLOW = 4;

    public function stringEquivalent(int $value): string {
       switch($value) {
            case self::ENROLLED:
                return "enrolled";
            case self::DENIED:
                return "denied";
            case self::PENDING:
                return "pending";
            case self::TO_FOLLOW:
                return "to-follow";
            default:
                return "unknown";
        }
    }

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $enrollee = new adminEnrolleesModel();
        $this->getAllEnrollees = $enrollee->getAllEnrollees();
        $this->getAllEnrolleesCount = $enrollee->countAllEnrollees();
    }

    public function displayCount() {
        try {
            $count = $this->getAllEnrolleesCount;
            //--Comment ko muna dabid, balik mo nalang if ever kaylangan mo ---
            //  echo htmlspecialchars($count);
            echo strval($count);
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }

    public function displayAllEnrollees() {
        try {
            $data = $this->getAllEnrollees;
            foreach($data as $rows) {   
                $parentMiddleInitial = substr($rows['Middle_Name'], 0, 1) . ".";
                $hasLrn = $rows['Learner_Reference_Number'] == 0 ? 'No LRN' :  $rows['Learner_Reference_Number'] ;
                $studentMiddleInitial = substr($rows['Student_Middle_Name'], 0, 1) . ".";

                $status = $this->stringEquivalent((int)$rows['Enrollment_Status']);
                
                echo '<tr class="enrollee-row"> 
                        <td>' . $hasLrn . '</td>
                        <td>' .htmlspecialchars($rows['Student_Last_Name']) . ', ' 
                        .htmlspecialchars($rows['Student_First_Name']) . ' ' 
                        .htmlspecialchars($studentMiddleInitial) . '</td>
                        <td>' . htmlspecialchars($rows['E_Grade_Level']) . '</td>
                        <td>' . htmlspecialchars($status) . '</td>
                        <td>' . htmlspecialchars($rows['Last_Name']) . ', ' . htmlspecialchars($rows['First_Name']) . ' ' 
                               .htmlspecialchars($parentMiddleInitial) . '</td> 
                        <td>' 
                        . htmlspecialchars($rows['Contact_Number']) . 
                        '</td>
                        <td> <button class="view-button" data-id="' . $rows['Enrollee_Id'] . '"> View info</button><td>
                        </tr>';
            }
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }
} 