<?php
// Get current page filename for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Map page filenames to menu IDs
$page_menu_map = [
    'admin_dashboard.php' => 'dashboard',
    'admin_all_students.php' => 'students',
    'admin_edit_student.php' => 'students',
    'admin_all_teachers.php' => 'teachers',
    'admin_teacher_info.php' => 'teachers',
    'admin_all_enrollees.php' => 'enrolls',
    'admin_unprocessed_enrollments.php' => 'enrolls',
    'admin_grade_levels.php' => 'grade-levels',
    'admin_view_section.php' => 'grade-levels',
    'admin_student_per_section.php' => 'grade-levels',
    'admin_subjects.php' => 'subjects',
    'admin_locker.php' => 'locker',
    'admin_system_management.php' => 'sysmanagement',
    'admin_announcements.php' => 'announcements',
];

$active_menu = isset($page_menu_map[$current_page]) ? $page_menu_map[$current_page] : '';
?>
<div class="sidebar active">
            <div class="sidebar-wrapper">
                <div class="sidebar-title" id="sidebar-button-wrapper">
                    <span class="SSIS">SSIS</span>
                    <button class="menu-btn"><img src="../../assets/imgs/bar.svg" id="sidebar-toggle-button"></button>
                </div>

                <div class="menu-wrappper" id="sidebar-menu-wrapper">
                    <!--DASHBOARD-->
                    <nav>
                        <div class="menu border-100sb <?php echo $active_menu === 'dashboard' ? 'active' : ''; ?>" id="dashboard" data-page="dashboard">
                            <img src="../../assets/imgs/dashboard-logo.png" class="bi">
                            <span id="dashboard-spn" class="menu-title">
                                <a href="./admin_dashboard.php" class="admin-nav-links">Dashboard</a>
                            </span>
                        </div>

                        <!--STUDENTS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'students' ? 'active' : ''; ?>" id="students" data-page="students">
                            <img src="../../assets/imgs/student.svg" class="bi">
                            <span id="students-spn" class="menu-title"> <a href="./admin_all_students.php" class="admin-nav-links">Students </a></span> 
                        </div>    
                        <!--TEACHERS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'teachers' ? 'active' : ''; ?>" id="teachers" data-page="teachers">
                            <img src="../../assets/imgs/teachers.svg" class="bi">
                            <span id="teachers-spn" class="menu-title"> <a href="./admin_all_teachers.php" class="allTeachers">Teachers</a></span>
                        </div> 
                        <!--ENROLLS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'enrolls' ? 'active' : ''; ?>" id="enrolls" data-page="enrolls">
                            <img src="../../assets/imgs/enrolls.svg" class="bi">
                            <span id="enrolls-spn" class="menu-title"> <a href="./admin_all_enrollees.php" class="enrolled"> Processed Enrollments</a></span>
                        </div>
                        <!--GRADE LEVELS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'grade-levels' ? 'active' : ''; ?>" data-page="grade-levels">
                            <img src="../../assets/imgs/sections-logo.png" class="bi">
                            <span id="grade-levels-spn" class="menu-title"> <a href="./admin_grade_levels.php" class="admin-nav-links"> Grade Levels </a></span>
                        </div>

                        <!--SUBJECTS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'subjects' ? 'active' : ''; ?>" data-page="subjects">
                            <img src="../../assets/imgs/subjects-logo.png" class="bi">
                            <span id="sections-spn" class="menu-title"> <a href="./admin_subjects.php" class="admin-nav-links"> Subjects</a></span>
                        </div>
                            <ul class="subjects-ul">
                                <li>
                                    <span class="sub-nav"> View Subject Details</span>
                                </li>
                            </ul>
                        <!--
                        <div class="menu border-100sb">
                            <img src="../../assets/imgs/calendar.png" class="bi">
                            <span id="sections-spn" class="menu-title"> <a href="./admin_schedules.php" class="admin-nav-links"> Schedules</a></span>
                        </div>
                        -->
                        <!--LOCKER-->
                        <div class="menu border-100sb <?php echo $active_menu === 'locker' ? 'active' : ''; ?>" data-page="locker">
                            <img src="../../assets/imgs/subjects-logo.png" class="bi">
                            <span id="locker-spn" class="menu-title"> <a href="./admin_locker.php" class="admin-nav-links"> Locker</a></span>
                        </div>
                        <!--SYSTEM MANAGEMENT -->
                        <div class="menu border-100sb <?php echo $active_menu === 'sysmanagement' ? 'active' : ''; ?>" data-page="sysmanagement">
                            <img src="../../assets/imgs/calendar.png" class="bi">
                            <span id="sysmanagement-spn" class="menu-title"> <a href="./admin_system_management.php" class="admin-nav-links"> System Management</a></span>
                        </div>
                        <!--ANNOUNCEMENTS-->
                        <div class="menu border-100sb <?php echo $active_menu === 'announcements' ? 'active' : ''; ?>" data-page="announcements">
                            <img src="../../assets/imgs/subjects-logo.png" class="bi">
                            <span id="announcements-spn" class="menu-title"> <a href="./admin_announcements.php" class="admin-nav-links"> Announcements</a></span>
                        </div>
                    </nav>
                </div>
            </div>
        </div>  