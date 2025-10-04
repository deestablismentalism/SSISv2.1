<?php
declare(strict_types=1);
require_once __DIR__ . '/../controller/teacherGradesController.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';

class teacherGradesView {
    protected $controller;
    protected $staffId;
    protected $tableTemplate;

    public function __construct() {
        if(isset($_SESSION['Staff']['Staff-Id'])) $this->staffId = (int) $_SESSION['Staff']['Staff-Id'];
        $this->controller = new teacherGradesController();
        $this->tableTemplate = new TableCreator();
    }

    public function displaySubjectsToGrade() {
        try {
            if(empty($this->staffId)) {
                throw new IdNotFoundException('Staff ID not found',0);
            }
            $data = $this->controller->viewSubjectsToGrade($this->staffId);
            if(!$data['success']) {
                echo '<div class="error-message">'.htmlspecialchars($data['message']). '</div>';
            }
            else {
                echo '<table class="subjects-to-grade">';
                $this->tableTemplate->generateHorizontalTitles('subjects-to-grade-header', [
                    'Subject Name', 'Section Name', 'Students Count', 'Display Students'
                ]);
                echo '<tbody>';
                foreach($data['data'] as $rows) {
                    $subjectName = !empty($rows['Subject_Name']) ? $rows['Subject_Name'] : 'No Subject name';
                    $sectionName = !empty($rows['Section_Name']) ? $rows['Section_Name'] : 'No section name';
                    $button = new safeHTML('<button> Grade </button>');
                    $this->tableTemplate->generateHorizontalRows('subjects-to-grade-data', [
                        $subjectName, $sectionName, $rows['Student_Count'], $button
                    ]);
                }
                echo '</tbody></table>';
            }
        }
        catch(IdNotFoundException $e) {
            echo '<div class="error-message">'. $e->getMessage(). '</div>';
        }
        catch(Exception $e) {
            echo '<div class="error-message">'. $e->getMessage(). '</div>';
        }
    }
}