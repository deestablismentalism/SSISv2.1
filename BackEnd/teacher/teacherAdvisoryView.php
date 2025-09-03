<?php

declare(strict_types=1);
require_once __DIR__ . '/./models/teacherSectionAdvisersModel.php';
session_start();

class teacherAdvisoryView {
    protected $conn;
    protected $sectionsModel;
    protected $id;
    protected $staffId;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->sectionsModel = new teacherSectionAdvisersModel();
        if(isset($_GET['adv_id'])) $this->id = $_GET['adv_id'];
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
            $html .= "<tr><thead><th>Student Name</th></thead></tr>";
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
}