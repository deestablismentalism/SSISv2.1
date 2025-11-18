<div class="header-wrapper">
            <div class="header">
                <img src="../../assets/imgs/LS2ES.png" alt="Lausent South II Elementary School" class="header-logo">
            </div>
            <div class="account">
                <div class="account-settings-wrapper">
                    <?php 
                        $name = '';
                        $profilePicPath = null;
                        $defaultPic = '../../assets/imgs/default-avatar.svg';
                        
                        if (isset($_SESSION['Staff']['First-Name']) && isset($_SESSION['Staff']['Last-Name']) && $_SESSION['Staff']['User-Type']) {
                            $name = $_SESSION['Staff']['First-Name'] . " " . $_SESSION['Staff']['Last-Name'];
                            
                            // Get profile picture path
                            if (isset($_SESSION['Staff']['User-Id'])) {
                                require_once __DIR__ . '/../../../BackEnd/teacher/views/teacherProfilePictureView.php';
                                $profilePicView = new teacherProfilePictureView();
                                $profilePicPath = $profilePicView->getProfilePicturePath($_SESSION['Staff']['User-Id']);
                            }
                        } 
                        else {
                            $name = "User Name";
                        }
                    ?>   
                    <img src="<?php echo $profilePicPath ?? $defaultPic; ?>" alt="Profile" class="teacher-profile-pic">
                    <div class="teacher-name-wrapper">
                        <h2 class="name"> <?php echo $name; ?></h2>
                        <span>  <?php $viewType = new UserTypeView((int)$_SESSION['Staff']['Staff-Type']);
                                    echo $viewType; ?> </span>
                    </div>
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
                            <a href="./teacher_edit_information_links.php"><img src="../../assets/imgs/edit-information.svg" class="edit-info-icon" alt="" ></a>
                            <a href="./teacher_edit_information_links.php" class="edit-info-text">Edit Profile</a>
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