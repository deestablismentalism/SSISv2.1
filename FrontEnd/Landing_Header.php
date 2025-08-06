<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <link rel="stylesheet" href="./assets/css/landing-header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <title>Document</title>
</head>
<body>
    <div class = "header">
        <div class = "header-content" style="text-align: center;">
            <div class = "header-left">
                <!-- <img src="../imgs/SSIS_Logo.png" alt="SSIS Logo" class="logo"> for logo--> 
                <p class = "header-title"> Lucena South II Elementary School </p>
            </div>
            <div class="header-right">
                <!-- will turn this into a href or a button, same with about, learn-more, and log-in + Test with JScript -->
                <a href = "./Index.php" class = "nav-link"> Home </a> 
                <span class = "separator"> | </span>
                <a href = "./About_Page.php" class = "nav-link"> About Us </a>
                <span class = "separator"> | </span>
                <a href = "./Learn_More_Page.php" class = "nav-link"> Learn More </a>
                <span class = "separator"> | </span>
                <a href = "./Login.php" class = "nav-link"> Log In </a> 
                <span class = "separator"> | </span>
                <a href = "./Registration.php" class = "nav-link"> Sign Up  </a>
            </div>
        </div>

        <!--MOBILE VERSION-->
        <div class = "header-content-mobile" style="text-align: center;">
            <div class = "top-header">
                <!-- <img src="../imgs/SSIS_Logo.png" alt="SSIS Logo" class="logo"> for logo--> 
                <p class = "header-title"> Lucena South II Elementary School </p>
            </div>
            <div class="bottom-header" style="font-size: 10px; gap: 9px; padding: 2px;">
                <!-- will turn this into a href or a button, same with about, learn-more, and log-in + Test with JScript -->
                <a href = "./Index.php" class = "nav-link" style=" margin: 3px; 0px"> Home </a>
                <span class = "separator"> | </span>
                <a href = "./About_Page.php" class = "nav-link" style=" margin: 3px; 0px"> About Us </a>
                <span class = "separator"> | </span>
                <a href = "./Learn_More_Page.php" class = "nav-link" style=" margin: 3px; 0px"> Learn More </a>
                <span class = "separator"> | </span>
                <a href = "./Login.php" class = "nav-link" style=" margin: 3px; 0px"> Log In </a> <!--not yet-->
            </div>
        </div>
    </div>
    <script src='./assets/js/landing-header.js' defer></script>
</body>
</html>