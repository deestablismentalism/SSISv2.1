<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../BackEnd/common/userTypeView.php';
if (!isset($_SESSION['Staff']) && $_SESSION['Staff']['Staff-Type'] !== 1) {
    header("Location: ../../Login.php");
    exit();
}
?>
<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php if(isset($pageTitle)) {echo $pageTitle;}else{echo "Page";} ?></title>
    <link rel="preconnect" href="../../assets/css/fonts.css" as="style" crossorigin>
    <link rel="stylesheet" href="../../assets/css/fonts.css">
    <link rel="stylesheet" href="../../assets/css/admin/admin-base-designs.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/loader.css">
    <link rel="icon" href="../../../favicon.ico">
    <?php if (isset($pageCss)) echo $pageCss; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    <img src="../../assets/imgs/graduation-cap.png" alt="graduation-cap" fetchpriority="high" aria-hidden="true" hidden>
</head>
<body>
        <?php
            require_once __DIR__ . '/../loader.php';
        ?>

        <div class="main-wrapper">
        <?php require_once __DIR__ . '/./admin_sidebar.php'; ?>
        <div class="content">
            <?php require_once __DIR__ . '/./admin_header.php';
                if(isset($pageContent)) {
                   echo $pageContent;
                }
            ?>
        </div>
    </div>
</body>
<script src="../../assets/js/admin/admin-base-designs.js" defer></script>
<?php if(isset($pageJs)) echo $pageJs ?>
</html>

