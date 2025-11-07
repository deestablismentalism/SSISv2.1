<?php 
ob_start();
$pageJs = '<script type="module" src="../../assets/js/admin/admin-subjects.js?v=' . time() . '" defer></script>';
$pageTitle = "Admin Subjects";
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-subjects.css">';
?>
<div class="admin-subjects-content">
    <div class="subjects-title-wrapper">    
        <h1 class="page-title">Subjects</h1>
        <button id="add-subject-button" class="submit-btn">Add Subject</button>  
    </div>
    
    <div class="subjects-container">
        <div id="loading-state" class="loading-container">
            <div class="spinner"></div>
            <p>Loading subjects...</p>
        </div>
        
        <div id="subjects-list" class="subjects-list">
            <!-- Subjects will be dynamically inserted here -->
        </div>
        
        <div id="empty-state" class="empty-state" style="display: none;">
            <p>No subjects found.</p>
        </div>
    </div>
    
    <div id="subjects-modal" class="modal"> 
        <div class="modal-content" id="subjects-content"></div>
    </div>
</div>

<?php 
    $pageContent = ob_get_clean();
    require_once __DIR__ . '/./admin_base_designs.php';
?>