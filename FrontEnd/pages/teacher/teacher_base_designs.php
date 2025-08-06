<!DOCTYPE html>
<html>
<head>
<?php  
    require_once __DIR__ . '/../../../BackEnd/common/UserTypeView.php';
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['Staff']['User-Id']) && $_SESSION['Staff']['Staff-Type'] != 2) {
        header('Location: ../../Login.php');
        exit();
    }
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../assets/css/teacher/teacher-base-designs.css">
<link rel="stylesheet" href="../../assets/css/reset.css">
<script src="../../assets/js/teacher/teacher-base-designs.js" defer></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-wrapper">
            <div class="sidebar-title">
                <span class="SSIS">SSIS</span>
                <button class="menu-btn" onclick="menu()"><img src="../../assets/imgs/bar.svg" class="menu-btn"></button>
            </div>
            <div class="menu-wrappper">
                <!--DASHBOARD-->
                <div class="menu border-100sb" id="dashboard">
                    <img src="../../assets/imgs/easel.svg" class="bi">
                    <span id="dashboard-spn" class="menu-title">Dashboard</span>
                    <button class="dashboard-btn" onclick="dashboarddrop()"><img src="../../assets/imgs/chevron-down.svg" class ="bi-chevron-down"></button>
                    <ul class="dashboard-ul">
                        <li>
                            <a href="../staff/staff_pending_enrollments.php">Home</a>
                        </li>
                        <!-- <li>
                            <a href="../userPages/User_Enrollment_Status.php" class="eStat">Enrollment Status</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="header-wrapper">
            <div class="header">
                <p class="header-title">
                    Welcome to South II Student Information System
                </p>
            </div>
            <div class="account">
                <div class="account-settings-wrapper">
                    <?php 
                        $name = '';
                        if (isset($_SESSION['Staff']['First-Name']) && isset($_SESSION['Staff']['Last-Name']) && $_SESSION['Staff']['User-Type']) {
                            $name = $_SESSION['Staff']['First-Name'] . " " . $_SESSION['Staff']['Last-Name'];
                        } 
                        else {
                            echo "User Name";
                        }
                    ?>   
                    <h2 class="name"> <?php echo $name; ?></h2>
                    <span>  <?php $viewType = new UserTypeView(); ?> </span>
                </div>
                <div class="account-settings-btn">
                    <button class="account-btn" onclick="accountDrop()"><img src="../../assets/imgs/chevron-down-black.svg" id="account-drop" alt=""></button>
                    <div class="account-settings-btn-content-wrapper">
                        <div class="user-info-wrapper border-100sb">
                            <img src="../../assets/imgs/check2-circle.svg" alt="">
                            <div class="user-name">
                                <p class="account-type">User</p>
                            </div>
                        </div>
                        <div class="account-edit-info-wrapper">
                            <a href="./admin_edit_information_links.php"><img src="../../assets/imgs/edit-information.svg" class="edit-info-icon" alt="" ></a>
                            <a href="./admin_edit_information_links.php" class="edit-info-text">Edit Profile</a>
                        </div>  

                        <div class="account-link-wrapper">
                            <!-- <a href=""><img src="" alt="">Edit Profile</a><br> -->
                            <a href="../Change_Password.php"><img src="../../assets/imgs/lock-solid.svg" class="change-pass-icon" alt=""></a>
                            <a href="../Change_Password.php" class="update-password">Update Password</a><br>
                        </div>
                        <div class="account-logout-wrapper">
                            <a href="../../../BackEnd/common/logout.php" id="logout"><img src="../../assets/imgs/logout.svg" class="logout-icon" alt=""></a>
                            <a href="../../../BackEnd/common/logout.php" class="logout-text">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
