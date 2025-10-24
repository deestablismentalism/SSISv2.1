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
                        <td>' . htmlspecialchars($rows['Contact_Number']) . '</td>
                        <td>
                            <svg class="view-button" data-id="' . $rows['Enrollee_Id'] . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path d="M288 80c-65.2 0-118.8 29.6-159.9 67.7C89.6 183.5 63 226 49.4 256c13.6 30 40.2 72.5 78.6 108.3C169.2 402.4 222.8 432 288 432s118.8-29.6 159.9-67.7C486.4 328.5 513 286 526.6 256c-13.6-30-40.2-72.5-78.6-108.3C406.8 109.6 353.2 80 288 80zM95.4 112.6C142.5 68.8 207.2 32 288 32s145.5 36.8 192.6 80.6c46.8 43.5 78.1 95.4 93 131.1c3.3 7.9 3.3 16.7 0 24.6c-14.9 35.7-46.2 87.7-93 131.1C433.5 443.2 368.8 480 288 480s-145.5-36.8-192.6-80.6C48.6 356 17.3 304 2.5 268.3c-3.3-7.9-3.3-16.7 0-24.6C17.3 208 48.6 156 95.4 112.6zM288 336c44.2 0 80-35.8 80-80s-35.8-80-80-80c-.7 0-1.3 0-2 0c1.3 5.1 2 10.5 2 16c0 35.3-28.7 64-64 64c-5.5 0-10.9-.7-16-2c0 .7 0 1.3 0 2c0 44.2 35.8 80 80 80zm0-208a128 128 0 1 1 0 256 128 128 0 1 1 0-256z"/>
                            </svg>
                        </td>
                    </tr>';
            }
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }
}