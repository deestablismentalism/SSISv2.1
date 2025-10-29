<?php
ob_start();

$pageTitle = 'SSIS - Edit Personal Information';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-edit-personal-information.css">
'
?>

<script src="../../assets/js/admin/admin-edit-staff-information.js"></script>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>

 