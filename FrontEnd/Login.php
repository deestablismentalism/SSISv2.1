<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>School Portal</title>
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/login.css" />
  <link rel="stylesheet" href="./assets/css/fonts.css" />
  <link rel="stylesheet" href="./assets/css/loader.css" />
  <link rel="stylesheet" href="./assets/css/notifications.css">
  <script src="./assets/js/loader.js"></script>
  <script src="./assets/js/login-validation.js"></script> 
  <script src="./assets/js/notifications.js"></script>

</head>

<body>
    <?php
        include './pages/loader.php';
    ?>
    <?php
        include_once './pages/notifications.php'
    ?>
  <div class="login-container">
    <!-- LEFT ILLUSTRATION -->
    <div class="login-illustration">
      <img src="./assets/imgs/users-login.png" alt="Users Illustration" />
    </div>

    <!-- RIGHT LOGIN FORM -->
    <div class="login-form">
      <h1>Welcome!</h1>
      <p class="subtitle">Sign in to your Account</p>

     <form id="login-form" action="..\BackEnd\common\postLoginVerify.php" method="post">
        <div class="input-group">
          <input type="text" id="phone_number" name="phone_number" placeholder=" " required />
          <label for="phone_number">Phone Number</label>
        </div>

        <div class="input-group">
          <input type="password" id="password" name="password" placeholder=" " required />
          <label for="password">Password</label>
        </div>


        <button type="submit" class="btn-primary">Log In <span style=" place-items: center;"></span></button>
        <br>
        <br>

        <p><span style="color: black;">Don't have an account?</span>
            <a href="./Registration.php" class="register-link">
                Create a New Account
            </a>
        </p>



      </form>
    </div>
  </div>
</body>
</html>
