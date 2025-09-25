<?php 
    ob_start();
    require_once __DIR__ . '/../../../BackEnd/teacher/teacherAdvisoryView.php';
    $teacherAdvisoryView = new teacherAdvisoryView();
    $pageCss = '<link rel="stylesheet" href="../../assets/css/teacher/teacher-advisory.css">';
    $pageJs = '<script src="../../assets/js/teacher/teacher-advisory.js" defer></script>';
?>
<div class="teacher-advisory-content">
    <div class="advisory-wrapper">
        <?php
            $teacherAdvisoryView->displayAdvisoryPage();
        ?>
    </div>

    <h1> Section Subjects List</h1>
    <div class="section-subjects-wrapper">
        <?php 
            $teacherAdvisoryView->displaySectionSubjects();
        ?>
    </div>
</div>

<div class="modal" id="student-view-modal">
    <div class="modal-content" id="student-modal-content"></div>
</div>
<?php
    $pageContent = ob_get_clean();
    require_once __DIR__ . '/./teacher_base_designs.php';
?>