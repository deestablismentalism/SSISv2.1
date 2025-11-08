<?php
require_once __DIR__ . '/../controllers/adminDashboardController.php';

class adminDashboardView {
    protected $controller;

    public function __construct() {
        $this->controller = new adminDashboardController();
    }

    public function displayEnrolleesCount() {
        $response = $this->controller->viewEnrolleesCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }

        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }

    public function displayStudentsCount() {
        $response = $this->controller->viewStudentsCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }

        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }

    public function displayDeniedAndToFollowUpCount() {
        $response = $this->controller->viewDeniedAndFollowedUpCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }
        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }
    public function displayPendingEnrolleesInformation() {
            $response = $this->controller->viewPendingEnrollees();        
            if(!$response['success']) {
                echo '<span>' .htmlspecialchars($response['message']). '</span>';
            }
            $data = $response['data'];

            foreach($data as $row) {
                $firstName = htmlspecialchars($row['Student_First_Name']);
                $lastName = htmlspecialchars($row['Student_Last_Name']);
                $middleName = !empty($row['Student_Middle_Name']) ? htmlspecialchars($row['Student_Middle_Name']) : '';
                $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

                echo '<tr> 
                    <td>'.$row['Learner_Reference_Number'].'</td>
                    <td>'. $fullName. '</td>
                    <td>'.htmlspecialchars($row['E_Grade_Level']).'</td>
                </tr>';
            }
    }
}

?>