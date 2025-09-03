<?php 

ob_start();

$pageTitle = 'Admin view section';
$pageCss = '<link rel="stylesheet" href= "../../assets/css/admin/admin-view-section.css">';
$pageJs = '<script src="../../assets/js/admin/admin-view-section.js" defer></script>';
require_once __DIR__ . '/../../../BackEnd/admin/adminViewSectionView.php';
$adminViewSectionView = new adminViewSectionView();
?>

    <div class="admin-view-section-content">
        <div class="section-details-container"> 
           <button id="edit-section-btn" class="edit-section"> Edit Section details </button>
           <div class="section-name-container">
                <div class="section-name"> <h1> <?php $adminViewSectionView->displaySectionName(); ?> </h1></div>
           </div>
           <div class="adviser-name-container">
                <div class="adviser-name"> <h1> <?php $adminViewSectionView->displayAdviserName(); ?></h1></div>
           </div>
           <div class="students-list-container">
                <div class="students-list-title-container">
                    <h1 class="students-list-title">All Students </h1>
                </div>
                <div class="students-list">
                    <?php 
                        $adminViewSectionView->displaySectionStudents();
                    ?>
                </div>
           </div>
        </div>

    <div class="modal" id="admin-view-section-edit-modal">
        <div class="modal-content" id="admin-view-section-edit-content"></div>
    </div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>