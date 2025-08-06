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
                        if (isset($_SESSION['Staff']['First-Name']) && isset($_SESSION['Staff']['Last-Name']) && $_SESSION['Staff']['User-Type']) {
                            $name = $_SESSION['Staff']['First-Name'] . " " . $_SESSION['Staff']['Last-Name'];
                        } 
                        else {
                            echo "User Name";
                        }
                    ?>   
                    <h2 class="name"> <?php echo $name; ?></h2>
                    <span>  <?php $viewType = new UserTypeView(); ?> </span>
                    <!-- end of account-settings-wrapper -->
                </div>
                    <div class="account-settings-btn">
                       <div> <button class="account-btn dropdown" ><img src="../../assets/imgs/chevron-down-black.svg" id="account-drop" alt=""></button></div>
                            <div class="account-settings-btn-content-wrapper drop-content">
                                <div class="user-info-wrapper border-100sb">
                                    <img src="../../assets/imgs/check2-circle.svg" alt="">
                                    <div class="user-name">
                                        <p class="account-type">User</p>
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
                                <a href="../Change_Password.php"><img src="../../assets/imgs/lock-solid.svg" class="change-pass-icon" alt=""></a>
                                <a href="../Change_Password.php" class="update-password">Update Password</a><br>
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
          