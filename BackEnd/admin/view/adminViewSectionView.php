<?php 

declare(strict_types=1);
require_once __DIR__ . '/../controller/adminSectionsController.php';
require_once __DIR__ . '/../controller/adminViewSectionController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';

class adminViewSectionView {

    protected $conn;
    protected $sectionsController;
    protected $viewSectionController;
    protected $tableTemplate;
    protected $id;

    public function __construct() {
        $db = new Connect();
        $this->tableTemplate = new TableCreator();
        $this->conn = $db->getConnection();
        $this->sectionsController = new adminSectionsController();
        $this->viewSectionController = new adminViewSectionController();
        if(isset($_GET['section_id'])) $this->id = (int)$_GET['section_id'];
    }

    public function displaySectionStudents() {
        try {
            $response = $this->sectionsController->viewSectionDetails($this->id);

            if(!$response['success']) {
                echo '<table><tr class="error-message">
                        <td>'.$response['message'].'</td>
                    </tr></table>';
            }
            else {
                $maleData = $response['data']['male'];
                $femaleData = $response['data']['female'];
                if(!$maleData['success']) {
                    echo '<td>' .htmlspecialchars($maleData['message']).'</td>';
                }
                if(!$femaleData['success']) {
                    echo '<td>'.htmlspecialchars($femaleData['message']).'</td>';
                }
                $maleCount   =  count($maleData['students']);
                $femaleCount = count($femaleData['students']);
                $higherIndex = max($maleCount, $femaleCount);
                if($higherIndex > 0) {
                    echo '<table class="students-list">';
                    echo "<tr><thead><th>Male</th><th>Female</th></thead></tr>";
                    echo '<tbody>';
                    for ($i = 0; $i < $higherIndex; $i++) {
                        echo '<tr>';
                        echo $this->renderCell($maleData['students'][$i] ?? null, $i);
                        echo $this->renderCell($femaleData['students'][$i] ?? null, $i);
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                }
            }
        }
        catch(Exception $e) {
            echo '<table><tr class="error-message">
                <td>'.$e->getMessage().'</td>
            </tr></table>';
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
            $sectionName = $this->sectionsController->viewSectionName($this->id);

            if(!$sectionName['success']) {
                echo $sectionName['message'];
            }
            else {
                echo htmlspecialchars($sectionName['data']['Grade_Level']) . ' - '.htmlspecialchars($sectionName['data']['Section_Name']);
            }
        }
        catch(Exception  $e) {
            echo 'Something went wrong. Please try again Later.';
        }
    }
    public function displayAdviserName() {
        try {
            $adviserName = $this->sectionsController->viewAdviserName($this->id);

            if(!$adviserName['success']) {
                echo $adviserName['message'];
            }
            else {
                echo htmlspecialchars($adviserName['data']['Staff_Last_Name']) . ', '. htmlspecialchars($adviserName['data']['Staff_First_Name']) . ' '. htmlspecialchars($adviserName['data']['Staff_Middle_Name']);
            }

        }
        catch(Exception  $e) {
            echo 'Something went wrong. Please try again Later.';
        }
    }
    public function displaySubjectDetails() {
        try {
            $subjectDetails = $this->viewSectionController->viewSectionSubjectDetails($this->id);

            if(!$subjectDetails['success']) {
                echo $subjectDetails['message'];
            }
            else {
                echo '<table class="subject-details-table">';
                $this->tableTemplate->returnHorizontalTitles([
                    'Subject Name',
                    'Scheduled Day',
                    'Scheduled Time'
                ],
                'subject-details-head');
                echo '<tbody>';
                foreach($subjectDetails['data'] as $subjects) {
                    $subjectName = !empty($subjects['Subject_Name']) ? $subjects['Subject_Name'] : 'No subject name';
                    $scheduleDay = !empty($subjects['Schedule_Day']) ? $subjects['Schedule_Day'] : 'No scheduled day yet';
                    $timeStart = (!empty($subjects['Time_Start']) && !empty($subjects['Time_End'])) ? 
                    $subjects['Time_Start'] . '-' . $subjects['Time_End']  : 'No time set yet';

                    $this->tableTemplate->returnHorizontalTitles([
                        $subjectName, $scheduleDay, $timeStart
                    ],'subject-details-body');
                }
                echo '</tbody>';
                echo '</table>';
            }
        }
        catch(Exception $e) {
            echo 'There was an unexpected problem. Please try again later.';
        }
    }
 }