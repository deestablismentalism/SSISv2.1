<?php
// Start output buffering to capture the HTML as a string
ob_start();

// Optionally set page metadata
$pageTitle = "Admin Dashboard";
$currentPage = "dashboard";
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-dashboard.css">';
$pageJs = '<script type="module" src="../../assets/js/admin/admin-dashboard.js" defer></script>';
?>

<div class="bg-image">
    <div class="enrollee-data-wrapper">
    <div class="gradient-background">
        <div class="clock-container">
            <div class="time">
                <span id='Hours'> 00 </span>
                <span> : </span>
                <span id='Minutes'> 00 </span>
                <span> : </span>
                <span id='Seconds'> 00</span>
            </div>
            <div class="date">
                <span id="date"></span> 
            </div>
        </div>
    </div>
    <div class="dashboard-hyperlinks">
        <a href="admin_all_enrollees.php" class="all-hyperlinks-wrapper"> 
            <div class="all-enrollees-count">
                <div class="count-box-content">
                    <h1 class="data-link-title"> All Enrollees </h1>
                    <span id="total-enrollees" class="total-count">
                        <?php 
                        include_once __DIR__.'/../../../BackEnd/admin/view/adminDashboardView.php';
                        $dashboard = new adminDashboardView();
                        $total_enrollees = $dashboard->displayEnrolleesCount();
                        echo $total_enrollees;
                        ?>
                    </span> 
                </div>
                <img src="../../assets/imgs/all-enrollees.png" alt="Enrollees Icon" class="count-box-icon">
            </div>
        </a>
        <a href="admin_all_students.php" class="all-hyperlinks-wrapper">
            <div class="all-students-count">
                <div class="count-box-content">
                    <h1 class="data-link-title"> All Students </h1>
                    <span id="total-students" class="total-count">
                        <?php 
                            $total_students = $dashboard->displayStudentsCount();
                            echo $total_students;
                        ?>
                    </span>
                </div>
                <img src="../../assets/imgs/all-students.png" alt="Students Icon" class="count-box-icon">
            </div>
        </a>
        <a href="admin_denied_followup.php" class="all-hyperlinks-wrapper">
            <div class="all-denied-follow-up-count">
                <div class="count-box-content">
                    <h1 class="data-link-title"> Denied / Follow Up </h1>
                    <span id="total-denied-follow-up" class="total-count">
                        <?php
                            $total_denied_follow_up = $dashboard->displayDeniedAndToFollowUpCount();
                            echo $total_denied_follow_up;
                        ?>
                    </span>
                </div>
                <img src="../../assets/imgs/deniedOrDenied.png" alt="Denied Icon" class="count-box-icon">
            </div>
        </a>
    </div>
    
    <h1 class="data-title"> Enrollee data</h1>
    <div class="enrollees-by-day-container">
        <p> Submitted enrollment forms in the last: </p>
        <div class="radio-container">
            <label for="week"> <input type="radio" id="week" name="days-filter" value="7" checked> 7 days </label>
            <label for="5-days">  <input type="radio" id="5-days" name="days-filter" value="5"> 5 days </label>
            <label for="3-days"> <input type="radio" id="3-days" name="days-filter" value="3"> 3 days </label>
        </div>
        <div class="chart-loading"></div>
        <canvas id="enrollee-by-day" class="enrollee-by-day"></canvas>
    </div>
    <div class="card-container">
    <!--Enrolled-->
    <p class="chart-loading" id="enrollee-pie-chart-loading"></p>
    <div class="card card-1" id="pie-chart-container">
        <canvas id="enrollee-pie-chart" ></canvas>
    </div>
    <!--Pending Enrollees-->
    <p class="chart-loading" id="enrollee-grade-level-distribution-loading"></p>
    <div class="card card-2" id="grade-level-distribution-container">
        <canvas id="enrollee-grade-level-distribution"></canvas>
    </div>
    <!--To Follow Up-->
    <p class="chart-loading" id="enrollee-biological-sex-loading"></p>
    <div class="card card-3" id="biological-sex-container">
        <canvas id="enrollee-biological-sex"></canvas>
    </div>
    </div>

        <h1 class="data-title"> Student data</h1>
    <div class="students-data-wrapper">

            <div class="card-container">

                <p class="chart-loading" id="student-pie-chart-loading"></p>
                <div class="card card-4" id="student-pie-chart-container">
                    <canvas id="student-pie-chart"></canvas>
                </div>
                <p class="chart-loading" id="student-grade-level-distribution-loading"></p>
                <div class="card card-5" id="student-grade-level-distribution-container">
                    <canvas id="student-grade-level-distribution"></canvas>
                </div>
                <p class="chart-loading" id="student-biological-sex-loading"></p>
                <div class="card card-6" id="student-biological-sex-container">
                    <canvas id="student-biological-sex"></canvas>
                </div>
            </div>
    </div>
    </div>
    <div class="big-card-wrapper">
    <!--PENDING ENROLLMENTS BIG-->
    <div class="pending-enrollments-wrapper">
        <div class="wrapper-content">
            <div class="pending-enrollments-header">
                <h3 class="big-card-title"> Recently Submitted</h3> 
                <a class="big-card-hyperlink" href="../staff/staff_pending_enrollments.php"> View All Pending Enrollees</a>
                <hr class="custom-line">
            </div>
            <table class="pending-enrollments-table">
                <thead>
                <tr>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Level</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    include_once __DIR__ . '/../../../BackEnd/admin/view/adminDashboardView.php';
                    $dashboard = new adminDashboardView();
                    $dashboard->displayPendingEnrolleesInformation();
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="quick-actions-wrapper">
        <div class="wrapper-content">
            <div class="quick-action-card">
                <h3>Quick Actions</h3>
                <hr class="quick-action-line">
                <ul>
                    <li><a href="Admin_Subjects.php"> Add a subject</a></li>
                    <li><a href="Admin_Sections.php"> Add a section</a></li>
                    <li><a href="Admin_Schedules.php"> Create schedules</a></li>
                </ul>
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