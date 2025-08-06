<!DOCTYPE html>
<?php 
ob_start();
$pageTitle = "Admin Sections";
$pageJs = "../../assets/js/admin/admin-sections.js"; 
$pageCss = "../../assets/css/admin/admin-sections.css";
    
?>

<div class="admin-sections-content">
    <div class="sections-form"> 
        <form action="../../../BackEnd/api/admin/postSections.php" method="post" class="form"> 
            <input type="text" name="section-name" placeholder="Enter Section Name..." required>
            <select name="section-level">
                <?php
                    include_once __DIR__ . '/../../../BackEnd/common/getGradeLevels.php';
                    $gradeLevels = new getGradeLevels();
                    $gradeLevels->createSelectValues();
                ?>
            </select>
            <button type="submit" class="submit-btn">Add Section</button>
        </form>
    </div>
    <div class="sections-list">
        <div class="sections-list-header">
            <h2> Sections List </h2>
            <a href="Admin_Student_Per_Section.php"> Assign Students to Section </a>
        </div>
        <table class="admin-sections-table" id="admin-sections-table">
            <thead> 
                <tr>
                    <th> Section Name </th>
                    <th> Grade Level </th>
                </tr>
            </thead>
            <tbody> 
                <?php 
                    include_once __DIR__ . '/../../../BackEnd/admin/adminSectionsView.php';
                    $view = new adminSectionsView();
                    $view->displayAdminSections();
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>