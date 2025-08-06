<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminSubjectsModel.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $subjectsQuery = new adminSubjectsModel();

        $subjectName = $_POST['subject-name'];

        if (empty($subjectName)) {
            echo"<script> 
                alert('Subject name cannot be empty'); 
                window.location.href = '../adminPages/Admin_Subjects.php'
            </script>";
            exit();
        }
        
        $convertedName = strtoupper($subjectName);
        
        // Check if we have multiple grade levels (checkboxes)
        if (isset($_POST['levels']) && is_array($_POST['levels'])) {
            $success = true;
            
            // Each checkbox value contains a grade level ID
            foreach($_POST['levels'] as $gradeLevelId) {
                $result = $subjectsQuery->insertSubjectAndLevel($convertedName, (int)$gradeLevelId);
                if ($result !== "succesfully inserted") {
                    $success = false;
                    echo "Failed to insert subject for grade level $gradeLevelId: $result";
                    break;
                }
            }
            
            if ($success) {
                header("Location: ../adminPages/Admin_Subjects.php");
                exit();
            }
        }
        // Single grade level from select dropdown
        else if (isset($_POST['subject-level'])) {
            $subjectLevel = (int)$_POST['subject-level'];
            $result = $subjectsQuery->insertSubjectAndLevel($convertedName, $subjectLevel);
            
            if ($result === "succesfully inserted") {
                header("Location: ../adminPages/Admin_Subjects.php");
                exit();
            }
            else {
                echo "Failed to insert subject and level: $result";
            }
        }
        else {
            echo "<script> 
                alert('Please select a grade level'); 
                window.location.href = '../adminPages/Admin_Subjects.php'
            </script>";
            exit();
        }
    }
}
catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
