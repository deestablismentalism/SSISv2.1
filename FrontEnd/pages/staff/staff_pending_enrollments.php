<!DOCTYPE html>
<?php 
    require_once __DIR__ . '/../../../BackEnd/common/UserTypeView.php';
    require_once __DIR__ . '/../session_init.php';
    if(!isset($_SESSION['Staff']['User-Id']) || !isset($_SESSION['Staff']['Staff-Type']) || 
    ($_SESSION['Staff']['Staff-Type'] != 1 && $_SESSION['Staff']['Staff-Type'] != 2)) {
        session_destroy();
        header("Location: ../../Login.php");
        exit();
    }
    $view = new UserTypeView((int)$_SESSION['Staff']['Staff-Type']);
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $view?> - Pending Enrollments </title>
    <link rel="stylesheet" href="../../assets/css/staff/staff-enrollment-pending.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/fonts.css">
</head>

    <div class="main-content">
        <div class="sidebar">
            <div class="sidebar-title-container">
                <div class="sidebar-title">
                    <span class="SSIS">SSIS</span>
                </div>  
            </div>
            <div class="user-name">
                <?php 
                    $name = '';
                    if(isset($_SESSION['Admin'])) {
                        $name = $_SESSION['Admin']['First-Name'] . ' ' . $_SESSION['Admin']['Last-Name'];
                    }
                    else if(isset($_SESSION['Staff'])) {
                        $name = $_SESSION['Staff']['First-Name'] . ' ' . $_SESSION['Staff']['Last-Name'];
                    }
                    else {
                        $name = 'User Name';    
                    }
                    ?>
                    <h2 class='name'> <?php echo $name; ?> </h2>
                    <span class='user-type'> <?php echo $view?> </span>
                    <button id="back-button"> Go Back </button>
            </div>

        </div>
        <div class="content">
            <div class="header">
                <div class="header-title">
                    <p class="header-title">
                    Welcome to South II Student Information System
                    </p>
                </div>
            </div>
            <div class="enrollment-start">
                <div class="enrollment-access">
                    <div class="header">
                        <div class="header-left">
                            <h2> Pending Enrollments </h2>
                        </div>
                        <div class="header-right">
                            <input type=text id="search" placeholder="search enrollee name...">
                        </div>
                    </div>
                    <div class="menu-content">
                        <table class="enrollments">
                            <tr>
                                <th>Student LRN</th>
                                <th>Student Name</th>
                                <th> Age </th>
                                <th> Birthdate </th>
                                <th> Biological Sex </th>
                                <th> Display Enrollment Information</th>
                            </tr>
                            <tbody id="query-table"> 
                                <?php      
                                require_once __DIR__ . '/../../../BackEnd/staff/staffEnrollmentStatusView.php';
                                $enrollmentStatusView = new staffEnrollmentStatusView();
                                $enrollmentStatusView->displayEnrollees();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="enrolleeModal" class="modal">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <script type="module" src="../../assets/js/staff/staff-enrollee-popUp-handler.js" defer></script>
    <script src="../../assets/js/admin/admin-searchPendingEnrollees.js" defer></script>
</body>    
</html>