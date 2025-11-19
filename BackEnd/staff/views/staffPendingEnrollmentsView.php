<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/staffEnrollmentController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';

class staffPendingEnrollmentsView {
    protected $controller;
    private $tableTemplate;
    private $staffId;
    public function __construct(){
        $this->controller = new staffEnrollmentController();
        $this->tableTemplate = new TableCreator();
        $this->staffId = isset($_SESSION['Staff']['Staff-Id']) ? (int)$_SESSION['Staff']['Staff-Id']  : null;
    }
    public function displayPendingEnrollees():void {
        try {
            $data = $this->controller->viewPendingEnrollees($this->staffId);
            if(!$data['success']) {
                echo '<div class="error-message">'.htmlspecialchars($data['message']).'</div>';
            }
            else {
                echo '<table class="enrollments">';
                echo '<thead>';
                echo '<tr class="pending-enrollees">';
                echo '<th>LRN</th>';
                echo '<th class="sortable" data-column="1" data-order="none">
                        <div class="th-content">
                            <span>Student Name</span>
                            <span class="sort-arrows">
                                <span class="arrow-up">▲</span>
                                <span class="arrow-down">▼</span>
                            </span>
                        </div>
                      </th>';
                echo '<th>Age</th>';
                echo '<th class="sortable" data-column="3" data-order="none">
                        <div class="th-content">
                            <span>Grade Level</span>
                            <span class="sort-arrows">
                                <span class="arrow-up">▲</span>
                                <span class="arrow-down">▼</span>
                            </span>
                        </div>
                      </th>';
                echo '<th>Biological Sex</th>';
                echo '<th>Display Enrollment Information</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody id="query-table">';
                foreach($data['data'] as $rows) {
                    $middleName = !is_null($rows['Student_Middle_Name']) ? $rows['Student_Middle_Name'] : '';
                    $fullName = $rows['Student_Last_Name'] . ', ' .$rows['Student_First_Name'].' '.$middleName;
                    $age = !is_null($rows['Age']) ? $rows['Age'] : 'No Age available';
                    $sex = !empty($rows['Sex']) ? $rows['Sex'] : 'No Biological Sex provided';
                    $lrn = !is_null($rows['Learner_Reference_Number']) ? $rows['Learner_Reference_Number'] : 'No LRN';
                    $gradeLevel = !empty($rows['Grade_Level']) ? $rows['Grade_Level'] : 'N/A';
                    $button = new safeHTML('<button class="view-button" data-id="' . $rows['Enrollee_Id'] . '"> <img src="../../assets/imgs/edit-white.png" loading="lazy" alt="edit"></button>');
                    echo $this->tableTemplate->returnHorizontalRows([
                        $lrn, $fullName, $age, $gradeLevel, $sex, $button
                    ]);
                }
                echo '</tbody></table>';
            }
        }
        catch(IdNotFoundException $e) {
            echo '<div class="error-message">'.htmlspecialchars($e->getMessage()).'</div>';
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."] " . $t . "\n",3, __DIR__ . '/../../errorLogs.txt');
            echo '<div class="error-message"> There was a syntax problem. Please wait while we look into it</div>';
        }
        catch(Exception $e) {
            echo '<div class="error-message">'.htmlspecialchars($e->getMessage()).'</div>';
        }

    }
}