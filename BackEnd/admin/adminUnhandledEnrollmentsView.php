<?php
require_once __DIR__ . '/./models/adminEnrollmentTransactionsModel.php';

class adminUnhandledEnrollmentsView {
    protected $conn;
    protected $Model;
    private const ENROLLED = 1;
    private const DENIED = 2;
    private const FOLLOWUP = 4;
    //TODO: 
    // Create different views for Enrolled, Denied and followed up
    //api logic for issuing new enrollment forms and transferring the instance of the enrolle to a denied students table


    public function stringEquivalent(int $value): string {
        switch($value) {
            case self::ENROLLED:
                return "enrolled";
            case self::DENIED:
                return "denied";
            case self::FOLLOWUP:
                return "to-follow";
            default:
                return "Unknown";
        }
    }
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->Model = new adminEnrollmentTransactionsModel();
    }

    public function displayFollowUpTransactions() {
        $data = $this->Model->getFollowedUpTransactions();
        foreach($data as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $allowResubmissionButton = '<button>Check</button>Close<button></button>';
            $viewSubmission = $row['Is_Resubmitted'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
            $studentMiddleInitial = !empty($row['Student_Middle_Name']) ? substr($row['Student_Middle_Name'], 0, 1) . "." : "";
            $fullName = $row['Student_Last_Name'] . ', ' . $row['Student_First_Name'] . ' ' .  $studentMiddleInitial;
            $staffMiddleInitial = !empty($row['Staff_Middle_Name']) ? substr($row['Staff_Middle_Name'], 0, 1) . "." : "";
            $staffName = $row['Staff_Last_Name'] . ', ' . $row['Staff_First_Name'] . ' ' . $staffMiddleInitial;
            echo '<tr class="denied-followup-row">
                <td>' . $lrn . '</td>
                <td>' . $fullName . '</td>
                <td>' .$staffName.'</td>
                <td>' .$row['Transaction_Code'].'</td>
                <td>' .$status.'</td>
                <td>' .$row['Date'].'</td>
                <td> ' .$viewSubmission. ' <button id="' .$row['Enrollee_Id'].'" data-id="' .$row['Enrollee_Id'].'" class="view-reason">View Remarks</button> </td>
            </tr>';
        }
    }
    public function displayEnrolledTransactions() {
        $data = $this->Model->getEnrolledTransactions();
        foreach($data as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $viewSubmission = $row['Is_Resubmitted'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
            $acceptEnrollmentButton = '<button>Check</button>Close<button></button>';
            $studentMiddleInitial = !empty($row['Student_Middle_Name']) ? substr($row['Student_Middle_Name'], 0, 1) . "." : "";
            $fullName = $row['Student_Last_Name'] . ', ' . $row['Student_First_Name'] . ' ' .  $studentMiddleInitial;
            $staffMiddleInitial = !empty($row['Staff_Middle_Name']) ? substr($row['Staff_Middle_Name'], 0, 1) . "." : "";
            $staffName = $row['Staff_Last_Name'] . ', ' . $row['Staff_First_Name'] . ' ' . $staffMiddleInitial;
            echo '<tr class="denied-followup-row">
                <td>' . $lrn . '</td>
                <td>' . $fullName . '</td>
                <td>' .$staffName.'</td>
                <td>' .$row['Transaction_Code'].'</td>
                <td>' .$status.'</td>
                <td>' .$row['Date'].'</td>
                <td> ' .$viewSubmission. ' <button id="' .$row['Enrollee_Id'].'" data-id="' .$row['Enrollee_Id'].'" class="view-reason">View Remarks</button> </td>
            </tr>';
        }
    }
    public function displayDeniedTransactions() {
        $data = $this->Model->getDeniedTransactions();
        foreach($data as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $viewSubmission = $row['Is_Resubmitted'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
            $studentMiddleInitial = !empty($row['Student_Middle_Name']) ? substr($row['Student_Middle_Name'], 0, 1) . "." : "";
            $fullName = $row['Student_Last_Name'] . ', ' . $row['Student_First_Name'] . ' ' .  $studentMiddleInitial;
            $staffMiddleInitial = !empty($row['Staff_Middle_Name']) ? substr($row['Staff_Middle_Name'], 0, 1) . "." : "";
            $staffName = $row['Staff_Last_Name'] . ', ' . $row['Staff_First_Name'] . ' ' . $staffMiddleInitial;
            echo '<tr class="denied-followup-row">
                <td>' . $lrn . '</td>
                <td>' . $fullName . '</td>
                <td>' .$staffName.'</td>
                <td>' .$row['Transaction_Code'].'</td>
                <td>' .$status.'</td>
                <td>' .$row['Date'].'</td>
                <td> ' .$viewSubmission. ' <button id="' .$row['Enrollee_Id'].'" data-id="' .$row['Enrollee_Id'].'" class="view-reason">View Remarks</button> </td>
            </tr>';
        }
    }
}

?>