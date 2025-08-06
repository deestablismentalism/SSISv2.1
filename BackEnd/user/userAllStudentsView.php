<?php

declare(strict_types =1);

require_once __DIR__ . '/./models/userStudentsModel.php';

class userAllStudentsView {
    protected $studentsModel;
    
    public function __construct() {
        $this->studentsModel = new userStudentsModel();
    }
    public function displayAllStudents() {

        if(isset($_SESSION['User']['User-Id'])) {
            $userId = $_SESSION['User']['User-Id'];
            $studentData = $this->studentsModel->getUserStudents($userId);

            foreach($studentData as $rows) {
                $firstName = htmlspecialchars($rows['Student_First_Name']);
                $middleInitial = !empty($rows['Student_Middle_Name']) ? substr($rows['Student_Middle_Name'], 0, 1) . "." : "";
                $lastName = htmlspecialchars($rows['Student_Last_Name']);
                $fullName = $lastName . ',' . $firstName . ' ' . $middleInitial;
                echo '<tr> <td class="user-student-data">' . $fullName. '</td> 
                            <td class="view-button"> <a href="./user_students_page.php?id='.$rows['Student_Id'].'"> Visit Student Page </a> </td>
                </tr>';

                echo `<script> console.log('.  $userId .')</script>`;
            }

        }

        
    }
}