<?php 
ob_start();
$pageTitle = "Admin Sections";
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-sections.css">'; 
$pageJs= '<script type="module" src="../../assets/js/admin/admin-sections.js" defer></script>';
    
?>

<div class="admin-sections-content">
    <div class="sections-title-wrapper"> 
            <h1 class="page-title"> Sections List</h1>
            <button class="submit-btn" id="add-section-btn">Add Section</button>
    </div>
    <div class="sections-list-wrapper">
        <div class="sections-list-header">
            <input type="text" name="search" id="search-section" placeholder="Search section...">        
        </div>

      <template id="sections-list-template">
        <div class="sections-list">
            <div class="section-major-information">
                <div class="section-name"><h1 class="section">Sampaguita</h1> <div class="section-grade-level">Grade 10</div></div>
                <a class="edit-section" href=""><img src="../../assets/imgs/eye-regular.svg"></a>
            </div>
            <div class="section-minor-information">
                <div class="adviser">
                    <span class="adviser-title">Adviser: </span>
                    <span class="adviser-value"> No adviser yet</span>
                </div>
                <div class="students">
                    <span class="students-title"> Students: </span>
                    <span class="students-value"> No students yet</span>
                </div>
            </div>
        </div>
      </template>

        <div class="sections-list-container"></div>
    </div>
    <div id="add-section-modal"class="modal">
        <div class="modal-content"></div>
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>