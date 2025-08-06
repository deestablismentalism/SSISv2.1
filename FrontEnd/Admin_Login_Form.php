<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="./assets/css/admin_login_form.css">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <script src="./assets/js/admin-login.js" defer></script> 
</head>
<body>
    <div class="bg-image">
    <div class="blurred-background"></div>
        <div class="form">
            <form class="overlay-form" method="POST" action="../server_side/admin/post_admin_login.php" id ="admin-login-form">
                <div class="title-container">
                    <h2 style="text-align: center;">Teacher LogIn</h2>
                </div>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">LogIn</button>
                <a href="login_form.php" class="login-back" style="center" >Back to Login</a>
            </form>
        </div>
    </div>
</body>

</html>
