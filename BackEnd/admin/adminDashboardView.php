<?php
require_once __DIR__ . '/./models/adminDashboardModel.php';

class adminDashboardView {
    protected $dashboard;

    public function __construct() {
        $this->dashboard = new adminDashboardModel();
    }

    public function displayEnrolledStudents() {
        $enrolled = $this->dashboard->EnrolledStudents();
        echo $enrolled;
    }

    public function displayPendingStudents() {
        $pending = $this->dashboard->PendingStudents();
        echo $pending;
    }

    public function displayToFollowStudents() {
        $toFollow = $this->dashboard->FollowUpStudents();
        echo $toFollow;
    }
    public function displayPendingEnrolleesInformation() {
        $pendingEnrollees = $this->dashboard->PendingEnrolleesInformation();
        
        if (!empty($pendingEnrollees)) {
            foreach ($pendingEnrollees as $rows) {
                $lrn = $rows['Learner_Reference_Number'] === 0 ? "No LRN" : $rows['Learner_Reference_Number'];
                $Student_Full_Name = $rows['Student_First_Name'] . " " . $rows['Student_Middle_Name'] . " " . $rows['Student_Last_Name'];
                echo '
                    <tr>
                        <td>' . $lrn . '</td>
                        <td>' . $Student_Full_Name . '</td>
                        <td>' . $rows['E_Grade_Level'] . '</td>
                    </tr>';
            }
        } else {

        }

    }


}

?>