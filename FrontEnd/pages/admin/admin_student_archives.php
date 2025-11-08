<?php
ob_start();
$pageTitle = 'Students Archives';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-student-archives.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="students-archive-content">
    <div class="students-archive-table-container">
        <a href="./admin_system_management.php"><img src="../../assets/imgs/arrow-left-solid.svg"></a> 
        <table class="students-archive-table">
            <thead> 
                <tr>
                    <th>Student Name</th>
                    <th>Student LRN</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                    <th>Student Birthdate</th>
                    <th>Student Status</th>
                    <th>Student Actions</th>
                </tr>
                </thead>
                <?php $view->displayArchivedStudents(); ?>
        </table>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>