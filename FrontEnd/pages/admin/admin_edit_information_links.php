<?php
ob_start();

$pageTitle = 'SSIS - Edit Information';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-edit-information-links.css" media="all">';
$pageJs = '<script type="module" src="../../assets/js/admin/admin-edit-information-links.js"></script>';

?>

<!--START OF THE MAIN CONTENT-->
<div class="content">
    <div class="container">
        <p class="title">Edit Information</p>
        <div class="links">
            <div class="card">
                <button id="edit-personal-information">Edit Personal Information</button>
            </div>
            <div class="card">
                <button id="edit-address">Edit Address</button>
            </div>
            <div class="card">
                <button id="edit-credentials">Edit Credentials</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="information-modal">
    <div class="modal-content" id="information-modal-content">
        
    </div>
</div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>
