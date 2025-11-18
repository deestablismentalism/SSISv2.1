<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminSystemManagementController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';

class adminSystemManagementView {
    protected $controller;
    protected $tableTemplate;
    //SCHOOL YEAR DATE VALUES PROPERTY
    protected $startDate;
    protected $endDate;
    public function __construct() {
        $this->controller = new adminSystemManagementController();
        $this->tableTemplate = new tableCreator();
        $this->passSavedSchoolYearDetails();
    }
    private function passSavedSchoolYearDetails() {
        try {
            $data = $this->controller->viewSchoolYearDetailsDate();
            if(!$data['success']) {
                error_log("[".date('Y-m-d H:i:s')."]" .$data['message']."\n",3, __DIR__ . '/../../errorLogs.txt');
                return;
            }
            $this->startDate = $data['data']['Starting_Date'] ?? null;
            $this->endDate = $data['data']['Ending_Date'] ?? null;
        }
        catch(Throwable $t){
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function endYearValue():void {
        try {
            if(!is_null($this->endDate)) {
                echo 'value="'.htmlspecialchars($this->endDate).'"';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function startYearValue():void {
        try {
            if(!is_null($this->startDate)) {
                echo 'value="'.htmlspecialchars($this->startDate).'"';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function displaySchoolYearDetails() {
        try {
            $data = $this->controller->viewSchoolYearDetails();
            if(!$data['success']) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            if($data['success'] && empty($data['data'])) {
                echo '<div class="error-message">'.$data['message'].'</div>';
                return;
            }
            $schedules = $data['data'];
            $startDate = !empty($schedules['start_date']) ? htmlspecialchars($schedules['start_date']) : 'No Starting date set';
            $endDate = !empty($schedules['end_date']) ? htmlspecialchars($schedules['end_date']) : 'No Ending date set';
            echo '<span> School starts from '.$startDate.' up to '.$endDate.'</span>';
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function displayUserLoginActivity() {
        try {
            $data = $this->controller->viewPartialUserLoginActivity();
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
                echo '<thead><th>Name</th><th>Date</th><th>Time</th></thead>';
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    echo '<tr>';
                    echo '<td>'.htmlspecialchars($rows['First_Name']).'</td>';
                    echo '<td>'.htmlspecialchars($rows['readable_date']).'</td>';
                    echo '<td>'.htmlspecialchars($rows['readable_time']).'</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function displayTeacherLoginActivity() {
        try {
            $data = $this->controller->viewPartialTeacherLoginActivity();
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
                echo '<thead><th>Name</th><th>Date</th><th>Time</th></thead>';
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    echo '<tr>';
                     echo '<td>'.htmlspecialchars($rows['Staff_First_Name']).'</td>';
                    echo '<td>'.htmlspecialchars($rows['readable_date']).'</td>';
                    echo '<td>'.htmlspecialchars($rows['readable_time']).'</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function displayArchivedStudents() {
        try {
            $response = $this->controller->viewArchivedStudents();
            if(!$response['success']) {
                echo '<div class="error-message"> <p>'.htmlspecialchars($response['message']). '</p></div>';
                return;
            }
            if($response['success'] && empty($response['data'])) {
                echo '<div class="error-message"> <p>'.htmlspecialchars($response['message']). '</p></div>';
                return;
            }
            else {
                $data = $response['data'];
                foreach($data as $rows) {
                    $section = $rows['Section_Name'] === null ? 'No Section yet' : htmlspecialchars($rows['Section_Name']);
                    $middleInitial = $rows['Middle_Name'] === null ? '' : substr($rows['Middle_Name'], 0, 1) . ".";
                    $lrn = $rows['LRN'] === null ? 'LRN is not set' : $rows['LRN'];
                    $statusText = (string)$rows['Status'];
                    $rowData = ' data-grade="' . htmlspecialchars($rows['Grade_Level']) . '"' .
                            ' data-status="' . htmlspecialchars((string)$rows['Status']) . '"' .
                            ' data-section="' . htmlspecialchars($rows['Section_Name'] ?? 'Section is not set') . '"';
                    echo '<tr class="student-rows"' . $rowData . '> 
                        <td>' . htmlspecialchars($rows['Last_Name']).','. 
                        htmlspecialchars($rows['First_Name']) .' '.
                        $middleInitial .
                        '</td>
                        <td>' .$lrn. ' </td>
                        <td>' . htmlspecialchars($rows['Grade_Level']).' </td>
                        <td> ' . $section .'</td>
                        <td>' .htmlspecialchars($rows['Birthday']) .' </td>
                        <td> <span class="status status' .  $statusText . '">'  . $statusText . '</span></td>
                        <td> 
                            <button class="restore-student" data-enrollee="'.$rows['Enrollee_Id'].'" data-student="'.$rows['Student_Id'].'"> <img src="../../assets/imgs/arrow-rotate-right-solid-full.svg" alt="Restore Student"></button>
                            <button class="delete-student" data-enrollee="'.$rows['Enrollee_Id'].'" data-student="'.$rows['Student_Id'].'"> <img src="../../assets/imgs/trash-solid.svg" alt="Delete Student Information"></button>
                        </td>
                    </tr>';
                }
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
    public function displayArchivedTeachers() {
        $data = $this->controller->viewArchivedTeachers();

        if(!$data['success']) {
            echo '<div class="error-message">'. htmlspecialchars($data['message']) .'</div>';
            return;
        }
        if($data['success'] && empty($data['data'])) {
            echo '<div class="error-message">'. htmlspecialchars($data['message']) .'</div>';
            return;
        }
        else {
            echo '<div class="table-teachers-container"><table class="table-teachers">';
            echo $this->tableTemplate->returnHorizontalTitles([
                'Full Name', 'Contact Number', 'Position', 'Action'
            ], 'teachers-table-title');

            echo '<tbody>';
            foreach($data['data'] as $rows) {
                $middleName = !empty($rows['Staff_Middle_Name']) ? $rows['Staff_Middle_Name'] : '';
                $fullName = $rows['Staff_Last_Name'] . ', ' . $rows['Staff_First_Name'] . ' ' . $middleName;
                $contactNumber = $rows['Staff_Contact_Number'];
                $position = !empty($rows['Position']) ? $rows['Position'] : 'No position set';
                $actionButtons = new safeHTML('
                <button id="view-teacher">
                    <img class="view-icon" src="../../assets/imgs/eye-regular.svg" alt="View">
                </button>
                <button id="edit-teacher">
                    <img class="edit-icon" src="../../assets/imgs/edit-yellow-green.svg" alt="Edit">
                </button>
                ');
                echo $this->tableTemplate->returnHorizontalRows([
                    $fullName, $contactNumber, $position, $actionButtons 
                ], 'teachers-data');
            }
            echo '</tbody></table></div>';
        }
    }
    public function displayArchivedSubjects() {
        try {
            $data = $this->controller->viewArchivedSubjects();
            if(!$data['success']) {
                echo '<div class="error-message">'. htmlspecialchars($data['message']) .'</div>';
                return;
            }
            if($data['success'] && empty($data['data'])) {
                echo '<div class="error-message">'. htmlspecialchars($data['message']) .'</div>';
                return;
            }
            else {
                echo '<div class="table-teachers-container"><table class="table-subjects">';
                echo $this->tableTemplate->returnHorizontalTitles([
                    'Subject Name', 'Action'
                ], 'archive-subjects-table-title');
                echo '<tbody>';
                foreach($data['data'] as $row)  {
                    $subjectName =!empty($row['Subject_Name']) ?$row['Subject_Name'] : 'Subject name not found';
                    $actionButtons = new safeHTML('
                        <button id="view-teacher">
                            <img class="view-icon" src="../../assets/imgs/eye-regular.svg" alt="View">
                        </button>
                        <button id="edit-teacher">
                            <img class="edit-icon" src="../../assets/imgs/edit-yellow-green.svg" alt="Edit">
                        </button>
                    ');
                    echo $this->tableTemplate->returnHorizontalRows(
                        [ $subjectName,$actionButtons],'subjects-table-data');
                }
                echo '</tbody></table></div>';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            echo '<div class="error-message"> There was a syntax problem. Please wait while we look into it</div>';
        }
    }
}