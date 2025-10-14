<?php 
ob_start();
require_once __DIR__ . '/../session_init.php';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollees.css">';
$pageJs = '<script src="../../assets/js/user/user-enrollees.js"defer></script>';
$pageTitle = 'Home';
require_once __DIR__ . '/../../../BackEnd/user/view/userEnrolleesView.php';
$enrollee = new displayEnrollmentForms();
?>
</head>
    <!--START OF THE MAIN CONTENT-->
    <div class="content" id="content">
        <div class="shadow-container">
            <div class="wrapper">
                <p class = "title"> Enrollment Forms Submitted </p> <br>
                <div class="table-container">
                    <?php
                    $enrollee->displaySubmittedForms();
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
