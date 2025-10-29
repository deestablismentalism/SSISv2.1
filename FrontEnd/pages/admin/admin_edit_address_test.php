<?php
ob_start();

$pageTitle = 'SSIS - Edit Address';
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-edit-address-test.css">'
?>
      <!--START OF THE MAIN CONTENT-->
        <div class="content">
            <form action="../server_side/post_edit_staff_information.php" method="POST">
                <p class="title">Edit Address</p>           
                <!--DO NOT REMOVE!! For Switch case statement-->
                <input type="hidden" name="form_type" value="update_address">

                <label for="House_Number">House Number:</label>
                <input type="text" id="House_Number" name="House_Number" required>

                <label for="Subd_Name">Subdivision Name:</label>
                <input type="text" id="Subd_Name" name="Subd_Name" required>

                <label for="Brgy_Name">Barangay Name:</label>
                <input type="text" id="Brgy_Name" name="Brgy_Name" required>

                <label for="Municipality_Name">Municipality:</label>
                <input type="text" id="Municipality_Name" name="Municipality_Name" required>

                <label for="Province_Name">Province:</label>
                <input type="text" id="Province_Name" name="Province_Name" required>

                <label for="Region">Region:</label>
                <input type="text" id="Region" name="Region" required>

                <div class="form-buttons">
                    <input type="submit" value="Update">  
                    <a href="./edit_information_links.php" class="back">Go Back</a>
                </div>
            </form>
        </div>
    </div>

<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>
