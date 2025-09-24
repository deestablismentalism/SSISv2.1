<?php 
ob_start();
$pageJs = '<script type="module" src="../../assets/js/admin/admin-subjects-validation.js" defer></script>';
$pageTitle = "Admin Subjects";
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-subjects.css">';
?>
<div class="admin-subjects-content">
    <div class="admin-subject-header">    
        <h1 class="admin-subject-title"> Subjects List </h1>
        <button id="add-subject-button" class="submit-button"> Add Subject </button>  
    </div>
    <div class="subjects-list">
        <table class="subjects-table">
            <thead> 
                <th> Subject Name </th>
                <th> Section Name</th>
                <th> Grade Level </th>
                <th> Teacher Assigned </th>
                <th> Assign </th>
            </thead>
            <tbody> 
                <?php 
                    require_once __DIR__ . '/../../../BackEnd/admin/view/adminSubjectsView.php';
                    $view = new adminSubjectsView();
                    $view->displaySubjects();
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="success-message"></div>
<div class="error-message"></div>
<div class="modal" id="subjects-modal"> 
    <div class="modal-content" id="subjects-content"></div>
</div>

<?php 
    $pageContent = ob_get_clean();
    require_once __DIR__ . '/./admin_base_designs.php';
?>