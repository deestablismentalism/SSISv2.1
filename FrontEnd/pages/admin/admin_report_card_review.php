<?php
ob_start();
$pageTitle = "Report Card Review";
$pageJs = '<script type="module" src="../../assets/js/admin/admin-report-card-review.js" defer></script>';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-report-card-review.css">';
require_once __DIR__ . '/../../../BackEnd/admin/views/reportCardReviewView.php';
$view = new reportCardReviewView();
?>
<!--START OF THE MAIN CONTENT-->

<div class="report-card-review-content">
    <div class="header-left">
        <h2 id="reviewTitle">Report Card Submissions</h2>
    </div>
    <div class="table">
        <?php 
            $view->displayAllSubmissions();
        ?>
    </div>
    <div id="submissionModal" class="modal">
        <div class="modal-content" id="submission-modal-content">
            <span class="close">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>
</div>
<?php 
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>

