<!DOCTYPE html>
<html>

<head>
    <meta ssrset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/Registration.css">
    <link rel="stylesheet" href="./assets/css/registration_form_errors.css">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <script src="./assets/js/registration.js"></script> 

</head>

<body>  
    <div class="main">
        <div class="header">
            <h2>Lucena South II Elementary School</h2>
        </div>

        <div id="logo-container">
            <img src="./assets/imgs/logo.jpg" 
            alt="School Logo" id="school-logo">
        </div>

        <div class="page-title">
          <h3>Registration</h3>
          <BR>
          <hr>
        </div>

        <div class="container">

            <!-- Registration form -->
            <form id="registration-form" method="post">
                <div class="user-details">
                    <!-- Input for users first name -->
                    <!-- <div class="input-box">
                        <span class="details">First Name</span>
                        <input type="text" placeholder="Ex: Juan Felipe" required>
                    </div> -->
                    <!-- Input for users last name -->
                    <div class="input-box">
                        <span class="details">Enrollee's Guardian First Name</span>
                        <div class="error-message">
                            <span class="em-guardian-first-name"></span>
                        </div>
                        <input type="text" name="Guardian-First-Name" id="guardian-first-name" placeholder="Ex: Dela Cruz" required>
                    </div>
                    <!-- Input for guardian's first name -->
                    <div class="input-box">
                        <span class="details">Enrollee's Guardian Middle Name</span>
                        <div class="error-message">
                            <span class="em-guardian-middle-name"></span>
                        </div>
                        <input type="text" name="Guardian-Middle-Name" id="guardian-middle-name"   placeholder="Ex: Juan Felipe" required>
                    </div>
                    <!-- Input for guardian's last name -->
                    <div class="input-box">
                        <span class="details">Enrollee's Guardian Last Name</span>
                        <div class="error-message">
                            <span class="em-guardian-last-name"></span>
                        </div>
                        <input type="text" name="Guardian-Last-Name" id="guardian-last-name" placeholder="Ex: Dela Cruz" required>
                    </div>
                    <!-- Input for guardians contact number -->
                    <div class="input-box cn">
                        <span class="details">Contact Number</span>
                        <div class="error-message">
                            <span class="em-contact-number"></span>
                        </div>
                        <input type="text" name="Contact-Number" id="contact-number" placeholder="Ex: 09xx xxx xxxx" required>
                    </div>  
                    <!-- Submit button -->                      
                    <div class="button">
                        <button type="submit" name="submit" class="btn submit-btn">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Create Account  -->     
        <p>Already have an existing account?
            <a href="../client_side/login_form.php" class="signin-link" style="text-decoration: none;">
                Sign In.
            </a>
        </p>
   </div>

    <!-- two Lines  -->  
    <div class="vlorange"></div>
    <div class="vlyellow"></div>

    <div id="img-container">
        <img src="./assets/imgs/teacher.jpg" alt="student" id="student">
    </div>
   <!-- <script src="../js/registration-validation.js"></script> -->
</body>
</html>
