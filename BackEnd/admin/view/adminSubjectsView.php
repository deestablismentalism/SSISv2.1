<?php
declare(strict_types=1);
require_once __DIR__ . '/../controller/adminSubjectsController.php';
require_once __DIR__ . '/../../common/getGradeLevels.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';

class adminSubjectsView {
    protected $subjectsController;
    protected $getGradeLevels;
    protected $tableTemplate;

    public function __construct() {
        $this->subjectsController = new adminSubjectsController();
        $this->getGradeLevels = new getGradeLevels();
        $this->tableTemplate = new TableCreator();
    }

    public function displaySubjects() {
        try {
            $response = $this->subjectsController->viewSubjectsPerSection();

            if(!$response['success']) {
                echo '<div>' .htmlspecialchars($response['message']).'</div>';
            }
            else {
                foreach($response['data'] as $rows) {
                    $firstName = !empty($rows['Staff_First_Name']) ? htmlspecialchars($rows['Staff_First_Name']) : '';
                    $lastName = !empty($rows['Staff_Last_Name']) ? htmlspecialchars($rows['Staff_Last_Name']) : '';
                    $middleName = !empty($rows['Staff_Middle_Name']) ? htmlspecialchars($rows['Staff_Middle_Name']) : '';
                    
                    $fullName = (!empty($firstName) && !empty($lastName)) ? 
                    htmlspecialchars($rows['Staff_Last_Name']) . ', ' . htmlspecialchars($rows['Staff_First_Name']) . ' ' . htmlspecialchars($rows['Staff_Middle_Name']) : 'No Teacher Assigned Yet';

                    echo '<tr> 
                                <td>'. htmlspecialchars($rows['Subject_Name']) .'</td>
                                <td>'. htmlspecialchars($rows['Section_Name']) .'</td>
                                <td>'. htmlspecialchars($rows['Grade_Level']) .'</td>
                                <td>'. $fullName.'</td>
                                <td> <button data-id="'.$rows['Section_Subjects_Id'].'" class="assign-teacher"> <img src="../../assets/imgs/edit-white.png" loading="lazy" alt="edit"></button></td>
                        </tr>';
                }
            }
        }
        catch(Exception $e) {
            $this->tableTemplate->generateHorizontalRows('exception-content', [$e->getMessage()]);
        }
    }
}
