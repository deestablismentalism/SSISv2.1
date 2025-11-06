<?php 
ob_start();    
require_once __DIR__ . '/../session_init.php';
require_once __DIR__ . '/../../../BackEnd/user/views/userAllStudentsView.php';
$pageTitle = 'Submitted Forms';
$pageCss = '<link rel="stylesheet" href="../../assets/user/user-all-students.css">';
?>
<div class="user-all-enrolled-content">
    <div class="table-container">
        <?php 
            $data = new userAllStudentsView();
            $data->displayAllStudents();
        ?>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>