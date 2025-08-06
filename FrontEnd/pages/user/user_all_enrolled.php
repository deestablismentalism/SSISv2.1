<?php 
    require_once __DIR__ . '/./user_base_designs.php'; 
    require_once __DIR__ . '/../../../BackEnd/user/userAllStudentsView.php';
?>;
<link rel="stylesheet" href="../../assets/user/user-all-enrolled.css">
</head>
<body> 

    <div class="user-all-enrolled-content">
        <div class="table-wrapper">
            <p class="table-title">  All Enrolled </p>
            <table class="user-all-enrolled-table">
                <tbody>
                    <?php 
                        $data = new userAllStudentsView();
                        $data->displayAllStudents();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>