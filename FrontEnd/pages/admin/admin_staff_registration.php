<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration</title>
    <link rel="stylesheet" href="../../assets/css/admin/admin-staff-registration.css">
</head>
<body>
    <div class="bg-image">
    <div class="blurred-background"></div>
        <div class="form">
            <h2 style="text-align: center;">Register New Staff</h2>
        <form class="overlay-form" action="../server_side/post_staff_registration.php" method="POST">

            <input type="text" name="Staff_First_Name" id="Staff_First_Name" placeholder="First Name" required><br><br>

            <input type="text" name="Staff_Middle_Name" id="Staff_Middle_Name" placeholder="Middle Name" ><br><br>

            <input type="text" name="Staff_Last_Name" id="Staff_Last_Name" placeholder="Last Name"  required><br><br>

            <input type="email" name="Staff_Email" id="Staff_Email" placeholder="Email Address"  required><br><br>

            <input type="text" name="Staff_Contact_Number" id="Staff_Contact_Number"  placeholder="Contact Number" required><br><br>

            <button type="submit" value="Register Staff">Register</button><br><br>
            
        </form>
    </div>
</body>
</html> 
