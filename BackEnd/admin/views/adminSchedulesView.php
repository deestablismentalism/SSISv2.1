<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminSchedulesController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';
class adminSchedulesView {
    protected $sectionId;
    protected $schedulesController;
    protected $tableTemplate;
    //PROPERTIES
    protected $sectionName;
    protected $sectionSchedSubj;
    protected $timeTable;
    //ERROR PROPERTIES
    protected $isGlobal = false;
    protected $globalError = '';
    public function __construct() {
        $this->schedulesController = new adminSchedulesController();
        $this->tableTemplate = new TableCreator();
        $this->init();
    }
    private function init(): void {
        try {
            $this->sectionId = isset($_GET['section_id']) ? (int)$_GET['section_id'] : null;
            $this->sectionName = $this->schedulesController->viewCurrentSectionName($this->sectionId);
            $this->sectionSchedSubj = $this->schedulesController->viewSectionSubjectsAndSchedulesById($this->sectionId);
            $this->timeTable = $this->schedulesController->viewSectionSubjectTimetable($this->sectionId);
            if(!$this->sectionName['success'] || !$this->sectionSchedSubj['success']) {
                $this->isGlobal = true;
                $this->globalError = !$this->sectionName['success'] 
                    ? $this->sectionName['message'] : $this->sectionSchedSubj['message'];
                return;
            }
            if($this->timeTable['success']) {
                $this->isGlobal = true;
                $this->globalError = $this->timeTable['message'];
                return;
            }
        }
        catch(IdNotFoundException $e) {
            $this->isGlobal = true;
            $this->globalError = $e->getMessage();
            return;
        }
    }
    public function displayGlobalError(): void {
        if($this->isGlobal) {
            echo '<div class="error-message">'.htmlspecialchars($this->globalError).'</div>';
        }
    }
    public function displaySectionName(): void {
        try {
            $data = $this->sectionName;
            if($data['success'] && is_null($data['section_name'])) {
                echo '<span>'.htmlspecialchars($data['message']).'</span>';
                return;
            } else {
                echo '<span>'.htmlspecialchars($data['section_name']).'</span>';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."] " .$t ."\n",3, __DIR__ . '/../../errorLogs.txt');
            $this->isGlobal = true;
            $this->globalError = 'There was a syntax problem. Please wait while we look into it.';
        }
    }
    public function displaySectionTimetable(): void {
        try {
            $data = $this->timeTable;
            if(!$data['success']) {
                echo '<div class="error-message">'.htmlspecialchars($data['message']).'</div>';
                return;
            }
            if(empty($data['data'])) {
                echo '<div class="error-message">'.htmlspecialchars($data['message']).'</div>';
                return;
            }
            $days = ['TIME','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            echo '<table class="schedules-table">';
            echo $this->tableTemplate->returnHorizontalTitles($days, 'schedules-table-title');
            echo '<tbody>';
               foreach($data['data'] as $rows) {
                    echo '<tr>';
                    echo '<td>'.htmlspecialchars($rows['Time_Range']).'</td>';
                    foreach(array_slice($days,1) as $day) {
                        $content = $rows[$day] ?? '-'; // Use dash if no class
                        echo '<td>' . htmlspecialchars($content) . '</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table>';
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."] " .$t ."\n",3, __DIR__ . '/../../errorLogs.txt');
            $this->isGlobal = true;
            $this->globalError = 'There was a syntax problem. Please wait while we look into it.';
        }
    }
    public function displaySectionSubjectsById(): void {
        try {
            $response = $this->sectionSchedSubj;
            if (!$response['success']) {
                echo '<div class="error-message">' . htmlspecialchars($response['message']) . '</div>';
                return;
            }
            if (empty($response['data'])) {
                echo '<div class="info-message">' . htmlspecialchars($response['message']) . '</div>';
                return;
            }
            echo '<table class="section-subjects-table">';
            echo $this->tableTemplate->returnHorizontalTitles(
                ['Subject Name', 'Section Name', 'Scheduled Days','Actions'],
                'section-subjects-table-title'
            );
            foreach ($response['data'] as $subject) {
                $button = new safeHTML(
                    '<button data-sec-sub-id="'.$subject['Section_Subjects_Id'].'" class="edit-section-btn">
                     <img data-sec-sub-id="'.$subject['Section_Subjects_Id'].'" src="../../assets/imgs/edit-yellow-green.svg" alt="Edit schedule">
                    </button>'
                );
                echo $this->tableTemplate->returnHorizontalRows(
                    [$subject['Subject_Name'], $subject['Section_Name'],$subject['Scheduled_Days'],$button],
                    'section-subjects'
                );
            }
            echo '</table>';
        } 
        catch (Throwable $t) {
            error_log("[" . date('Y-m-d H:i:s') . "] " . $t . "\n", 3, __DIR__ . '/../../errorLogs.txt');
            $this->isGlobal = true;
            $this->globalError = 'There was a syntax problem. Please wait while we look into it.';
        }
    }
    public function displaySchedules() {
        try {
            $response = $this->schedulesController->viewAllSchedules();

            if(!$response['success']) {
                echo '<div class="error-message">' .htmlspecialchars($response['message']).'</div>';
            }
            
            else {
                echo '<table class="schedules-table">';
                echo $this->tableTemplate->returnHorizontalTitles([
                    'Subject Name',
                    'Section Name',
                    'Day',
                    'Time'
                ], 'schedules-table-title');
                foreach($response['data'] as $schedules) {
                    $subjectName = htmlspecialchars($schedules['Subject_Name']);
                    $sectionName = htmlspecialchars($schedules['Section_Name']);
                    $day = $schedules['Schedule_Day'];
                    $time = $schedules['Time_Start'] . '-' .  $schedules['Time_End'];

                    echo $this->tableTemplate->returnHorizontalRows([
                        $subjectName,
                        $sectionName,
                        $day,
                        $time
                    ], 'schedules');
                }
                echo '</table>';
            }
        }
        catch(Exception $e) {
            echo '<div>' .$e->getMessage().'</div>';
        }
    }
}