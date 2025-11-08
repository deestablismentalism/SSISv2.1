<?php 
ob_start();
$pageCss= '<link rel="stylesheet" href="../../assets/css/admin/admin-edit-student.css">';
    require_once __DIR__ . '/../../../BackEnd/admin/models/adminStudentsModel.php';
    
    // Check if student ID is provided
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo "<script>alert('Student ID is required'); window.location.href = 'Admin_All_Students.php';</script>";
        exit;
    }
    
    $studentId = intval($_GET['id']);
    $studentModel = new adminStudentsModel();
    $student = $studentModel->getStudentById($studentId);
    
    if (!$student) {
        echo "<script>alert('Student not found'); window.location.href = 'Admin_All_Students.php';</script>";
        exit;
    }

    // Handle form submission
    $errorMessage = '';
    $successMessage = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $studentModel = new adminStudentsModel();
        $result = $studentModel->updateStudent($_POST);
        
        if ($result) {
            $successMessage = 'Student updated successfully!';
            $student = $studentModel->getStudentById($studentId); // Refresh data
        } else {
            $errorMessage = 'Error updating student: ' . $result;
        }
    }
    
    // Get sections for dropdown
    $sections = $studentModel->getSectionsByGradeLevel($student['Grade_Level_Id']);
    
    // Get additional student data (assuming these fields exist in your database)
    $additionalInfo = $studentModel->getAdditionalStudentInfo($studentId);
