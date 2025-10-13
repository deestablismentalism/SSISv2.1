<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/change-password.css">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <link rel="stylesheet" href="/SSISV2.1/FrontEnd/assets/css/notifications.css">
    <script src="./assets/js/change-password.js" defer></script>
    <script src="/SSISV2.1/FrontEnd/assets/js/notifications.js"></script>
</head>
<body>
    <?php
        include_once "pages/loader.php";
    ?>
    <div class="bg-image">
        <div class="blurred-background"></div>
            <div class="form">
                <h2 >Change Password</h2>
                <form id="change-password-form" class="overlay-form" action="../server_side/post_change_password.php" method="post"><br>
                    <input type="password" id="old-password" name="old-password" placeholder="Old Password" required><br>
                    <input type="password" id="new-password" name="new-password" placeholder="New Password" required><br>   
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm New Password" required><br>
                    <button type="submit" class="submit" style="center">Change Password</button>
                    <a onclick="history.back()" class="back">Go Back</a>
                </form>
            </div>
    </div>
</body>
</html>