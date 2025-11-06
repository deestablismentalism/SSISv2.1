<?php
ob_start();
$pageTitle = 'Teacher Archives';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="teacher-archive-content">
    <a href="./admin_system_management.php"><img src="../../assets/imgs/arrow-left-solid.svg"></a> 
    <div class="teacher-archive-table-container">
       <?php $view->displayArchivedTeachers(); ?>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>