?>
    <div class="main-content">
        <div class="content">
            <div class="page-title">
                <div class="title">
                    <h2>Edit Student Information</h2>
                    <a href="admin_all_students.php" class="back-button"><img src="../../assets/imgs/arrow-left-solid.svg"></a>
                </div>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-error">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="admin_edit_student.php?id=<?php echo $studentId; ?>">
                    <input type="hidden" name="student_id" value="<?php echo $student['Student_Id']; ?>">
                    <input type="hidden" name="enrollee_id" value="<?php echo $student['Enrollee_Id']; ?>">
                    
                    <div class="form-section">
                        <h3>Personal Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="psa_number">PSA Birth Certificate Number</label>
                                <input type="text" id="psa_number" name="psa_number" value="<?php echo htmlspecialchars($additionalInfo['Psa_Number'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['Student_First_Name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($student['Student_Middle_Name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['Student_Last_Name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="name_extension">Name Extension</label>
                                <input type="text" id="name_extension" name="name_extension" value="<?php echo htmlspecialchars($additionalInfo['Student_Extension'] ?? ''); ?>" placeholder="Jr., Sr., III, etc.">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="lrn">Learner Reference Number (LRN)</label>
                                <input type="text" id="lrn" name="lrn" value="<?php echo htmlspecialchars($student['Learner_Reference_Number']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['Student_Email']); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="birthdate">Birth Date</label>
                                <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($additionalInfo['Birth_Date'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($additionalInfo['Age'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender">
                                    <option value="">-- Select Gender --</option>
                                    <option value="Male" <?php echo (isset($additionalInfo['Sex']) && $additionalInfo['Sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo (isset($additionalInfo['Sex']) && $additionalInfo['Sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="religion">Religion</label>
                                <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($additionalInfo['Religion'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="native_language">Native Language</label>
                                <input type="text" id="native_language" name="native_language" value="<?php echo htmlspecialchars($additionalInfo['Native_Language'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Academic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="grade_level">Grade Level</label>
                                <select id="grade_level" name="grade_level" required>
                                    <?php 
                                        $gradeLevels = $studentModel->getAllGradeLevels();
                                        foreach ($gradeLevels as $level) {
                                            $selected = ($level['Grade_Level_Id'] == $student['Grade_Level_Id']) ? 'selected' : '';
                                            echo "<option value='{$level['Grade_Level_Id']}' $selected>{$level['Grade_Level']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="section">Section</label>
                                <select id="section" name="section">
                                    <option value="">-- No Section --</option>
                                    <?php 
                                        foreach ($sections as $section) {
                                            $selected = ($section['Section_Id'] == $student['Section_Id']) ? 'selected' : '';
                                            echo "<option value='{$section['Section_Id']}' $selected>{$section['Section_Name']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="1" <?php echo ($student['Student_Status'] == 1) ? 'selected' : ''; ?>>Active</option>
                                    <option value="2" <?php echo ($student['Student_Status'] == 2) ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="3" <?php echo ($student['Student_Status'] == 3) ? 'selected' : ''; ?>>Dropped</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="last_school">Last School Attended</label>
                                <input type="text" id="last_school" name="last_school" value="<?php echo htmlspecialchars($additionalInfo['Last_School'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_school_id">Last School ID</label>
                                <input type="text" id="last_school_id" name="last_school_id" value="<?php echo htmlspecialchars($additionalInfo['Last_School_ID'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="last_school_address">Last School Address</label>
                                <input type="text" id="last_school_address" name="last_school_address" value="<?php echo htmlspecialchars($additionalInfo['Last_School_Address'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="last_school_type">Last School Type</label>
                                <select id="last_school_type" name="last_school_type">
                                    <option value="">-- Select School Type --</option>
                                    <option value="Public" <?php echo (isset($additionalInfo['Last_School_Type']) && $additionalInfo['Last_School_Type'] == 'Public') ? 'selected' : ''; ?>>Public</option>
                                    <option value="Private" <?php echo (isset($additionalInfo['Last_School_Type']) && $additionalInfo['Last_School_Type'] == 'Private') ? 'selected' : ''; ?>>Private</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="last_grade_completed">Last Grade Level Completed</label>
                                <input type="text" id="last_grade_completed" name="last_grade_completed" value="<?php echo htmlspecialchars($additionalInfo['Last_Grade_Completed'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="last_year_completed">Last School Year Completed</label>
                                <input type="text" id="last_year_completed" name="last_year_completed" value="<?php echo htmlspecialchars($additionalInfo['Last_Year_Completed'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3>Special Needs Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="has_special_needs">Has Special Needs?</label>
                                <select id="has_special_needs" name="has_special_needs">
                                    <option value="0" <?php echo (isset($additionalInfo['Has_Special_Needs']) && $additionalInfo['Has_Special_Needs'] == 0) ? 'selected' : ''; ?>>No</option>
                                    <option value="1" <?php echo (isset($additionalInfo['Has_Special_Needs']) && $additionalInfo['Has_Special_Needs'] == 1) ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="special_needs_details">Special Needs Details</label>
                                <input type="text" id="special_needs_details" name="special_needs_details" value="<?php echo htmlspecialchars($additionalInfo['Special_Needs_Details'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="has_assistive_tech">Uses Assistive Technology?</label>
                                <select id="has_assistive_tech" name="has_assistive_tech">
                                    <option value="0" <?php echo (isset($additionalInfo['Has_Assistive_Tech']) && $additionalInfo['Has_Assistive_Tech'] == 0) ? 'selected' : ''; ?>>No</option>
                                    <option value="1" <?php echo (isset($additionalInfo['Has_Assistive_Tech']) && $additionalInfo['Has_Assistive_Tech'] == 1) ? 'selected' : ''; ?>>Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assistive_tech_details">Assistive Technology Details</label>
                                <input type="text" id="assistive_tech_details" name="assistive_tech_details" value="<?php echo htmlspecialchars($additionalInfo['Assistive_Tech_Details'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="save-button">Save Changes</button>
                        <a href="Admin_All_Students.php" class="cancel-button">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to update sections dropdown when grade level changes
        document.getElementById('grade_level').addEventListener('change', function() {
            const gradeLevelId = this.value;
            
            // Make an AJAX request to get sections for the selected grade level
            fetch(`../server_side/getSectionsByGradeLevel.php?grade_level_id=${gradeLevelId}`)
                .then(response => response.json())
                .then(data => {
                    const sectionDropdown = document.getElementById('section');
                    
                    // Clear existing options
                    sectionDropdown.innerHTML = '<option value="">-- No Section --</option>';
                    
                    // Add new options
                    if (data.success && data.sections.length > 0) {
                        data.sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.Section_Id;
                            option.textContent = section.Section_Name;
                            sectionDropdown.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching sections:', error);
                });
        });

        // Calculate age based on birthdate
        document.getElementById('birthdate').addEventListener('change', function() {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const monthDiff = today.getMonth() - birthdate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
        });
    </script>
<?php 
    $pageContent = ob_get_clean();
    require_once __DIR__ . '/./admin_base_designs.php';
?>