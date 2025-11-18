<?php
ob_start();
$pageTitle = 'Section Archives';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-section-archives.css">';
$pageJs = '<script src="../../assets/js/admin/admin-section-archives.js" defer></script>';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="section-archive-content">
    <div class="section-archive-table-container">
        <a href="./admin_system_management.php"><img src="../../assets/imgs/arrow-left-solid.svg">
        <p>Back to System Management</p></a> 
        <?php $view->displayArchivedSections(); ?>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>