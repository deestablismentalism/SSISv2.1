<?php
ob_start(); 
    $pageTitle = "Admin Students";
    $pageCss = '<link rel="stylesheet" href="../../assets/css/teacher/teacher-all-students.css">';
    $pageJs = '<script src="../../assets/js/teacher/teacher-all-students.js" defer></script>';
?>
   <div class="teacher-all-students-content">
        <div class="table-title">
            <div class="table-title-left">
                <h2>Students</h2>
            </div>
            <div class="table-title-right">
                <select id="filter-grade" class="filter-select" aria-label="Filter by grade">
                    <option value="">All Grades</option>
                </select>
                <select id="filter-status" class="filter-select" aria-label="Filter by status">
                    <option value="">All Statuses</option>
                </select>
                <select id="filter-section" class="filter-select" aria-label="Filter by section">
                    <option value="">All Sections</option>
                </select>
                <input type="text" id="search" class="search-box" placeholder="Search student..." aria-label="Search students">
            </div>
        </div>
        <div class="table-container">
            <table class="students">
                <thead> 
                    <tr>
                        <th>Student Name</th>
                        <th>Student LRN</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>Student Birthdate</th>
                        <th>Student Status</th>
                        <th>View Student</th>
                    </tr>
                </thead>
                <tbody class="student-info">
                    <?php 
                        require_once __DIR__ . '/../../../BackEnd/teacher/views/teacherAllStudentsView.php';
                        $view = new teacherAllStudentsView();
                        $view->displayStudents();
                    ?>
                </tbody>
            </table>
        </div>
   </div>
<?php 
$pageContent = ob_get_clean();
require_once __DIR__ . '/./teacher_base_designs.php';
?>