<?php 

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';

class searchEnrollee {
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
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->getEnrollees = new adminEnrolleesModel();
    }

    public function displaySearchResults() {

        if (isset($_POST['query'])) {
            $query = $_POST['query'];

            $data = $this->getEnrollees->searchEnrollees($query);

            if ($data) {
                foreach ($data as $rows) {
                    $studentMiddleInitial = !empty($rows['Student_Middle_Name']) ? substr($rows['Student_Middle_Name'], 0, 1) . "." : "";
                    $lrn = $rows['Learner_Reference_Number'] === 0 ? "No LRN" : $rows['Learner_Reference_Number'];
                    echo '<tr class="enrollee-row"> 
                            <td>' . $lrn . '</td>
                            <td>' . htmlspecialchars($rows['Student_Last_Name']) . ', ' 
                            . htmlspecialchars($rows['Student_First_Name']) . ' ' 
                            . htmlspecialchars($studentMiddleInitial) . '</td>
                            <td>' . $rows['Age']. '</td>
                            <td>' . htmlspecialchars($rows['Birth_Date']) . '</td>
                            <td>' .htmlspecialchars($rows['Sex']) . '</td> 
                            <td> <button class="view-button" data-id="' . $rows['Enrollee_Id'] .'"> View Info </button> </td>
                        </tr>';
                }
            } else {
                echo '<tr><td colspan="5">No results found</td></tr>';
            }
        }
    }
}
