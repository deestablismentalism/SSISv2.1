<div class="sidebar active">
        <div class="sidebar-wrapper">
            <div class="sidebar-title" id="sidebar-button-wrapper">
                <span class="SSIS">SSIS</span>
                <button class="menu-btn"><img src="../../assets/imgs/bar.svg" id="sidebar-toggle-button"></button>
            </div>
            <div class="menu-wrappper" id="sidebar-menu-wrapper">
                <nav>
                    <!--DASHBOARD-->
                    <div class="menu border-100sb" id="dashboard">
                        <img src="../../assets/imgs/easel.svg" class="bi">
                        <span class="menu-title"><a href="./teacher_dashboard.php" class="teacher-nav-links">Dashboard</a></span>
                    </div>
                    <!--ALL STUDENTS-->
                    <div class="menu border-100sb" id="students">
                        <img src="../../assets/imgs/student.svg" class="bi">
                        <span class="menu-title"><a href="./teacher_students.php" class="teacher-nav-links">All Students</a></span>
                    </div>
                    <!--PENDING ENROLLMENTS-->
                    <div class="menu border-100sb" id="pending">
                        <img src="../../assets/imgs/newspaper.png" alt="newspaper" class="bi">
                        <span class="menu-title"><a href="../staff/staff_pending_enrollments.php" class="teacher-nav-links">Pending Enrollments</a></span>
                    </div>
                    <!--SUBJECTS HANDLED-->
                    <div class="menu border-100sb" id="subjects">
                        <img src="../../assets/imgs/subjects-logo.png" alt="subjects" class="bi">
                        <span class="menu-title"><a href="./teacher_subjects_handled.php" class="teacher-nav-links">Subjects Handled</a></span>
                    </div>
                    <!--GRADE STUDENTS-->
                    <div class="menu border-100sb" id="grading">
                        <img src="../../assets/imgs/a-plus.png" alt="grade-students" class="bi">
                        <span class="menu-title"><a href="./teacher_grades.php" class="teacher-nav-links">Grade Students</a></span>
                    </div>
                    <!--LOCKER-->
                    <div class="menu border-100sb" id="locker">
                        <img src="../../assets/imgs/subjects-logo.png" alt="locker" class="bi">
                        <span class="menu-title"><a href="./teacher_locker.php" class="teacher-nav-links">Locker</a></span>
                    </div>
                    <?php
                        $teacherIsAnAdviser->displayAdvisoryHyperLink();
                    ?>
                </nav>
            </div>
        </div>
    </div>