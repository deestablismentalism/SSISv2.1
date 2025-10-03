<?php
require_once __DIR__ . '/../controller/adminUnprocessedEnrollmentsController.php';

class adminUnhandledEnrollmentsView {
    protected $conn;
    protected $transactionsController;
    private const ENROLLED = 1;
    private const DENIED = 2;
    private const FOLLOWUP = 4;
    //TODO: 
    // Create different views for Enrolled, Denied and followed up
    //api logic for issuing new enrollment forms and transferring the instance of the enrolle to a denied students table

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->transactionsController = new adminUnprocessedEnrollmentsController();
    }
    private function stringEquivalent(int $value): string {
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
    public function displayEnrolledTransactions() {
        $data = $this->transactionsController->viewMarkedEnrolledTransactions();

        if(!$data['success']) {
            echo '<div class="error-message"><span>'.htmlspecialchars($data['message']). '</span></div>';
        }
        foreach($data['data'] as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $viewSubmission = $row['Transaction_Status'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
            $studentMiddleInitial = !empty($row['Student_Middle_Name']) ? substr(htmlspecialchars($row['Student_Middle_Name']), 0, 1) . "." : "";
            $fullName = htmlspecialchars($row['Student_Last_Name']) . ', ' . htmlspecialchars($row['Student_First_Name']) . ' ' .  $studentMiddleInitial;
            $staffMiddleInitial = !empty($row['Staff_Middle_Name']) ? substr($row['Staff_Middle_Name'], 0, 1) . "." : "";
            $staffName = htmlspecialchars($row['Staff_Last_Name']) . ', ' . htmlspecialchars($row['Staff_First_Name']) . ' ' . $staffMiddleInitial;
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
    public function displayFollowUpTransactions() {
        $data = $this->transactionsController->viewMarkedFollowedUpTransactions();

        if(!$data['success']) {
            echo '<div class="error-message"><span>'.htmlspecialchars($data['message']). '</span></div>';
        }
        foreach($data['data'] as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $allowResubmissionButton = '<button>Check</button>Close<button></button>';
            $viewSubmission = $row['Transaction_Status'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
            $studentMiddleInitial = !empty($row['Student_Middle_Name']) ? substr(htmlspecialchars($row['Student_Middle_Name']), 0, 1) . "." : "";
            $fullName = htmlspecialchars($row['Student_Last_Name']) . ', ' . htmlspecialchars($row['Student_First_Name']) . ' ' .  $studentMiddleInitial;
            $staffMiddleInitial = !empty($row['Staff_Middle_Name']) ? substr($row['Staff_Middle_Name'], 0, 1) . "." : "";
            $staffName = htmlspecialchars($row['Staff_Last_Name']) . ', ' . htmlspecialchars($row['Staff_First_Name']) . ' ' . $staffMiddleInitial;
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
        $data = $this->transactionsController->viewMarkedDeniedTransactions();

        if(!$data['success']) {
            echo '<div class="error-message"><span>'.htmlspecialchars($data['message']). '</span></div>';
        }
        foreach($data['data'] as $row) {
            $lrn = !empty($row['Learner_Reference_Number']) ?  $row['Learner_Reference_Number'] :"No LRN";
            $status = $this->stringEquivalent($row['Enrollment_Status']);
            $viewSubmission = $row['Transaction_Status'] == 1 ? "<button id='" .$row['Enrollee_Id']."' data-id='" .$row['Enrollee_Id']."' class='view-resubmission'>View Resubmission</button>" : "";
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