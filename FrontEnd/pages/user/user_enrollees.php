<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="../../assets/css/user/user-enrollees.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
    <?php
        include './user_base_designs.php'; 
    ?>
</head>
<body>
    <!--START OF THE MAIN CONTENT-->
    <div class="content" id="content">
        <div class="shadow-container">
            <div class="wrapper">
                <p class = "title"> Enrollment Forms Submitted </p> <br>
                <div class="table-container">
                    <table id="user-enrollees-table"> 
                    <tbody> 
                            <?php
                            include_once __DIR__ . '/../../../BackEnd/user/userEnrolleesView.php';
                            $enrollee = new displayEnrollmentForms();
                            $enrollee->displaySubmittedForms();
                            ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../../assets/js/user/user-enrollees.js"defer></script>
</html>