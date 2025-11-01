<?php
$pageTitle = 'Enrollment Status';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-status.css">';
$pageJs = '<script src="../../assets/js/user/user-enrollment-status.js" defer></script>';

ob_start();
?>
<div class="main-wrapper">
    <h1 class="title">Enrollment Status</h1>
    <div class="status-info" id="status-info">
        <?php 
            include_once __DIR__ . '/../../../BackEnd/user/view/userEnrollmentStatusView.php';
            $status = new displayEnrollmentStatus();
            $status->displayStatus(); 
        ?>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Edit Enrollment Information</h2>
        <h1>Note: You can only resubmit the form once</h1>
        <form id="editEnrollmentForm">
            <input type="hidden" name="region_name" id="region-name">
            <input type="hidden" name="province_name" id="province-name">
            <input type="hidden" name="city_municipality_name" id="city-municipality-name">
            <input type="hidden" name="barangay_name" id="barangay-name">
            <div class="form-fields"></div>
            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="cancel-btn">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
include './user_base_designs.php';
?>