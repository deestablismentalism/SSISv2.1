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
</div>
<?php
    $PageContent = ob_get_clean();
    require_once __DIR__ . '/./teacher_base_designs.php';
?>