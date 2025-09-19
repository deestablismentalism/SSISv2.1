<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../BackEnd/common/userTypeView.php';

if (!isset($_SESSION['Staff']['User-Id']) || $_SESSION['Staff']['Staff-Type'] != 1) {
    header("Location: ../../Login.php");
    exit();
}

$pageTitle = "All Teachers";

$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-teachers.css">';

ob_start();
?>
    <div class="table-wrapper">
        <p class="all-teachers-title">All Teachers</p>
        <table class="table-teachers">
            <?php
                require_once __DIR__ . '/../../../BackEnd/admin/adminTeachersView.php';
                $table = new adminTeachersView();
                $table->displayAllTeachers();
            ?>
        </table>

        <?php if ($_SESSION['Staff']['Staff-Type'] == 1): ?>
            <a href="./admin_staff_registration.php" class="btn btn-primary register">
                Register a New Teacher
            </a>
        <?php endif; ?>
    </div>
<?php
$pageContent = ob_get_clean();

include_once __DIR__ . '/./admin_base_designs.php';
