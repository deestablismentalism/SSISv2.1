<?php
ob_start();
$pageTitle = 'Subject Archives';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-subject-archives.css">';
$pageJs = '<script src="../../assets/js/admin/admin-subject-archives.js" defer></script>';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="subject-archive-content">
    <div class="subject-archive-table-container">
        <a href="./admin_system_management.php"><img src="../../assets/imgs/arrow-left-solid.svg">
        <p>Back to System Management</p></a> 
        <?php $view->displayArchivedSubjects(); ?>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>