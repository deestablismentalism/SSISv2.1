<?php 
ob_start();
require_once __DIR__ . '/../session_init.php';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollees.css">
<link rel="stylesheet" href="../../assets/css/user/user-enrollees-modal.css">
<link rel="stylesheet" href="../../assets/css/user/user-enrollment-status.css">
<link rel="stylesheet" href="../../assets/css/user/user-announcements.css">';
$pageJs = '<script src="../../assets/js/user/user-enrollees-modal.js" type="module" defer></script>
<script src="../../assets/js/announcements.js" defer></script>';
$pageTitle = 'My Enrollees';
require_once __DIR__ . '/../../../BackEnd/user/views/userEnrolleesView.php';
$enrollee = new displayEnrollmentForms();
?>
</head>
    <!--START OF THE MAIN CONTENT-->
    <div class="content" id="content">
        <!-- Announcements Section -->
        <div class="shadow-container">
            <div class="title-header">
                <p class="title">Latest Announcements</p>
            </div>
            <div class="wrapper">
                <!-- Loading State -->
                <div id="announcements-loading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p>Loading announcements...</p>
                </div>

                <!-- Empty State -->
                <div id="announcements-empty" style="display: none;">
                    <p>No announcements available at this time.</p>
                </div>

                <!-- Announcements Grid -->
                <div id="announcements-container"></div>
            </div>
        </div>

        <!-- Enrollment Forms Section -->
        <div class="shadow-container">
            <div class="title-header">
                <p class = "title"> Enrollment Forms Submitted </p> 
            </div>
            <div class="wrapper">
                <div class="table-container">
                    <?php
                    $enrollee->displaySubmittedForms();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Enrollment Form</h2>
                <span class="close-edit-modal">&times;</span>
            </div>
            <form id="editEnrollmentForm">
                <div class="form-fields">
                    <!-- Form fields will be dynamically generated here -->
                </div>
                <!-- Hidden fields for address names -->
                <input type="hidden" id="region-name" name="region_name">
                <input type="hidden" id="province-name" name="province_name">
                <input type="hidden" id="city-municipality-name" name="city_municipality_name">
                <input type="hidden" id="barangay-name" name="barangay_name">
                <div class="form-actions">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit">Update Enrollment</button>
                </div>
            </form>
        </div>
    </div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
