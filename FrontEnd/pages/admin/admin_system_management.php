<?php
ob_start();
$pageTitle = 'System Management';
$pageJs = '<script src="../../assets/js/admin/admin-system-management.js" defer></script>';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-system-management.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="system-management-content">
    <!-- SCHOOL YEAR DEETS -->
    <h3>Academic Year Details</h3>
    <div class="school-year-details">
        <div id="view-mode" class="view-mode-container">
                <div class="view-mode-content">
                    <div class="dates">
                        <?php $view->displaySchoolYearDetails();?>
                    </div>
                    <div class="button-actions">
                        <button id="edit-btn">Edit Dates</button>
                    </div>
                </div>
        </div>
        <div id="edit-mode" class="edit-mode-container" style="display:none;">
            <form id="school-year-details-form">
                <input type="date" name="school-year-start">
                <input type="date" name="school-year-end">
                <div class="actions">
                    <button type="button" id="cancel-btn">Cancel</button>
                    <button type="submit" id="save-btn">Save</button>
                </div>
            </form> 
        </div>
    </div>
    <!-- ARCHIVES  -->
    <h1> Archived Information</h1>
    <div class="archives">
        <div class="student-archives">
            <a href="./admin_student_archives.php">View Student archives</a>
        </div>
        <div class="teacher-archives">
            <a href="./admin_teacher_archives.php">View Teacher archives</a>
        </div>
    </div>
    <!-- LOGIN  -->
    <h1>Login Activity</h1>
    <div class="login-activity">
        <div class="users-login-activity">
            <h1>Recent User logins</h1>
            <div class="ul-table-container">
                <?php $view->displayUserLoginActivity();?>
            </div>
        </div>
        <div class="teachers-login-activity">
            <h1>Recent teacher logins</h1>
             <div class="tl-table-container">
                <?php $view->displayTeacherLoginActivity();?>
             </div>
        </div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>