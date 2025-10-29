<div class="header-wrapper">
    <div class="header">
        <p class="header-title">
            Welcome to South II Student Information System
        </p>
        <!-- end of header -->
        </div>
            <div class="account">
                <div class="account-settings-wrapper">
                    <?php 
                        $name = '';
                        $profilePicPath = null;
                        $defaultPic = '../../assets/imgs/default-avatar.svg';
                        
                        if (isset($_SESSION['Staff']['First-Name']) && $_SESSION['Staff']['User-Type']) {
                            $name = $_SESSION['Staff']['First-Name'];
                            
                            if (isset($_SESSION['Staff']['User-Id'])) {
                                require_once __DIR__ . '/../../../BackEnd/admin/view/adminProfilePictureView.php';
                                $profilePicView = new adminProfilePictureView();
                                $profilePicPath = $profilePicView->getProfilePicturePath($_SESSION['Staff']['User-Id']);
                            }
                        } 
                        else {
                            $name = "User Name";
                        }
                    ?>   
                    <img src="<?php echo $profilePicPath ?? $defaultPic; ?>" alt="Profile" class="admin-profile-pic">
                    <div class="admin-name-wrapper">
                        <h2 class="name"> <?php echo $name; ?></h2>
                        <span>  <?php $viewType = new UserTypeView((int)$_SESSION['Staff']['Staff-Type']); 
                                echo $viewType; ?> </span>
                    </div>
                    <!-- end of account-settings-wrapper -->
                </div>
                    <div class="account-settings-btn">
                       <div> <button class="account-btn dropdown" ><img src="../../assets/imgs/chevron-down-black.svg" id="account-drop" alt=""></button></div>
                            <div class="account-settings-btn-content-wrapper drop-content">
                                <div class="user-info-wrapper border-100sb">
                                    <img src="<?php echo $profilePicPath ?? $defaultPic; ?>" alt="Profile" class="dropdown-profile-pic">
                                    <div class="user-name">
                                        <p class="dropdown-user-name"><?php echo $_SESSION['Staff']['First-Name']; ?></p>
                                        <p class="dropdown-position"><?php $userType = new UserTypeView((int)$_SESSION['Staff']['Staff-Type']); echo $userType; ?></p>
                                    <!-- end of user-name -->
                                    </div>
                            <!-- end of user-info-wrapper -->
                            </div>
                            <div class="account-edit-info-wrapper">
                                <a href="./admin_edit_information_links.php"><img src="../../assets/imgs/edit-information.svg" class="edit-info-icon" alt="" ></a>
                                <a href="./admin_edit_information_links.php" class="edit-info-text">Edit Profile</a>
                            <!-- end of account-edit-info-wrapper -->
                            </div>  

                            <div class="account-link-wrapper">
                                <!-- <a href=""><img src="" alt="">Edit Profile</a><br> -->
                                <a href="../../Change_Password.php"><img src="../../assets/imgs/lock-solid.svg" class="change-pass-icon" alt=""></a>
                                <a href="../../Change_Password.php" class="update-password">Update Password</a><br>
                            <!-- end of account-link-wrapper  -->
                            </div>

                            <div class="account-logout-wrapper">
                                <a href="../../../BackEnd/common/logout.php" id="logout"><img src="../../assets/imgs/logout.svg" class="logout-icon" alt=""></a>
                                <a href="../../../BackEnd/common/logout.php" class="logout-text">Logout</a>
                            <!-- end of account-logout-wrapper  -->
                            </div>
                    <!-- end of account-settings-button-content-wrapper -->
                    </div>
            <!-- end of account -->
            </div>
    <!-- end of header-->
    </div>
<!-- end of header wrapper -->  
</div>
          