<?php 
ob_start();
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-students-page.css">';
$pageJs = '<script type="module" src="../../assets/js/user/user-students-page.js" defer></script>';
?>
<div class="user-students-page-content">
    <div class="back-button">
        <button> <img src="../../assets/imgs/arrow-left-solid.svg"> Back to students list page</button>
    </div>
    <div class="user-students-page-header">
        <p class="student-name"> 
            Student Name
        </p>
        <nav class="student-navigation-list-wrapper">
            <ul class="student-navigation-list">
                <li>
                    <span> My Class </span>    
                <li>
                <li>
                    <span> My Section</span>    
                <li>
                <li>
                    <span> My Grades</span>    
                <li>        
            </ul>
        </nav>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
