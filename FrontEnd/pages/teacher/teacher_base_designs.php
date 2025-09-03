<?php  
    require_once __DIR__ . '/../../../BackEnd/teacher/teacherIsAnAdviserView.php';
    require_once __DIR__ . '/../../../BackEnd/common/UserTypeView.php';
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['Staff']['User-Id']) && $_SESSION['Staff']['Staff-Type'] != 2) {
        header('Location: ../../Login.php');
        exit();
    }
    $teacherIsAnAdviser = new teacherIsAnAdviserView();
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/teacher/teacher-base-designs.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <script src="../../assets/js/teacher/teacher-base-designs.js" defer></script>
    <?php if(isset($pageCss)) {echo $pageCss;} else { echo '';} ?>
</head>
<body>
   <div class="main-wrapper">
        <?php require_once __DIR__ . '/./teacher_sidebar.php';?> 
        <div class="content">
            <?php require_once __DIR__ . '/./teacher_header.php'; 
                if(isset($PageContent)) {
                    echo $PageContent;
                }
            ?>
        </div>
   </div>
   <?php if(isset($pageJs)) {echo $pageJs;} else { echo '';} ?>
</body>
</html>
