<?php
require_once __DIR__ . '/../../student/views/studentClassDetailsView.php';
    $view = new studentClassDetailsView();
?>
<h1> My Section </h1>
<?php $view->displayGlobalError(); ?>
<div class="section-details">
    <?php 
        $view->displayStudentSectionClassmates();
    ?>
</div>