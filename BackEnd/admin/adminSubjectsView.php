<?php
declare(strict_types=1);
require_once __DIR__ . '/./models/adminSubjectsModel.php';
require_once __DIR__ . '/../../common/getGradeLevels.php';

class adminSubjectsView {
    protected $subjectsModel;
    protected $getGradeLevels;
    public function __construct() {
        $this->subjectsModel = new adminSubjectsModel();
        $this->getGradeLevels = new getGradeLevels();
    }

    public function displaySubjects() {
        $data = $this->subjectsModel->getSubjectsPerGradeLevel();

        foreach($data as $rows) {
            echo '<tr> 
                    <td>' .htmlspecialchars($rows['Subject_Name']).'</td>
                    <td>' .htmlspecialchars($rows['Grade_Level']).'</td>
            </tr>';
        }
    }
}
