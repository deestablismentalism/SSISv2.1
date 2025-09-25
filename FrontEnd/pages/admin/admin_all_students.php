<?php
ob_start(); 
    $pageTitle = "Admin Students";
    $pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-students.css">';
    $pageJs = '<script src="../../assets/js/admin/admin-all-students.js" defer></script>';
?>
   <div class="admin-all-students-content">
        <div class="table-title">
            <div class="table-title-left"><h2> Students </h2></div>
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