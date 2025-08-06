
    <?php 
        include_once './admin_base_designs.php';
        require_once __DIR__ . '/../../../BackEnd/admin/adminAllEnrolleesView.php';
    ?>
      <title>SSIS-Admin All Enrollees</title>
      <link rel="stylesheet" href="../../assets/css/admin/admin-all-enrollees.css">
</head>
<body>
            <div class="admin-all-enrollees-content">
                <div class="enrollment-access">
                    <div class="admin-all-enrollees-header">
                        <div class="header-left">
                            <div class="count-display">
                                Total Enrollees: <span class="count-number">
                                <?php
                                    $countView = new adminAllEnrolleesView();
                                    $countView->displayCount();
                                ?>
                                </span>
                            </div>
                        </div>
                        <div class="header-right">
                            <input type="text" id="search" class="search-box" placeholder="Search by name, LRN, grade level, or status...">
                        </div>
                    </div>
                    <div class="menu-content">
                        <table class="enrollments">
                            <thead>
                                <tr>
                                    <th>Student LRN</th>
                                    <th>Student Name</th>
                                    <th>Grade Level</th>
                                    <th>Enrollment Status</th>
                                    <th>Guardian Name</th>
                                    <th>Contact Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="query-table">
                                <?php      
                                $enrollmentStatusView = new adminAllEnrolleesView();
                                $enrollmentStatusView->displayAllEnrollees();
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="enrolleeModal" class="modal">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
            </div>
</div>  
</body>
<script src="../../assets/js/admin/admin-all-enrollees.js" defer></script>
</html>