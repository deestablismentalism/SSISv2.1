<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminSchedulesController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';

class adminSchedulesView {
    protected $schedulesController;
    protected $tableTemplate;
    public function __construct() {
        $this->schedulesController = new adminSchedulesController();
        $this->tableTemplate = new TableCreator();
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