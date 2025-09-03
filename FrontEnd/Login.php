<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <script src="./assets/js/login-validation.js"></script> 
    <link rel="icon" href="../favicon.ico">
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

        <div class = "page-title">
             <h3>Log-In</h3>
             <BR>
             <hr>
        </div>

       <div class="form-container">
            <br>
            <div class="error-msg">
                <span id="em-login"> Error Message Here </span>
            </div>
           <form id="login-form" action="../server_side/common/post_login_verify.php" method="post">
               <div class="box">
                   <BR>
                   <label for="phone_number" style="color: white;  font-family: Baloo-Thambi-2;  font-size: .8em;" > Phone Number</label>
                   <input type="text" id="phone_number" name="phone_number" placeholder="09xx xxx xxxx" required>
                   <BR>
                   <label for="password" style="color: white; margin-bottom: 2em; font-family: Baloo-Thambi-2;  font-size: .8em; ">Password</label>
                   <input type="password" id="password" name="password" placeholder="Enter password here" required>
                </div>
                <div class="wrap">
                    <button type="submit">
                        Log In
                    </button>
                </div>
            </form>
        </div>
        
        <p><span style="color: white;">Don't have an account?</span>
            <a href="./Registration.php" class="register-link">
                Create a New Account
            </a>
        </p>
    </div>

    <div class="vlorange"></div>
    <div class="vlyellow"></div>

    <div id="img-container">
        <img src="./assets/imgs/teacher.jpg" 
        alt="student" id="student">
    </div>


</body>
</html>
