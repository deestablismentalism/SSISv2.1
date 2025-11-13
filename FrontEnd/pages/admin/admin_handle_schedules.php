<?php
ob_start();
require_once __DIR__ . '/../../../BackEnd/admin/views/adminSchedulesView.php';
require_once __DIR__ . '/../../../BackEnd/common/isAcademicYearSet.php';
$pageJs = '<script type="module" src="../../assets/js/admin/admin-handle-schedules.js" defer></script>';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-modal-shared.css">
            <link rel="stylesheet" href="../../assets/css/admin/admin-handle-schedules.css">';
$view = new adminSchedulesView();
$isSet = new isAcademicYearSet();
?>
<div class="admin-handle-section-schedule-content">
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isSet = <?= json_encode($isSet->isSet()); ?>;
        if (!isSet) {
            const btn = document.querySelectorAll('.edit-section-btn');
            if (btn) {
                btn.forEach(button=>{
                    button.disabled = true;
                    button.style.opacity = '0.5';
                    button.title = 'Disabled until school year is set';
                }) 
            }
        }
    });
    </script>
    <?php $isSet->displayError();?>
    <?php $isSet->displaySchoolYearDetails();?>
    <div class="section-schedule-title-wrapper">
        <h1 class="page-title">Scheduling for <?php $view->displaySectionName();?></h1>
    </div>
    <div class="section-subjects">
        <?php $view->displaySectionSubjectsById();?>
    </div>
    <div class="section-timetable">
        <div class="section-timetable-title-wrapper">
            <h1> <?php $view->displaySectionName(); ?> Timetable</h1>
            <div class="section-time-table-wrapper">
                <?php $view->displaySectionTimetable(); ?>
            </div>
        </div>
    </div>
    <div id="add-sched-form"class="modal">
        <form id="weekly-sched-form">
            <div id="add-sched-content" class="modal-content"></div>
        </form>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>