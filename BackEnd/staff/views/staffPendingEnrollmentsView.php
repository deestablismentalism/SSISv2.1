<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/staffEnrollmentController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';

class staffPendingEnrollmentsView {
    protected $controller;
    private $tableTemplate;
    public function __construct(){
        $this->controller = new staffEnrollmentController();
        $this->tableTemplate = new TableCreator();
    }
    public function displayPendingEnrollees():void {
        try {
            $data = $this->controller->viewPendingEnrollees();
            if(!$data['success']) {
                echo '<div class="error-message">'.htmlspecialchats($data['message']).'</div>';
            }
            else {
                echo '<table class="enrollments">';
                echo $this->tableTemplate->returnHorizontalTitles([
                    'LRN','Student Name','Age', 'Birth Date', 'Biological Sex', 'Display Enrollment Information'
                ],'pending-enrollees');
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    $middleName = !is_null($rows['Student_Middle_Name']) ? $rows['Student_Middle_Name'] : '';
                    $fullName = $rows['Student_Last_Name'] . ', ' .$rows['Student_First_Name'].' '.$middleName;
                    $lrn = !is_null($rows['Learner_Reference_Number']) ? $rows['Learner_Reference_Number'] : 'No LRN';
                    $button = new safeHTML('<button class="view-button" data-id="'.$rows['Enrollee_Id'].'">Display Info</button>');
                    echo $this->tableTemplate->returnHorizontalRows([
                        $lrn, $fullName,$rows['Age'],$rows['Birth_Date'],$rows['Sex'],$button
                    ]);
                }
                echo '</tbody></table>';
            }
        }
        catch(Exception $e) {
            echo '<div class="error-message">'.htmlspecialchars($e->getMessage()).'</div>';
        }

    }
}