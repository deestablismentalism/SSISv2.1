<?php
require_once __DIR__ . '/../../student/views/studentClassDetailsView.php';
    $view = new studentClassDetailsView();
?>
<h1> My Schedule for today</h1>
<?php $view->displayGlobalError();?>
<div class="my-class-schedule">
    <?php $view->displayStudentClassSchedule(); ?>
</div>