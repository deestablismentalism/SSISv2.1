<?php 

declare(strict_types=1);
require_once __DIR__ . '/./models/adminSectionsModel.php';

class adminViewSectionView {

    protected $conn;
    protected $sectionsModel;
    protected $id;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->sectionsModel = new adminSectionsModel();
        if(isset($_GET['section_id'])) $this->id = $_GET['section_id'];
    }

    public function displaySectionStudents() {
        
        $maleData = $this->sectionsModel->getSectionMaleStudents($this->id);
        $femaleData = $this->sectionsModel->getSectionFemaleStudents($this->id);
        $maleCount = count($maleData);
        $femaleCount = count($femaleData);
        $higherIndex = max($maleCount, $femaleCount);
        if($higherIndex > 0) {
            echo '<table class="students-list">';
            echo "<tr><thead><th>Male</th><th>Female</th></thead></tr>";
            echo '<tbody>';
            $rows = [];
            for ($i = 0; $i < $higherIndex; $i++) {
                echo '<tr>';
                echo $this->renderCell($maleData[$i] ?? null, $i);
                echo $this->renderCell($femaleData[$i] ?? null, $i);
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        else {
            echo '<div class="no-students-container">';
            echo '<h1>No Students yet. </h1>';
            echo '<a> Add students to this section? </a>';
            echo '</div>';
        }
    }
    private function renderCell($student, $index) {
        $lastName =  htmlspecialchars($student['Student_Last_Name'] ?? '');
        $firstName = htmlspecialchars($student['Student_First_Name'] ?? '');
        $middleName =  htmlspecialchars($student['Student_Middle_Name'] ?? '');

        $fullName = '<span>'. ($index+1) . '. </span>' . $lastName .', '. $firstName .' '. $middleName;

        $isNotNullName = (!empty($lastName) || !empty($firstName)) ? $fullName : '';
        echo '<td>' 
                 . $isNotNullName .
            '</td>';
    }
    public function displaySectionName() {
        try {
            $sectionName = $this->sectionsModel->getSectionName($this->id);

            if (!empty($sectionName)) {
                echo htmlspecialchars($sectionName['Grade_Level']) . ' - '.htmlspecialchars($sectionName['Section_Name']);
            }
            else {
                echo 'No Section name';
            }
        }
        catch(Exception  $e) {
            echo 'Something went wrong. Please try again Later.';
        }
    }
    public function displayAdviserName() {
        try {
            $adviserName = $this->sectionsModel->getSectionAdviserName($this->id);

            if(!empty($adviserName)) {
                echo htmlspecialchars($adviserName['Staff_Last_Name']) . ', '. htmlspecialchars($adviserName['Staff_First_Name']) . ' '. htmlspecialchars($adviserName['Staff_Middle_Name']);
            }
            else {
                echo 'No Adviser set';
            }
        }
        catch(Exception  $e) {
            echo 'Something went wrong. Please try again Later.';
        }
    }
}