<?php
// Start output buffering to capture the HTML as a string
ob_start();

// Optionally set page metadata
$pageTitle = "Admin Dashboard";
$currentPage = "dashboard";
$pageCss = '../../assets/css/admin/admin-dashboard.css';
$pageJs = '../../assets/js/admin/admin-dashboard-json-fetcher.js';
?>
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
             <h1 class="data-link-title"> All Enrollees </h1>
             <?php 
                 include_once __DIR__.'/../../../BackEnd/admin/models/adminDashboardModel.php';
                 $dashboard = new adminDashboardModel();
                 $total_enrollees = $dashboard->TotalEnrollees();
             ?>
             <span id="total-enrollees" class="total-count"><?php echo $total_enrollees; ?></span> 
         </div>
     </a>
     <a href="admin_all_students.php" class="all-hyperlinks-wrapper">
         <div class="all-students-count">
             <h1 class="data-link-title"> All Students </h1>
             <?php 
                 $total_students = $dashboard->countTotalStudents();
             ?>
             <span id="total-students" class="total-count"><?php echo $total_students; ?></span>
         </div>
     </a>
     <a href="admin_denied_followup.php" class="all-hyperlinks-wrapper">
         <div class="all-denied-follow-up-count">
             <h1 class="data-link-title"> Denied Follow Up </h1>
             <?php
                 $total_denied_follow_up = $dashboard->TotalDeniedFollowUp();
             ?>
             <span id="total-denied-follow-up" class="total-count"><?php echo $total_denied_follow_up; ?></span>
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
    <canvas id="enrollee-by-day" class="enrollee-by-day"></canvas>
 </div>
 <div class="card-container">
 <!--Enrolled-->
 <p class="chart-loading" id="enrollee-pie-chart-loading"> Waiting for the data to load...</p>
 <div class="card card-1" id="pie-chart-container">
     <canvas id="enrollee-pie-chart" ></canvas>
 </div>
 <!--Pending Enrollees-->
 <p class="chart-loading" id="enrollee-grade-level-distribution-loading"> Waiting for the data to load...</p>
 <div class="card card-2" id="grade-level-distribution-container">
     <canvas id="enrollee-grade-level-distribution"></canvas>
 </div>
 <!--To Follow Up-->
 <p class="chart-loading" id="enrollee-biological-sex-loading"> Waiting for the data to load...</p>
 <div class="card card-3" id="biological-sex-container">
      <canvas id="enrollee-biological-sex"></canvas>
 </div>
 </div>
 <div class="students-data-wrapper">
     <h1 class="data-title"> Student data</h1>
         <div class="card-container">

             <p class="chart-loading" id="student-pie-chart-loading"> Waiting for the data to load...</p>
             <div class="card card-4" id="student-pie-chart-container">
                 <canvas id="student-pie-chart"></canvas>
             </div>
             <p class="chart-loading" id="student-grade-level-distribution-loading"> Waiting for the data to load...</p>
             <div class="card card-5" id="student-grade-level-distribution-container">
                 <canvas id="student-grade-level-distribution"></canvas>
             </div>
             <p class="chart-loading" id="student-biological-sex-loading"> Waiting for the data to load...</p>
             <div class="card card-6" id="student-biological-sex-container">
                 <canvas id="student-biological-sex"></canvas>
             </div>
         </div>
 </div>
</div>
 <div class="big-card-wrapper">
 <!--PENDING ENROLLMENTS BIG-->
 <div class="pending-enrollments-wrapper">
     <h3 class="big-card-title"><a href="../staff/staff_enrollment_pending.php"> View All Pending Enrollees</a></h3>
     <table class="pending-enrollments-table">
         <tr>
             <th>LRN</th>
             <th>Name</th>
             <th>Level</th>
         </tr>
         <?php
             include_once __DIR__ . '/../../../BackEnd/admin/adminDashboardView.php';
             $dashboard = new adminDashboardView();
             $dashboard->displayPendingEnrolleesInformation();
         ?>
     </table>
</div>
<div class="quick-actions-wrapper">
 <div class="quick-action-card">
     <h3>Quick Actions</h3>
     <ul>
         <li><a href="Admin_Subjects.php"> Add a subject</a></li>
         <li><a href="Admin_Sections.php"> Add a section</a></li>
         <li><a href="Admin_Schedules.php"> Create schedules</a></li>
     </ul>
 </div>
</div>
</div>
</div>

<?php $pageContent = ob_get_clean(); 
    require_once __DIR__ . '/admin_base_designs.php';
?>