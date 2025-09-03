<?php

declare(strict_types=1);
require_once __DIR__ . '/./models/teacherSectionAdvisersModel.php';
class teacherIsAnAdviserView {
    protected $sectionAdviserModel;
    protected $staffId;
    
    public function __construct() {
        $this->sectionAdviserModel = new teacherSectionAdvisersModel();
        if (isset($_SESSION['Staff']['Staff-Id'])) $this->staffId = (int)$_SESSION['Staff']['Staff-Id']; 
    }
    
    public function displayAdvisoryHyperLink() {

        $isAdviser = $this->sectionAdviserModel->checkIfAdviser($this->staffId);

        if($isAdviser) {
            echo '<div class="menu border-100sb" id="advisory">
                    <img src="../../assets/imgs/door-open.png" class="bi">
                    <span id="dashboard-spn" class="menu-title"> <a href="teacher_advisory.php?adv_id='. $isAdviser['Section_Id'] .'">Advisory Class</a></span>
                </div>';
        }
        else {
            echo '';
        }
    }
}