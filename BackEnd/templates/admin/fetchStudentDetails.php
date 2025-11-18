<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/views/adminStudentInfo.php';
$view = new adminStudentInfo();
?>
<div class="student-info-modal">
    <?php $view->displayGlobalError(); ?>
    <h1>Student Personal Information</h1>
    <?php $view->displayStudentInfo();?>
    <h1>Student's Parents Information</h1>
    <?php $view->displayParentInfo();?><br>
</div>