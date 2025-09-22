<?php 

declare(strict_types=1);

require_once __DIR__ . '/../controller/adminStudentsController.php';

class adminStudentsView {
    protected $studentsController;

    public function __construct() {
        $this->studentsController = new adminStudentsController();
    }

    public function displayStudents() {
        $response = $this->studentsController->viewStudents();

        if(!$response['success']) {
            echo '<div class="error-message"> <p>'.htmlspecialchars($response['message']). '</p></div>';
        }
        $data = $response['data'];
        foreach($data as $rows) {
            $section = $rows['Section_Name'] === null ? 'Section is not set' : htmlspecialchars($rows['Section_Name']);
            $middleInitial = $rows['Student_Middle_Name'] === null ? '' : substr($rows['Student_Middle_Name'], 0, 1) . ".";
            $lrn = $rows['Learner_Reference_Number'] === 0 ? 'LRN is not set' : $rows['Learner_Reference_Number'];
            
            // Convert status number to text
            $statusText = '';
            switch($rows['Student_Status']) {
                case 1:
                    $statusText = 'Active';
                    break;
                case 2:
                    $statusText = 'Inactive';
                    break;
                case 3:
                    $statusText = 'Dropped';
                    break;
                default:
                    $statusText = 'Unknown';
            }

            echo '<tr class="student-rows"> 
                <td>' . htmlspecialchars($rows['Student_Last_Name']).','. 
                htmlspecialchars($rows['Student_First_Name']) .' '.
                $middleInitial .
                '</td>
                <td>' .$lrn. ' </td>
                <td>' . htmlspecialchars($rows['Grade_Level']).' </td>
                <td> ' . $section .'</td>
                <td>' .htmlspecialchars($rows['Student_Email']) .' </td>
                <td>' . $statusText . '</td>
                <td> 
                    <button class="view-student" data-id="'.$rows['Enrollee_Id'].'"> <img src="../../assets/imgs/eye-regular.svg" alt="View Student Information"></button> 
                    <button class="edit-student" data-id="'.$rows['Enrollee_Id'].'"> <img src="../../assets/imgs/edit.svg" alt="Edit Student Information"></button>
                    <button class="delete-student" data-id="'.$rows['Enrollee_Id'].'"> <img src="../../assets/imgs/trash-solid.svg" alt="Delete Student Information"></button>
                </td>
            </tr>';
        }
    }
}
