<?php
ob_start();
$pageTitle = 'Teacher Archives';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-teacher-archives.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="teacher-archive-content">
    <div class="teacher-archive-table-container">
        <a href="./admin_system_management.php"><img src="../../assets/imgs/arrow-left-solid.svg">
        <p>Back to System Management</p></a> 
        <?php $view->displayArchivedSubjects(); ?>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>