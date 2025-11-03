<?php 
    require_once __DIR__ . '/../../teacher/view/teacherStudentInformationView.php';

    $view = new teacherStudentInformationView();
?>

<span class="close"> &times; </span>

<h1> Impormasyon ng Estudyante</h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayStudentInformation(); ?>
    </tbody>
</table>
<h1> Mga Grades ng Estudyante </h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayStudentGrades(); ?>
    </tbody>
</table>