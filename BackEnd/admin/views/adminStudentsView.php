<?php 
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminStudentsController.php';
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
class adminStudentsView {
    protected $studentsController;
    public function __construct() {
        $this->studentsController = new adminStudentsController();
    }
    public function displayStudents() {
        try {
            $response = $this->studentsController->viewStudents();

            if(!$response['success']) {
                echo '<div class="error-message"> <p>'.htmlspecialchars($response['message']). '</p></div>';
                return;
            }
            $data = $response['data'];
            foreach($data as $rows) {
                $section = $rows['Section_Name'] === null ? 'No Section yet' : htmlspecialchars($rows['Section_Name']);
                $middleInitial = $rows['Middle_Name'] === null ? '' : substr($rows['Middle_Name'], 0, 1) . ".";
                $lrn = $rows['LRN'] === null ? 'LRN is not set' : $rows['LRN'];
                $statusText = (string)$rows['Status'];
                $statusNumeric = (int)$rows['Student_Status'];
                $rowData = ' data-grade="' . htmlspecialchars($rows['Grade_Level']) . '"' .
                        ' data-status="' . $statusNumeric . '"' .
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
                        <button class="view-student" data-enrollee="'.$rows['Enrollee_Id'].'" data-student="'.$rows['Student_Id'].'"> <img src="../../assets/imgs/eye-white.svg" alt="View Student Information"></button> 
                        <button class="edit-student" data-enrollee="'.$rows['Enrollee_Id'].'" data-student="'.$rows['Student_Id'].'"> <img src="../../assets/imgs/edit-yellow-green.svg" alt="Edit Student Information"></button>
                        <button class="delete-student" data-enrollee="'.$rows['Enrollee_Id'].'" data-student="'.$rows['Student_Id'].'"> <img src="../../assets/imgs/box-archive-solid-full.svg" alt="Archive Student Information"></button>
                    </td>
                </tr>';
            }
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            return;
        }
    }
}
