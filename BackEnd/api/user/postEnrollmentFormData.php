<?php
// AGGRESSIVE ERROR CATCHING
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../../debug.log');

// Catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Fatal Error: ' . $error['message'],
            'file' => $error['file'],
            'line' => $error['line']
        ]);
    }
});

session_start();
require_once __DIR__ .  '/../../user/models/userPostEnrollmentFormModel.php';

header('Content-Type: application/json');

try {
    error_log('=== ENROLLMENT FORM SUBMISSION STARTED ===');
    error_log('POST data: ' . print_r($_POST, true));
    error_log('FILES data: ' . print_r($_FILES, true));
    
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception('Invalid request method');
    }

    if (!isset($_SESSION['User']['User-Id'])) {
        throw new Exception('Unrecognized user. Session: ' . print_r($_SESSION, true));
    }

    $userId = $_SESSION['User']['User-Id'];
    error_log('User ID: ' . $userId);
    
    $enrollment_form = new userPostEnrollmentFormModel();
    error_log('Model instantiated successfully');
    
    // EDUCATIONAL INFORMATION
    $School_Year_Start = (int)($_POST['start-year'] ?? 0);
    $School_Year_End = (int)($_POST['end-year'] ?? 0);
    
    // FIX: Get the radio button value for bool-LRN (0 or 1), not the LRN itself
    $If_LRN_Returning = (int)($_POST['bool-LRN'] ?? 0);
    
    // FIX: Cast to string since database expects string
    $Enrolling_Grade_Level = (string)($_POST['grades-tbe'] ?? '');
    $Last_Grade_Level = !empty($_POST['last-grade']) ? (string)$_POST['last-grade'] : null;
    $Last_Year_Attended = !empty($_POST['last-year']) ? (int)$_POST['last-year'] : null;

    error_log("Educational Info - SY: $School_Year_Start-$School_Year_End, Grade: $Enrolling_Grade_Level, LRN Status: $If_LRN_Returning");

    // Validate educational information
    if (empty($School_Year_Start) || empty($School_Year_End)) {
        throw new Exception('School year start and end are required');
    }

    if (empty($Enrolling_Grade_Level)) {
        throw new Exception('Enrolling grade level is required');
    }

    // EDUCATIONAL BACKGROUND
    $Last_School_Attended = $_POST['lschool'] ?? "";
    $School_Id = (int)($_POST['lschoolID'] ?? 0);
    $School_Address = $_POST['lschoolAddress'] ?? "";
    $School_Type = $_POST['school-type'] ?? "";
    $Initial_School_Choice = userPostEnrollmentFormModel::INITIAL_SCHOOL_NAME;
    $Initial_School_Id = userPostEnrollmentFormModel::INITIAL_SCHOOL_ID;
    $Initial_School_Address = userPostEnrollmentFormModel::INITIAL_SCHOOL_ADDRESS;

    error_log("Last School: $Last_School_Attended, ID: $School_Id, Type: $School_Type");

    // DISABILITY INFORMATION
    $Have_Special_Condition = (int)($_POST['sn'] ?? 0);
    $Special_Condition = !empty($_POST['boolsn']) ? $_POST['boolsn'] : null;
    $Have_Assistive_Tech = (int)($_POST['at'] ?? 0);
    $Assistive_Tech = !empty($_POST['atdevice']) ? $_POST['atdevice'] : null;

    // ENROLLEE ADDRESS
    $House_Number = (int)($_POST['house-number'] ?? 0);
    $Subd_Name = $_POST['subdivision'] ?? "";
    $Brgy_Code = (int)($_POST['barangay'] ?? 0);
    $Municipality_Code = (int)($_POST['city-municipality'] ?? 0);
    $Province_Code = (int)($_POST['province'] ?? 0);
    $Region_Code = (int)($_POST['region'] ?? 0);
    $Region = $_POST['region-name'] ?? "";
    $Province_Name = $_POST['province-name'] ?? "";
    $Municipality_Name = $_POST['city-municipality-name'] ?? "";
    $Brgy_Name = $_POST['barangay-name'] ?? "";

    error_log("Address: $Brgy_Name, $Municipality_Name, $Province_Name, $Region");

    // ENROLLEE PARENTS INFORMATION
    $Father_First_Name = $_POST['Father-First-Name'] ?? "";
    $Father_Last_Name = $_POST['Father-Last-Name'] ?? "";
    $Father_Middle_Name = !empty($_POST['Father-Middle-Name']) ? $_POST['Father-Middle-Name'] : null;
    $Father_Educational_Attainment = $_POST['F-highest-education'] ?? "";
    $Father_Contact_Number = $_POST['F-Number'] ?? "";
    $FIf_4Ps = $_POST['fourPS'] === 'yes' ? 1 : 0;

    $Mother_First_Name = $_POST['Mother-First-Name'] ?? "";
    $Mother_Last_Name = $_POST['Mother-Last-Name'] ?? "";
    $Mother_Middle_Name = !empty($_POST['Mother-Middle-Name']) ? $_POST['Mother-Middle-Name'] : null;
    $Mother_Educational_Attainment = $_POST['M-highest-education'] ?? "";
    $Mother_Contact_Number = $_POST['M-Number'] ?? "";
    $MIf_4Ps = $_POST['fourPS'] === 'yes' ? 1 : 0;

    $Guardian_First_Name = $_POST['Guardian-First-Name'] ?? "";
    $Guardian_Last_Name = $_POST['Guardian-Last-Name'] ?? "";
    $Guardian_Middle_Name = !empty($_POST['Guardian-Middle-Name']) ? $_POST['Guardian-Middle-Name'] : null;
    $Guardian_Educational_Attainment = $_POST['G-highest-education'] ?? "";
    $Guardian_Contact_Number = $_POST['G-Number'] ?? "";
    $GIf_4Ps = $_POST['fourPS'] === 'yes' ? 1 : 0;

    error_log("Parents - F: $Father_First_Name $Father_Last_Name, M: $Mother_First_Name $Mother_Last_Name");

    // ENROLLEE INFORMATION
    $Student_First_Name = $_POST['fname'] ?? "";
    $Student_Middle_Name = !empty($_POST['mname']) ? $_POST['mname'] : null;
    $Student_Last_Name = $_POST['lname'] ?? "";
    $Student_Extension = !empty($_POST['extension']) ? $_POST['extension'] : null;
    
    // FIX: Handle LRN properly based on radio button
    $Learner_Reference_Number = null;
    if ($If_LRN_Returning === 1 && !empty($_POST['LRN'])) {
        $lrnInput = trim($_POST['LRN']);
        if (is_numeric($lrnInput)) {
            $Learner_Reference_Number = (int)$lrnInput;
        }
    }
    
    $Psa_Number = (int)($_POST['PSA-number'] ?? 0);
    $Birth_Date = $_POST['bday'] ?? "";
    $Age = (int)($_POST['age'] ?? 0);
    $Sex = $_POST['gender'] ?? "";
    $Religion = $_POST['religion'] ?? "";
    $Native_Language = $_POST['language'] ?? "";
    $If_Cultural = (int)($_POST['group'] ?? 0);
    $Cultural_Group = !empty($_POST['community']) ? $_POST['community'] : null;
    $Student_Email = !empty($_POST['email']) ? $_POST['email'] : "";
    $Enrollment_Status = 3;

    error_log("Student: $Student_First_Name $Student_Last_Name, LRN: " . ($Learner_Reference_Number ?? 'NULL') . ", PSA: $Psa_Number");

    // Check duplicates
    if ($Learner_Reference_Number !== null) {
        $isMatchingLrn = $enrollment_form->checkLRN($Learner_Reference_Number, null);
        if($isMatchingLrn) {
            throw new Exception('This LRN is already registered in the database');
        }
    }
    
    if ($Psa_Number > 0) {
        $isMatchingPsa = $enrollment_form->checkPSA($Psa_Number, null);
        if ($isMatchingPsa) {
            throw new Exception('This PSA number is already registered in the database');
        }
    } else {
        throw new Exception('PSA number is required');
    }

    // Image handling
    if (!isset($_FILES['psa-image']) || $_FILES['psa-image']['error'] !== 0) {
        throw new Exception('PSA image is required. Error code: ' . ($_FILES['psa-image']['error'] ?? 'No file'));
    }

    $uploadDirectory = __DIR__ . "/../../../ImageUploads/" . date("Y") . "/";
    if (!is_dir($uploadDirectory)) {
        if (!mkdir($uploadDirectory, 0777, true)) {
            throw new Exception('Failed to create upload directory: ' . $uploadDirectory);
        }
    }

    $image = $_FILES['psa-image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];

    $imageExt = explode('.', $imageName);
    $imageActualExt = strtolower(end($imageExt));
    $allowedTypes = ['jpg', 'jpeg', 'png'];

    if (!in_array($imageActualExt, $allowedTypes)) {
        throw new Exception('Invalid image type. Only JPG, JPEG, and PNG are allowed.');
    }

    $time = time();
    $randomString = bin2hex(random_bytes(5));
    $uniqueName = $userId . "-" . $time . "-" . $randomString;
    $filename = $uniqueName . "." . $imageActualExt;
    $directory = $uploadDirectory . $filename;

    if (!move_uploaded_file($imageTmpName, $directory)) {
        throw new Exception('Failed to upload image to: ' . $directory);
    }

    error_log('Image uploaded successfully: ' . $filename);
    error_log('About to call insert_enrollee...');

    // Insert the values into the database
    $result = $enrollment_form->insert_enrollee(
        $userId, $School_Year_Start, $School_Year_End, $If_LRN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended,
        $Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address,
        $Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech,
        $House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code,
        $Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Educational_Attainment, $Father_Contact_Number, $FIf_4Ps,
        $Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps,
        $Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps,
        $Student_First_Name, $Student_Last_Name, $Student_Middle_Name, $Student_Extension, $Learner_Reference_Number, $Psa_Number, $Birth_Date, $Age, $Sex, $Religion,
        $Native_Language, $If_Cultural, $Cultural_Group, $Student_Email, $Enrollment_Status, $filename, $directory
    );

    error_log('insert_enrollee returned: ' . ($result ? 'true' : 'false'));

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Enrollment form submitted successfully!'
        ]);
    } else {
        throw new Exception('Failed to insert enrollment data');
    }
} 
catch (Exception $e) {
    error_log('=== ENROLLMENT ERROR ===');
    error_log('Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile());
    error_log('Line: ' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>