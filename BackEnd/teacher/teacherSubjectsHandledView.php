<?php
declare(strict_types=1);
require_once __DIR__ . '/./models/teacherSubjectsModel.php';
require_once __DIR__ . '/../core/tableDataTemplate.php';

class teacherSubjectsHandledView {
    protected $staffId;
    protected $subjectsModel;
    protected $tableTemplate;
    public function __construct() {
        if(isset($_SESSION['Staff']['Staff-Id'])) $this->staffId = $_SESSION['Staff']['Staff-Id'];
        $this->subjectsModel = new teacherSubjectsModel(); 
        $this->tableTemplate = new TableCreator();
    }

    public function displaySubjects() { 
        try {
            $subjectData = $this->subjectsModel->getTeacherSubjectsHandled($this->staffId);

            if($subjectData === false) {
                throw new Exception('There was an error in fetching the subjects. Try Again later');
            }
            if(empty($subjectData)) {
                echo '<table clas="no-value">';
                echo '<tr><td> No subjects assigned yet. </td></tr>';
                echo '</table>';
            }
            else {
                echo '<table class="subjects-list">';
                foreach($subjectData as $rows) {
                    $this->tableTemplate->generateHorizontalTitles('subject-titles', ['Subject Name', 'Day', 'Time', 'Section Name']);
                    echo '<tr><td>'.$rows['Subject_Name'].'</td></tr>';
                }
                echo '</table>';
            }
        }
        catch(Exception $e) {
            echo '<table class="exception-table">';
            echo '<tr><td>'.$e->getMessage().'</td></tr>';
            echo '</table>';
        }

    }
}