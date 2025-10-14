<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration</title>

  <!-- CSS Files -->
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/Registration.css" />
  <link rel="stylesheet" href="./assets/css/registration_form_errors.css" />
  <link rel="stylesheet" href="./assets/css/fonts.css" />

  <!-- JS -->
  <script src="./assets/js/registration.js" defer></script>
</head>

<body>
    <?php
        include './pages/loader.php';
    ?>

  <div class="login-container">
    <!-- LEFT ILLUSTRATION -->
   <div id="img-container">
      <img src="./assets/imgs/users-login.png" id="image" />     
    </div>

    <!-- RIGHT LOGIN FORM -->

    <!-- Registration Form -->
    <div class="container">
        <form id="registration-form" action="../server_side/post_registration_form.php" method="post">
            <div class="user-details">
                <div class="page-title">
                    <h3>Welcome!</h3>
                    <p class="subtitle">Sign in to your Account</p>
                </div>

                <!-- Guardian First Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-First-Name" id="guardian-first-name" placeholder=" " required />
                    <span class="details">Enrollee's Guardian First Name</span>
                    <div class="error-message">
                        <span class="em-guardian-first-name"></span>
                    </div>
                </div>

                <!-- Guardian Middle Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-Middle-Name" id="guardian-middle-name" placeholder=" " required />
                    <span class="details">Enrollee's Guardian Middle Name</span>
                    <div class="error-message">
                        <span class="em-guardian-middle-name"></span>
                    </div>
                </div>

                <!-- Guardian Last Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-Last-Name" id="guardian-last-name" placeholder=" " required />
                    <span class="details">Enrollee's Guardian Last Name</span>
                    <div class="error-message">
                        <span class="em-guardian-last-name"></span>
                    </div>
                </div>

                <!-- Guardian Contact Number -->
                <div class="input-box">
                    <input type="text" name="Contact-Number" id="contact-number" placeholder=" " required />
                    <span class="details">Enrollee's Guardian Contact Number</span>
                    <div class="error-message">
                        <span class="contact-number"></span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="button">
                <button type="submit" name="submit" class="btn submit-btn">Register</button>
                </div>
            </div>
            <!-- Sign In Link -->
            <p>
              Already have an account?
              <a href="Login.php" class="signin-link" style="text-decoration: none;">Sign In.</a>
            </p>
        </form>
    </div>


</body>
</html>
