<?php 
    require_once __DIR__ . '/../../teacher/teacherStudentInformationView.php';

    $view = new teacherStudentInformationView();
?>

<span class="close"> &times; </span>

<h1> Impormasyon ng Estudyante</h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayStudentInformation(); ?>
    </tbody>
</table>
<h1> Address ng Estudyante </h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayAddress(); ?>
    </tbody>
</table>
<h1> Magulang ng Estudyante </h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayStudentParents(); ?>
    </tbody>
</table>