<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();
require_once __DIR__ . '/../../../BackEnd/teacher/views/teacherDashboardView.php';
$view = new teacherDashboardView();
$pageTitle = 'Dashboard';
$pageCss = '<link rel="stylesheet" href="../../assets/css/teacher/teacher-dashboard.css">';
$pageJs = '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script><script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script><script src="../../assets/js/teacher/teacher-dashboard.js" defer></script>';
?>
<div class="teacher-dashboard-content">
    <div class="dashboard-wrapper">
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
        <div class="dashboard-header">
            <h1 class="dashboard-title">Teacher Dashboard</h1>
            <p class="dashboard-subtitle">Welcome back! Here's an overview of your activities.</p>
        </div>


        <!-- Charts Section -->
        <h2 class="data-title">Student & Enrollee Data</h2>
        <div class="card-container">
            <!-- Chart 1: Students Biological Sex Distribution -->
            <p class="chart-loading" id="students-bio-sex-loading"></p>
            <div class="card card-1" id="students-bio-sex-container">
                <canvas id="students-bio-sex-chart"></canvas>
            </div>

            <!-- Chart 2: Enrollees Grade Level Distribution -->
            <p class="chart-loading" id="enrollees-grade-level-loading"></p>
            <div class="card card-2" id="enrollees-grade-level-container">
                <canvas id="enrollees-grade-level-chart"></canvas>
            </div>

            <!-- Chart 3: Enrollees Biological Sex Distribution -->
            <p class="chart-loading" id="enrollees-bio-sex-loading"></p>
            <div class="card card-3" id="enrollees-bio-sex-container">
                <canvas id="enrollees-bio-sex-chart"></canvas>
            </div>
        </div> 
       <div class="dashboard-stats-container">
            <?php $view->displayDashboardStats(); ?>
        </div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./teacher_base_designs.php';
?>