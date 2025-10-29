<?php
declare(strict_types=1);
require_once __DIR__ . '/../controller/adminTeacherController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';
    
class adminTeachersView {
    protected $controller;
    protected $tableTemplate;


    public function __construct() {
        $this->controller = new adminTeacherController();
        $this->tableTemplate = new TableCreator();
    }

    public function displayAllTeachers() {
        $data = $this->controller->viewAllTeachers();

        if(!$data['success']) {
            echo '<div class="error-message">'. htmlspecialchars($data['message']) .'</div>';
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
}
