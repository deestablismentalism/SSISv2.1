<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/studentClassController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';

class studentClassDetailsView {
    protected $studentId;
    protected $controller;
    protected $tableTemplate;
    //INIT ERROR
    protected $isGlobalError =false;
    protected $globalError = '';
    //VIEWS PROPERTIES
    protected $studentClassSchedule;
    protected $studentGrades;
    protected $studentSectionDetails;
    public function __construct() {
        $this->tableTemplate = new TableCreator();
        $this->studentId = isset($_GET['student-id']) ?(int)$_GET['student-id'] : null;
        $this->controller = new studentClassController();
        $this->init();
    }
    private function init() {
        try {
            $this->studentClassSchedule = $this->controller->viewClassSchedule($this->studentId);
            $this->studentGrades = $this->controller->viewStudentGrades($this->studentId);
            $this->studentSectionDetails = $this->controller->viewStudentSectionClassmates($this->studentId);
        }
        catch(IdNotFoundException $e) {
            $this->isGlobaleError = true;
            $this->globalError = $e->getMessage();
            return;
        }
    }
    public function displayGlobalError():void {
        if($this->isGlobalError) {
            echo '<div class="error-message">'.$this->globalError.'</div>';
        }
    }
    public function displayStudentSimpleDetails(){
        try {
            $data = $this->controller->viewThisStudentSimpleDetails($this->studentId);
            if(!$data['success']) {
                echo '<span>'.$data['message'].'</span>';
                return;
            }
            if($data['success'] && empty($data)) {
                echo '<span>'. $data['message'].'</span>';
                return;
            }
            $lrn = isset($data['data']['LRN']) ? $data['data']['LRN'] : 'No LRN';
            $firstName = $data['data']['First_Name'] ?? 'No First name';
            $lastName = $data['data']['Last_Name'] ?? 'No Last name';
            $middleName = $data['data']['Middle_Name'] ?? '';
            $studentName = $lastName . ', '.$firstName. ' '. $middleName;

            echo '<span> Student Name: '.$studentName.'</span><br>';
            echo '<span> Learner Referenece Number: '.$lrn.'</span>';
        }
        catch(IdNotFoundException $e) {
            echo '<span>'.$e->getMessage().'</span>';
        }
        catch(Throwable $t) {
            echo '<div class="error-message">There was a syntax problem. Please wait for it to be fixed</div>';
        }
    }
    public function displayStudentClassSchedule():void {
        try {
            $data = $this->studentClassSchedule;
            if(!$data['success']) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            if($data['success'] && empty($data['data'])) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            else {
                echo '<table>';
                $days = ['TIME','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                echo $this->tableTemplate->returnHorizontalTitles($days,'student-sched-header');
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    $timeSlot = $rows['Start_Time'] . ' - ' . $rows['End_Time'];
                    echo '<tr>';
                    echo '<td>'.htmlspecialchars($timeSlot).'</td>';
                    foreach(array_slice($days,1) as $day) {
                        $content = $rows[$day] ?? '-'; // Use dash if no class
                        echo '<td>' . htmlspecialchars($content) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }
        }
        catch(Throwable $t) {
            echo '<div class="error-message">There was a syntax problem. Please wait for it to be fixed</div>';
        }
    }
    public function displayStudentGrades():void {
        try {
            $data = $this->studentGrades;
            if(!$data['success']) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            if($data['success'] && empty($data['data'])) {
                echo '<div class="error-message">'.$data['message'].'<div>';
                return;
            }
            else {
                echo '<table class="student-grades">';
                echo $this->tableTemplate->returnHorizontalTitles([
                    'Subject Name', '1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'
                ],'student-grades-header');
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    $subjectName = !empty($rows['Subject_Name']) ? $rows['Subject_Name'] : 'No Subject name';
                    $first = !is_null($rows['Q1']) ? (float)$rows['Q1'] : 0.00;
                    $second = !is_null($rows['Q2']) ? (float)$rows['Q2'] : 0.00;
                    $third = !is_null($rows['Q3']) ? (float)$rows['Q3'] : 0.00;
                    $fourth =  !is_null($rows['Q4']) ? (float)$rows['Q4'] : 0.00;
                    echo $this->tableTemplate->returnHorizontalRows([
                        $subjectName, $first,$second,$third,$fourth
                    ],'student-grades-data');
                }
                echo '</tbody></table>';
            }
        }
        catch(Throwable $t) {
            echo '<div class="error-message"> There was a syntax problem. Please wait for it to be fixed</div>';
        }
    }
    public function displayStudentSectionClassmates():void {
        try {
            $data = $this->studentSectionDetails;
            if(!$data['success']) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            if($data['success'] && empty($data['data'])) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            else {
                echo '<table>';
                echo $this->tableTemplate->returnHorizontalTitles([$data['section_name']],'classmates-header');
                    // It's an array of students
                    foreach($data['data'] as $rows) {
                        $lastName = !empty($rows['Last_Name']) ? $rows['Last_Name'] : 'No Last name';
                        $firstName = !empty($rows['First_Name']) ? $rows['First_Name'] : 'No First name';
                        $middleName = isset($rows['Middle_Name']) && !empty($rows['Middle_Name']) ? $rows['Middle_Name'] : '';
                        $fullName = $lastName . ', '.$firstName . ' ' . $middleName;
                        echo $this->tableTemplate->returnHorizontalRows([$fullName],'classmates-body');
                    }
            }
        }
        catch(Throwable $t) {
            echo '<div class="error-message"> There was a syntax problem. Please wait for it to be fixed</div>';
            return;
        }
    }
}