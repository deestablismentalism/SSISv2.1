<?php 
ob_start();
$pageTitle = 'Students Page';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-students-page.css">';
$pageJs = '<script type="module" src="../../assets/js/user/user-students-page.js" defer></script>';
require_once __DIR__ . '/../../../BackEnd/student/views/studentClassDetailsView.php';
$view = new studentClassDetailsView();
?>
<div class="user-students-page-content">
    <div class="back-button">
        <button> <img src="../../assets/imgs/arrow-left-solid.svg"> Back to students list page</button>
    </div>
    <div class="user-students-page-header">
    <div class="student-details">
    <?php 
        $view->displayStudentSimpleDetails();
    ?>
    </div>
        <nav class="student-navigation-list-wrapper">
            <ul class="student-navigation-list">
                <li><label for="sched"> <input type="radio" id="sched" name="student-content" value="schedules" checked> My Schedules </label></li>
                <li><label for="sec">  <input type="radio" id="sec" name="student-content" value="section"> My Section </label></li>
                <li><label for="grades"> <input type="radio" id="grades" name="student-content" value="grades"> My Grades </label></li> 
            </ul>
        </nav>
    </div>

    <div class="student-dynamic-container" id="student-dynamic-modal">
        <div class="student-dynamic-content" id="student-dynamic-modal-content"></div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
