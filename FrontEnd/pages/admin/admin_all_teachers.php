<?php
require_once __DIR__ . '/../session_init.php';
require_once __DIR__ . '/../../../BackEnd/common/userTypeView.php';
require_once __DIR__ . '/../../assets/components.php';
$component = new components();

$pageTitle = "All Teachers";

$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-modal-shared.css">
            <link rel="stylesheet" href="../../assets/css/admin/admin-all-teachers.css">';
$pageJs = '<script type="module" src="../../assets/js/admin/admin-all-teachers.js" defer></script>';

ob_start();
?>
    <div class="admin-all-teachers-content">
        <div class="admin-all-teachers-title-wrapper">
             <h1 class="all-teachers-title">All Teachers </h1>
             <div class="button-group">
                 <button class="btn btn-primary register" id="register-admin-btn">Register Admin</button>
                 <button class="btn btn-primary register" id="register-teacher-btn">Register Teacher</button>
             </div>
        </div>
        <?php
            require_once __DIR__ . '/../../../BackEnd/admin/views/adminTeachersView.php';
            $table = new adminTeachersView();
            $table->displayAllTeachers();
        ?>
    </div>
<?php
echo $component->modalComponent('all-teachers-modal', 'all-teachers-modal-content');
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>