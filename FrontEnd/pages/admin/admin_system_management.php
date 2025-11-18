<?php
ob_start();
$pageTitle = 'System Management';
$pageJs = '<script src="../../assets/js/admin/admin-system-management.js" defer></script>';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-system-management.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSystemManagementView.php';
$view = new adminSystemManagementView();
?>
<div class="system-management-content">
    <h3>System Management Overview</h3>
    
    <!-- Two Column Grid Layout -->
    <div class="system-management-grid">
        
        <!-- LEFT COLUMN -->
        <div class="left-column">
            <!-- SCHOOL YEAR DETAILS -->
            <div class="section-wrapper">
                <h2 class="section-title">Academic Year Details</h2>
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
                            <input type="date" name="school-year-start" <?php $view->startYearValue(); ?>>
                            <input type="date" name="school-year-end" <?php $view->endYearValue();?>>
                            <div class="actions">
                                <button type="button" id="cancel-btn">Cancel</button>
                                <button type="submit" id="save-btn">Save</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>

            <!-- USER LOGIN ACTIVITY -->
            <div class="section-wrapper">
                <div class="users-login-activity">
                    <h2 class="section-title">Recent User Logins</h2>
                    <div class="ul-table-container">
                        <?php $view->displayUserLoginActivity();?>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-column">
            <!-- ARCHIVES -->
            <div class="section-wrapper">
                <h2 class="section-title">Archived Information</h2>
                <div class="archives">
                    <div class="student-archives">
                        <a href="./admin_student_archives.php">View Student Archives</a>
                    </div>
                </div>
            </div>

            <!-- TEACHER LOGIN ACTIVITY -->
            <div class="section-wrapper">
                <div class="teachers-login-activity">
                    <h2 class="section-title">Recent Teacher Logins</h2>
                    <div class="tl-table-container">
                        <?php $view->displayTeacherLoginActivity();?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/admin_base_designs.php';
?>