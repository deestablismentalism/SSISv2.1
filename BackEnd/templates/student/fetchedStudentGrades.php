<?php
require_once __DIR__ . '/../../student/views/studentClassDetailsView.php';
$view = new studentClassDetailsView();
?>
<h1> My Grades </h1>
<?php $view->displayGlobalError();?>
<div class="student-grades">
    <?php 
        $view->displayStudentGrades();
    ?>
</div>