<?php 
ob_start();    
require_once __DIR__ . '/../session_init.php';
require_once __DIR__ . '/../../../BackEnd/user/view/userAllStudentsView.php';
$pageTitle = 'All Students';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-all-students.css">';
$pageJs = '<script src="../../assets/js/user/user-all-students.js" defer></script>';
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