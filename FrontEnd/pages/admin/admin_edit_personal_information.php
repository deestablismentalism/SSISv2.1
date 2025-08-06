<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <link rel="stylesheet" href="../../assets/css/admin/admin-edit-personal-information.css">
    <?php
        include_once __DIR__ . '/./admin_base_designs.php'; 
    ?>

      <!--START OF THE MAIN CONTENT-->
      <div class="content">
        <form action="../server_side/post_edit_staff_information.php" method="POST">
          <p class="title">Edit Personal Information</p>
          <!-- Hidden input to identify the form type -->
          <input type="hidden" name="form_type" value="update_information">

          <label for="Staff_First_Name">First Name:</label>
          <input type="text" id="Staff_First_Name" name="Staff_First_Name" required>

          <label for="Staff_Middle_Name">Middle Name:</label>
          <input type="text" id="Staff_Middle_Name" name="Staff_Middle_Name"><br>

          <label for="Staff_Last_Name">Last Name:</label>
          <input type="text" id="Staff_Last_Name" name="Staff_Last_Name" required>

          <label for="Staff_Email">Email:</label>
          <input type="email" id="Staff_Email" name="Staff_Email" required>

          <label for="Staff_Contact_Number">Contact Number:</label>
          <input type="text" id="Staff_Contact_Number" name="Staff_Contact_Number" required>

          <div class="form-buttons">
              <input type="submit" value="Update">  
              <a href="./edit_information_links.php" class="back">Go Back</a>
          </div>
        </form>
      </div>
  </div>
<script src="../../assets/js/admin/admin-edit-staff-information.js"></script>

</body>
</html>
