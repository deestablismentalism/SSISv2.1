<?php
ob_start(); 
    $pageTitle = "Admin Students";
    $pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-students.css">';
    $pageJs = '<script src="../../assets/js/admin/admin-all-students.js" defer></script>';
?>
   <div class="admin-all-students-content">
        <div class="table-title">
            <div class="table-title-left">
                <h2>Students</h2>
            </div>
            <div>
                <button class="add-student-btn" aria-label="Add new student">
                    <img src="../../assets/imgs/plus-solid.svg" alt="Add">
                </button>
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
                        <th>Student Email</th>
                        <th>Student Status</th>
                        <th>Student Actions</th>
                    </tr>
                </thead>
                <tbody class="student-info">
                    <?php 
                        include_once __DIR__ . '/../../../BackEnd/admin/views/adminStudentsView.php';
                        $view = new adminStudentsView();
                        $view->displayStudents();
                    ?>
                </tbody>
            </table>
        </div>
   </div>
<?php 
$pageContent = ob_get_clean();

require_once __DIR__ . '/./admin_base_designs.php';
?>