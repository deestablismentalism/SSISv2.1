<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/views/adminStudentInfo.php';
$view = new adminStudentInfo();
?>
<div class="view-student-content">
    <?php $view->displayGlobalError(); ?>
    
    <div class="view-header">
        <div class="view-status-container">
            <p class="view-status-label">Current Status</p>
            <p class="view-status-value status-active">Active</p>
        </div>
    </div>
    
    <div class="view-body">
        <div class="view-section">
            <p class="view-section-title">Student Personal Information</p>
            <?php $view->displayStudentInfo();?>
        </div>
        
        <div class="view-section">
            <p class="view-section-title">Parent/Guardian Information</p>
            <?php $view->displayParentInfo();?>
        </div>
    </div>
</div>