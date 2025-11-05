<?php
declare(strict_types =1);
require_once __DIR__ . '/../controller/userStudentsController.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

class userAllStudentsView {
    protected $controller;
    protected $userId;
    
    public function __construct() {
        $this->controller = new userStudentsController();
        $this->userId = isset($_SESSION['User']['User-Id']) ? (int)$_SESSION['User']['User-Id'] : null;
    }
    public function displayAllStudents() {
        try {
            if(is_null($this->userId)) {
                throw new IdNotFoundException('User ID not found');
            }
            $studentData = $this->controller->viewUserStudents($this->userId);
            if(!$studentData['success']) {
                echo '<div class="error-message">'.htmlspecialchars($studentData['message']).'</div>';
            }
            else if($studentData['success'] && empty($studentData['data'])) {
                echo '<div class="error-message">'.htmlspecialchars($studentData['message']).'</div>';
            }
            else {
                echo '<table><tbody>';
                foreach($studentData['data'] as $rows) {
                    $firstName = htmlspecialchars($rows['Student_First_Name']);
                    $middleInitial = !empty($rows['Student_Middle_Name']) ? substr($rows['Student_Middle_Name'], 0, 1) . "." : "";
                    $lastName = htmlspecialchars($rows['Student_Last_Name']);
                    $fullName = $lastName . ',' . $firstName . ' ' . $middleInitial;
                    echo '<tr> <td class="user-student-data">' . $fullName. '</td> 
                                <td class="view-button"> <a href="./user_students_page.php?student-id='.$rows['Student_Id'].'"> Visit Student Page </a></td>
                    </tr>';
                }
                echo '</table></tbody>';   
            }
        }
        catch(IdNotFoundException $e) {
            echo '<div class="error-message">'.htmlspecialchars($e->getMessage()).'</div>';
        }
        catch(Exception $e) {
            echo '<div class="error-message">'.htmlspecialchars($e->getMessage()).'</div>';
        }   
    }
}