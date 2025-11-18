<div class="mobile-header-wrapper">
            <button class="bar-btn-mob" onclick="sideBarMobileOpen()"></button>
            <div class="user-btn-mob"></div>
        </div>
        <!--HEADER-->
        <?php 
            require_once __DIR__ . '/../../../BackEnd/common/isAcademicYearSet.php';
            $set = new isAcademicYearSet();
            $isSet = $set->isSet();
        ?>
        <div class="header-wrapper" id="header-wrapper">
            <!-- Hamburger Menu Button (Mobile Only) -->
            <button class="hamburger-menu" id="hamburger-menu" aria-label="Toggle navigation menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <!-- Navigation Container -->
            <div class="nav-container-1" id="nav-container">
                <div class="nav-logo-wrapper">
                    <img src="../../assets/imgs/LS2ES.png" alt="LS2ES Logo">
                </div>
                <nav>
                    <ul class="nav-1">
                        <li class="nav-item">
                            <a href="./user_enrollees.php" class="nav-link">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <?php if($isSet): ?>
                            <a href="./user_enrollment_form.php" class="nav-link">
                                Enrollment Form
                            </a>
                            <?php else: ?>
                            <a href="#" class="nav-link disabled" 
                                style="pointer-events: none; color: gray; opacity: 0.6; cursor: not-allowed;"
                                title="Enrollment is not available until an academic year is set.">
                                Enrollment Form
                            </a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item">                                
                            <a href="./user_all_students.php" class="nav-link">
                                Students List
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Mobile Overlay -->
            <div class="nav-overlay" id="nav-overlay"></div>
<div class="account">
        <div class="account-settings-wrapper">
            <?php
                if (isset($_SESSION['User']) && isset($_SESSION['User']['First-Name']) && isset($_SESSION['User']['Last-Name']) && isset($_SESSION['User']['User-Type'])) {
                    $name = $_SESSION['User']['First-Name'] . ", " . $_SESSION['User']['Last-Name'];
                    
                    // Get profile picture path
                    $profilePicPath = null;
                    $defaultPic = '../../assets/imgs/default-avatar.svg';
                    
                    if (isset($_SESSION['User']['User-Id'])) {
                        require_once __DIR__ . '/../../../BackEnd/user/views/userProfilePictureView.php';
                        $profilePicView = new userProfilePictureView();
                        $profilePicPath = $profilePicView->getProfilePicturePath($_SESSION['User']['User-Id']);
                    }
                    
                    echo '<div class="user-profile-info">';
                    echo '<img src="' . ($profilePicPath ?? $defaultPic) . '" alt="Profile" class="user-profile-pic">';
                    echo '<div class="user-text-wrapper">';
                    echo "<p class='user-name'>$name</p>";
                    
                    $viewType = new UserTypeView((int)$_SESSION['User']['User-Type']);
                    echo '<span>' . $viewType . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
        <div class="account-settings-btn">
            <button class="account-btn"><img src="../../assets/imgs/chevron-down-black.svg" id="account-drop" alt=""></button>
            <div class="account-settings-btn-content-wrapper">
                <div class="user-info-wrapper border-100sb">
                    <img src="../../assets/imgs/check2-circle.svg" alt="" class="user-icon">
                    <div class="user-name">
                        <p class="account-type"><?php echo $viewType ?></p>
                    </div>
                </div>
                <div class="account-link-wrapper">
                    <!-- <a href=""><img src="" alt="">Edit Profile</a><br> -->
                    <a href="../../Change_Password.php"><img src="../../assets/imgs/lock-solid.svg" class="change-pass-icon" alt=""></a>
                    <a href="../../Change_Password.php" class="update-password">Update Password</a><br>
                </div>
                <div class="account-logout-wrapper">
                    <a href="../../../BackEnd/common/logout.php" id="logout"><img src="../../assets/imgs/logout.svg" class="logout-icon" alt=""></a>
                    <a href="../../../BackEnd/common/logout.php" class="logout-text">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END OF HEADER-->
<!-- MOBILE VERISON SIDEBAR-->
<div class="mobile-sidebar-wrapper">
    <div class="sidebar-show-mob">
            <p class="ssis-title">SSIS</p>
            <button class="bar-btn-show-mob" onclick="sideBarMobileClose()"></button>
        </div>
    <!-- DASHBOARD MOBILE -->
    <div class="menu-mob-wrapper">
        <div class="menu-mob border-100sb">
            <div class="menu-button-wrapper">
                <div class="dashboard-icon-mob icon-title-mob"></div>
                <p class="dashboard-title menu-title-mob"> Dashboard </p>
                <button class="dashboard-arrow-mob arrow-mob" onclick="dashboardDropMobile()"></button>
            </div>
            <ul class="dashboard-mob-ul"> 
                <li> <a href="#"> Enrollment Form </a> </li>
                <li> <a href="#"> Enrollment Status </a> </li>
            </ul>
        </div>
    </div>
    <!-- SUBJECTS MOBILE -->
    <div class="menu-mob-wrapper">
        <div class="menu-mob border-100sb">
            <div class="menu-button-wrapper">
                <div class="subjects-icon-mob icon-title-mob"></div>
                <p class="subjects-title menu-title-mob"> Subjects </p>
                <button class="subjects-arrow-mob arrow-mob" onclick="subjectsDropMobile()"></button>
            </div>
            <ul class="subjects-mob-ul">
                <li>
                    <a href="All_Subjects.php" class="">All subjects</a>
                </li>
                <li>
                    <a href="#" class="">View Grades</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- TEACHERS MOBILE -->
    <div class="menu-mob-wrapper">
        <div class="menu-mob border-100sb">
            <div class="menu-button-wrapper">
                <div class="teachers-icon-mob icon-title-mob"></div>
                <p class="teachers-title menu-title-mob"> Teachers </p>
                <button class="teachers-arrow-mob arrow-mob" onclick="teachersDropMobile()"></button>
            </div>
            <ul class="teachers-mob-ul">
                <li>
                    <a href="#" class="">Prof Hi</a>
                </li>
                <li>
                    <a href="#" class="">Prof Hi</a>
                </li>
            </ul>
        </div>
    </div>
    <!--MISSION AND VISION-->
    <div class="menu-mob-wrapper">
        <div class="menu-mob border-100sb">
            <div class="menu-button-wrapper">
                <div class="mv-icon-mob icon-title-mob"></div>
                <p class="mv-title menu-title-mob">Mission and Vision</p>
            </div>
        </div>
    </div>
</div>