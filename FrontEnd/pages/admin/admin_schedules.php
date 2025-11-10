<?php
ob_start();
$pageJs = '<script type="module" src="../../assets/js/admin/admin-schedules.js"></script>';
$pageTitle = 'Admin Schedules';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-schedules.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSchedulesView.php';
require_once __DIR__ . '/../../../BackEnd/common/isAcademicYearSet.php';
$ayView  = new isAcademicYearSet();
$view = new adminSchedulesView();
?>
<div class="admin-schedules-content">
    <?php $ayView->displayError();?>
    <?php $ayView->displaySchoolYearDetails();?>
    <div class="admin-schedules-header-container">
        <div class="admin-schedules-header">
            <div class="schedule-title"> <h1> Schedules List </h1></div>
            <button class="add-sched-btn" id="get-add-sched-form"> Add Schedule </button>
        </div>
        <div class="schedules-list-container">
            <?php 
                $view->displaySchedules();
            ?>
        </div>
    </div>
</div>
<div class="modal" id="schedule-modal">
    <div class="modal-content" id="schedule-modal-content">
        
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>