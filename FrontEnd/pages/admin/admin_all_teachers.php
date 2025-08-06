<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 

    <link rel="stylesheet" href="../../assets/css/admin/admin-all-teachers.css">
    <?php
        include_once __DIR__ . '/./admin_base_designs.php'; 
    ?>
        <!--START OF THE MAIN CONTENT-->
        <div class="content">
            <div class="table-wrapper">
                <p class="all-teachers-title">All Teachers</p>
                <table class="table-teachers">
                    <?php
                        require_once __DIR__ .'/../../../BackEnd/admin/adminTeachersView.php';
                        $table = new adminTeachersView();
                        $table->displayAllTeachers();
                    ?>
                </table>
                <?php
                    if ($_SESSION['Staff']['Staff-Type'] == 1){
                        echo '<a href="./admin_staff_registration.php" class="btn btn-primary register">Register a New Teacher</a>';
                    }
                ?>
            </div>
        </div>
  </div>
</body>
</html>
