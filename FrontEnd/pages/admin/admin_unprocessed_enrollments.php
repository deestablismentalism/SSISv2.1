<?php
ob_start();
$pageTitle = "Admin Unhandled Enrollments";
$pageJs = '<script src="../../assets/js/admin/admin-unhandled-enrollments.js" defer></script>';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-unprocessed-enrollments.css">';
    require_once __DIR__ . '/../../../BackEnd/admin/view/adminUnhandledEnrollmentsView.php';
    $view = new adminUnhandledEnrollmentsView();
?>
    <!--START OF THE MAIN CONTENT-->


<div class="denied-followup-content">
    <div class="header-left">
        <h2  id="enrolledTitle" style="background: #54cc25ff;"> Enrolled </h2>
    </div>
    <div class="table">
        <table class="enrollments">
            <thead style="background-color: #ffffffff; border-bottom: 2px solid #3fa6d2e5; font-weight: 900;">
                <th>LRN</th>
                <th>Full Name</th>
                <th>Handled By</th>
                <th>Transaction Code</th>
                <th>Enrollment Status</th>
                <th>Date Handled</th>
                <th>Remarks</th>
            </thead>
            <tbody>
                <?php 
                    $view->displayEnrolledTransactions();
                ?>
            </tbody>
        </table>
    </div>

    <div class="header-left">
        <h2  id="enrolledTitle" style="background: #f4bb36ff;"> Followed Up </h2>
    </div>
    <div class="table">
        <table class="enrollments">
            <thead style="background-color: #ffffffff; border-bottom: 2px solid #3fa6d2e5; font-weight: 900;">
                <th>LRN</th>
                <th>Full Name</th>
                <th>Handled By</th>
                <th>Transaction Code</th>
                <th>Enrollment Status</th>
                <th>Handled At</th>
                <th>Remarks</th>
            </thead>
            <tbody>
                <?php 
                    $view->displayFollowUpTransactions();
                ?>
            </tbody>
        </table>
    </div>
    <div class="header-left">

        <h2 id="enrolledTitle" style="background: #f44336;"> Denied </h2>
    </div>
    <div class="table">
        <table class="enrollments">
            <thead style="background-color: #ffffffff; border-bottom: 2px solid #3fa6d2e5; font-weight: 900;">
                <th>LRN</th>
                <th>Full Name</th>
                <th>Handled By</th>
                <th>Transaction Code</th>
                <th>Enrollment Status</th>
                <th>Handled At</th>
                <th>Remarks</th>
            </thead>
            <tbody>
                <?php 
                    $view->displayDeniedTransactions();
                ?>
            </tbody>
        </table>
    </div>
    <div id="enrolleeModal" class="modal">
        <div class="modal-content" id="enrollee-modal-content"></div>
    </div>
    <div id="reasonModal" class="modal">
        <div class="modal-content">
            <h3>Enrollee Reasons</h3>
            <div id="reason-modal-content"></div>
        </div>
    </div>
</div>
<?php 
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>
