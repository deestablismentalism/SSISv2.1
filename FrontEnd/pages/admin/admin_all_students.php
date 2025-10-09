<?php
ob_start(); 
    $pageTitle = "Admin Students";
    $pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-students.css">';
    $pageJs = '<script src="../../assets/js/admin/admin-all-students.js" defer></script>';
?>
   <div class="admin-all-students-content">
        <div class="table-title">
            <div class="table-title-left"><h2> Students </h2></div>
            <div class="filter-panel"> 
                <select id="filter-grade"> 
                    <option value="All">All Grades</option> 
                    <option value="Kinder 1">Kinder 1</option> 
                    <option value="Kinder 2">Kinder 2</option> 
                    <option value="Grade 1">Grade 1</option> 
                    <option value="Grade 2">Grade 2</option> 
                    <option value="Grade 3">Grade 3</option> 
                    <option value="Grade 4">Grade 4</option> 
                    <option value="Grade 5">Grade 5</option> 
                    <option value="Grade 6">Grade 6</option> 
                </select> 
                
                <select id="filter-section"> 
                    <option value="">All Sections</option> 
                    <?php foreach ($sections as $section): ?> <option value="<?= $section['Section_Name'] ?>"><?= $section['Section_Name'] ?></option> <?php endforeach; ?> 
                </select> 

                <select id="filter-status"> 
                    <option value="">All Status</option> 
                    <option value="1">Active</option> 
                    <option value="2">Inactive</option> 
                    <option value="3">Dropped</option> 
                </select> 
                
                <select id="filter-sort"> 
                    <option value="">Sort</option> 
                    <option value="asc">Ascending</option> 
                    <option value="desc">Descending</option> 
                </select> 
                
                <button id="apply-filters">Filter</button> 
            </div>
            <div> <button class="add-student-btn"> <img src="../../assets/imgs/plus-solid.svg"></button></div>
            <div class="table-title-right">
                <input type="text" id="search" class="search-box" placeholder="search student...">
            </div>
        </div>

        <div class="table-container">
            <table class="students">
                <thead> 
                    <th> Student Name </th>
                    <th> Student LRN </th>
                    <th> Grade Level </th>
                    <th> Section </th>
                    <th> Student Email </th>
                    <th> Student Status </th>
                    <th> Student Actions </th>
                </thead>
                <tbody class="student-info">
                    <?php 
                        include_once __DIR__ . '/../../../BackEnd/admin/view/adminStudentsView.php';
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