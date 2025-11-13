<?php 
require_once __DIR__ . '/../session_init.php';
if(!isset($_SESSION['Staff']['User-Id']) || !isset($_SESSION['Staff']['Staff-Type']) || 
($_SESSION['Staff']['Staff-Type'] != 1 && $_SESSION['Staff']['Staff-Type'] != 2)) {
    header("Location: ../../Login.php");
    exit();
}
require_once __DIR__ . '/../../../BackEnd/staff/views/staffPendingEnrollmentsView.php';
require_once __DIR__ . '/../../../BackEnd/common/UserTypeView.php';
    $view = new UserTypeView((int)$_SESSION['Staff']['Staff-Type']);
    $pending = new staffPendingEnrollmentsView();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $view?> - Pending Enrollments </title>
    <link rel="stylesheet" href="../../assets/css/staff/staff-enrollment-pending.css">
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/fonts.css">
    <link rel="stylesheet" href="../../assets/css/notifications.css">
</head>

    <div class="main-content">
        <?php
            include_once __DIR__ . '/../notifications.php';
        ?>
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
                    <div class="header-pending">
                        <div class="header-left">
                            <h2> Pending Enrollments </h2>
                        </div>
                        <div class="header-right">
                            <input type=text id="search" placeholder="Search Name...">
                        </div>
                    </div>
                    <div class="filter-container">
                        <div class="filter-group">
                            <label for="filter-grade">Grade Level:</label>
                            <select id="filter-grade" class="filter-select">
                                <option value="all">All Grades</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-sex">Biological Sex:</label>
                            <select id="filter-sex" class="filter-select">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <button id="clear-filters" class="clear-filters-btn">Clear Filters</button>
                    </div>
                    <div class="menu-content">
                       <?php $pending->displayPendingEnrollees(); ?>
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
    <script src="../../assets/js/staff/staff-filter-enrollees.js" defer></script>
    <script src="../../assets/js/staff/staff-sort-enrollees.js" defer></script>
    <script src="../../assets/js/notifications.js" defer></script>
</body>    
</html>