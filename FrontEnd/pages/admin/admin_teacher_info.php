<?php
        include_once __DIR__ . '/./admin_base_designs.php'; 
?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <link rel="stylesheet" href="../../assets/css/admin/admin-teacher-info.css">
      <!--START OF THE MAIN CONTENT-->
      <div class="content">
            <form action="../server_side/post_edit_staff_information.php" method="POST" id="editForm">
                <div class="teacher-info-start">
                    <div class="header">
                        <div class="header-left">
                            <h2> Teacher Information </h2>
                        </div>
                        <!-- <div class="header-middle">
                            <p class="teacher-info">Personal Information and Position</p>
                        </div> -->
                        <div class="header-right">
                            <p class="teacher-status">Status</p>
                            <p class="status" id="statusDisplay">
                                <?php
                                    require_once __DIR__ . '/../../../BackEnd/admin/adminTeacherInfoView.php';
                                    $teacherInfo = new adminTeacherInformationView();
                                    $teacherInfo->displayStatus();
                                ?>
                            </p>
                            <select id="statusSelect" name="status" style="display:none;">
                                <option value="Active">Active</option>
                                <option value="Retired">Retired</option>
                                <option value="Transferred Out">Transferred Out</option>
                            </select>
                        <?php 
                            if ($_SESSION['Admin']['User-Type'] == 1) {
                            echo '<button type="button" id="editButton" class="button" onclick="enableEdit()">Edit</button>
                                <button type="submit" id="saveButton" style="display:none;">Save</button>';
                            }
                        ?>

                    </div>
                </div>
                <div class="menu-content">
                    <div class="profile-menu">
                        <div class="profile-pic">
                            <img src="../../assets/imgs/sample-teacher.png" alt="Teacher">
                        </div>
                        <div class="profile-name">
                            <p class="profile-top">Teacher Name</p>
                            <p class="profile-bottom">
                                <?php
                                    $teacherInfo = new adminTeacherInformationView();
                                    $teacherInfo->displayFullName();
                                ?>
                            </p>
                        </div>
                        <div class="profile-email">
                            <p class="profile-top">Email</p>
                            <p class="profile-bottom">
                                <?php
                                    $teacherInfo = new adminTeacherInformationView();
                                    $teacherInfo->displayEmail();
                                    ?>
                            </p>
                        </div>
                        <div class="profile-contact">
                            <p class="profile-top">Contact Number</p>
                            <p class="profile-bottom">
                                <?php
                                    $teacherInfo = new adminTeacherInformationView();
                                    $teacherInfo->displayContact();
                                    ?>
                            </p>
                        </div>
                        <div class="profile-address">
                            <p class="profile-top">Address</p>
                            <p class="profile-bottom">
                                <?php
                                    $teacherInfo = new adminTeacherInformationView();
                                    $teacherInfo->displayAddress();
                                ?>  
                            </p>
                        </div>
                    </div>
                    <div class="profile-content">
                        <p class="position">Current Postion</p>
                        <p id="positionDisplay">
                            <?php
                                $teacherInfo = new adminTeacherInformationView();
                                $teacherInfo->displayPosition();
                            ?>
                        </p>
                        <select id="positionSelect" name="position" style="display:none;">
                            <option value="Teacher 1">Teacher 1</option>
                            <option value="Teacher 2">Teacher 2</option>
                            <option value="Teacher 3">Teacher 3</option>
                            <option value="Teacher 4">Teacher 4</option>
                            <option value="Teacher 5">Teacher 5</option>
                            <option value="Teacher 6">Teacher 6</option>
                            <option value="Teacher 7">Teacher 7</option>
                            <option value="Master Teacher 1">Master Teacher 1</option>
                            <option value="Master Teacher 2">Master Teacher 2</option>
                            <option value="Master Teacher 3">Master Teacher 3</option>
                            <option value="Master Teacher 4">Master Teacher 4</option>
                            <option value="Master Teacher 5">Master Teacher 5</option>
                            <option value="Principal 1">Principal 1</option>
                            <option value="Principal 2">Principal 2</option>
                            <option value="Principal 3">Principal 3</option>
                            <option value="Principal 4">Principal 4</option>
                            <option value="Principal 5">Principal 5</option>
                        </select>
                        <p class="advisory">Class Advisory</p>
                        <!-- <table class="profile-info">
                            <tr>
                                <th>Section</th>
                                <th>Grade Level</th>
                                <th>Total Number of Students</th>
                            </tr>
                            <tr>
                                <td>Mahogany</td>
                                <td>Grade 1</td>
                                <td>22</td>
                            </tr>
                        </table>
                        <p class="subject-handled">Subject(s) Handled</p>
                        <table class="profile-info">
                            <tr>
                                <th>Subject</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>Total Number of Students</th>
                            </tr>
                            <tr>
                                <td>English</td>
                                <td>Grade 3</td>
                                <td>Narra</td>
                                <td>23</td>
                            </tr>
                            <tr>
                                <td>Math</td>
                                <td>Grade 1</td>
                                <td>Mahogany</td>
                                <td>22</td>
                            </tr>
                            <tr>
                                <td>Science</td>
                                <td>Grade 2</td>
                                <td>Acacia</td>
                                <td>25</td>
                            </tr>
                        </table> -->
                        <p class="government-id">Government ID(s)</p>
                        <table class="profile-info">
                            <tr>
                                <th>Employee Number</th>
                                <th>Philhealth Number</th>
                                <th>TIN</th>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                        $teacherInfo = new adminTeacherInformationView();
                                        $teacherInfo->displayEmployeeNumber();
                                        ?>
                                </td>
                                <td>
                                    <?php
                                        $teacherInfo = new adminTeacherInformationView();
                                        $teacherInfo->displayPhilhealthNumber();
                                        ?>
                                </td>
                                <td>
                                    <?php
                                        $teacherInfo = new adminTeacherInformationView();
                                        $teacherInfo->displayTIN();
                                        ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>            
            </form>
      </div>
  </div>
</body>
<script src="../assets/js/admin/admin-teacher-info.js"defer></script>
</html>



