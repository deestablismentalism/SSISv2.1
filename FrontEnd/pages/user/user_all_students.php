<?php 
ob_start();    
require_once __DIR__ . '/../session_init.php';
require_once __DIR__ . '/../../../BackEnd/user/views/userAllStudentsView.php';
$pageTitle = 'Submitted Forms';
$pageCss = '<link rel="stylesheet" href="../../assets/user/user-all-students.css">';
?>
<div class="user-all-enrolled-content">
    <div class="user-all-enrolled-wrapper">
        <p class="user-all-enrolled-title">  All Enrolled </p>
        <div class="user-all-enrolled-table-wrapper">
            <?php 
                $data = new userAllStudentsView();
                $data->displayAllStudents();
            ?>
        </div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>