<?php

declare(strict_types=1);
require_once __DIR__ . '/./models/teacherSectionAdvisersModel.php';
require_once __DIR__ . '/./models/teacherSubjectsModel.php';
require_once __DIR__ . '/../core/tableDataTemplate.php';
session_start();

class teacherAdvisoryView {
    protected $conn;
    protected $sectionsModel;
    protected $subjectsModel;
    protected $id;
    protected $staffId;
    protected $tableTemplate;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->sectionsModel = new teacherSectionAdvisersModel();
        $this->subjectsModel = new teacherSubjectsModel();
        $this->tableTemplate = new TableCreator();
        if(isset($_GET['adv_id'])) $this->id = $_GET['adv_id']; //this is the section id
        if(isset($_SESSION['Staff']['Staff-Id'])) $this->staffId = $_SESSION['Staff']['Staff-Id'];
    }

    public function displayAdvisoryPage() {
        $adviser = $this->sectionsModel->checkIfAdvisory($this->staffId, $this->id);
        if(!$adviser) {
            echo '<div class="advisory-content"> 
                    <p> Unauthorized access. No data to display</p>
                </div>';
        }
        else {
            echo ' <div class="advisory-content">
                        <div class="advisory-button-wrapper">
                        <a href="masterlist.php?section='.$adviser['Section_Id'].'" target="_blank"> Generate Masterlist</a>
                        </div>
                        <div class="advisory-name">
                            <h1>'. $this->returnSectionName() .'</h1>
                        </div>

                        <div class="students-list-wrapper">
                            '. $this->returnAdvisoryStudents() .'
                        </div>
                    </div>';
        }

    }
    public function returnAdvisoryStudents() {
        $students = $this->sectionsModel->getSectionStudents($this->id);
        $html = '';

        if($students) {
            $html = '<table class="students-list">';
            $html .= $this->tableTemplate->returnHorizontalTitles('students-title', ['Student Name']);
            $html .= '<tbody>';
            foreach($students as $index =>$student) {
                $lastName =  htmlspecialchars($student['Last_Name'] ?? '');
                $firstName = htmlspecialchars($student['First_Name'] ?? '');
                $middleName =  htmlspecialchars($student['Middle_Name'] ?? '');
        
                $fullName = '<span>'. ($index+1) . '. </span>' . $lastName .', '. $firstName .' '. $middleName;
        
                $isNotNullName = (!empty($lastName) || !empty($firstName)) ? $fullName : '';
                $html .= '<tr>';
                $html .= '<td>'. $isNotNullName .' </td> <td class="view-student-button-wrapper"><button class="view-student-button" data-id="'. $student['Student_Id'] .'">View Information</button></td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }
        else {
            $html = '<div class="no-students-container">';
            $html .= '<h1>No Students yet. </h1>';
            $html .= '</div>';
        }
        return $html;
    }
    public function returnSectionName() {
        try {
            $sectionName = $this->sectionsModel->getSectionName($this->id);

            if (!empty($sectionName)) {
                return htmlspecialchars($sectionName['Grade_Level']) . ' - '.htmlspecialchars($sectionName['Section_Name']);
            }
            else {
                return 'No Section name';
            }
        }
        catch(Exception  $e) {
            return 'Something went wrong. Please try again Later.';
        }
    }

    public function displaySectionSubjects() {
        try {
            $sectionSubjectsData = $this->subjectsModel->getSectionSubjects($this->id);

            if($sectionSubjectsData === false) {
                throw new Exception('There was an error in fetching the subjects list. Try again Later');
            }
            else if(empty($sectionSubjectsData)) {
                echo '<table class="empty-subjects-list">';
                $this->tableTemplate->generateHorizontalRows('empty-content', ['No Subjects found']);
                echo '</table>';
            }
            else {
                echo '<table class="section-subjects-list">';
                foreach($sectionSubjectsData as $rows) {
                    
                    $this->tableTemplate->generateHorizontalTitles('subjects-header', ['Subject Name', 'Day', 'Time',]);
                    $this->tableTemplate->generateHorizontalRows('subjects-list', [htmlspecialchars($rows['Subject_Name']),
                                                                                   htmlspecialchars($rows['Schedule_Day']),
                                                                                   htmlspecialchars($rows['Time_Start']) .'-'. htmlspecialchars($rows['Time_End'])]);
                }
                echo '</table>';
            }
        }
        catch(Exception $e) {
            echo '<table class="exception-row">';
            $this->tableTemplate->generateHorizontalRows('exception-content', [$e->getMessage()]);
            echo '</table>';
        }
    }
}