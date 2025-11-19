<?php
ob_start(); 
require_once __DIR__ . '/../../../BackEnd/admin/views/adminAllEnrolleesView.php';

$pageTitle = 'SSIS-Admin All Enrollees'; 
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-enrollees.css">';
$pageJs = '<script src="../../assets/js/admin/admin-all-enrollees.js" defer></script>';
$view = new adminAllEnrolleesView();
?>
<div class="admin-all-enrollees-content">
    <div class="enrollment-access">
        <div class="admin-all-enrollees-header">
            <div class="header-left">
                <div class="count-display">
                    Total Transactions: <span class="count-number">
                    <?php
                        $view->displayCount();
                        ?>
                    </span>
                </div>
            </div>
            <div class="header-right">
                <div class="filter-group">
                    <select id="status-filter" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="1">Enrolled</option>
                        <option value="2">Rejected</option>
                        <option value="3">Pending</option>
                        <option value="4">Archived</option>
                    </select>
                    <select id="source-filter" class="filter-select">
                        <option value="">All Sources</option>
                        <option value="admin">Admin Created</option>
                        <option value="user">User Created</option>
                    </select>
                </div>
                <input type="text" id="search" class="search-box" placeholder="Search by name, LRN, grade level, or status...">
            </div>
        </div>
        <div class="all-enrollees-table">
                <?php      
                $view->displayAllTransactions();
                ?>
        </div>
        <div id="enrolleeModal" class="modal">
            <div class="modal-content">
            </div>
        </div>
    </div>
</div>
</div>  
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>