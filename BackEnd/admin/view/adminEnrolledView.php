<?php
declare(strict_types=1);
require_once __DIR__ . '/./models/adminEnrolleesModel.php';

class adminEnrolledView {
    protected $conn;
    protected $getEnrolled;
    protected $getEnrolledCount;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $enrollee = new adminEnrolleesModel();
        $this->getEnrolled = $enrollee->getEnrolled();
        $this->getEnrolledCount = $enrollee->countEnrolled();
    }

    public function displayCount() {
        try {
            $count = $this->getEnrolledCount;
            echo "<h2>" . htmlspecialchars($count) . "</h2>";
        }
        catch(PDOException $e) {
            die("Query Failed: " . $e->getMessage());
        }
    }

    public function displayEnrolled() {
        try {
    
            $data = $this->getEnrolled;
            foreach($data as $rows) {   
                $parentMiddleInitial = substr($rows['Middle_Name'], 0, 1) . ".";
                $studentMiddleInitial = substr($rows['Student_Middle_Name'], 0, 1) . ".";
                $lrn = $rows['Learner_Reference_Number'] === 0 ? "No LRN" : $rows['Learner_Reference_Number'];
                echo '<tr class="enrollee-row"> 
                        <td>' . $lrn . '</td>
    
                        <td>' .htmlspecialchars($rows['Student_Last_Name']) . ', ' 
                        .htmlspecialchars($rows['Student_First_Name']) . ' ' 
                        .htmlspecialchars($studentMiddleInitial) . '</td>
                        <td>' . htmlspecialchars($rows['Last_Grade_Level']) . '</td>
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