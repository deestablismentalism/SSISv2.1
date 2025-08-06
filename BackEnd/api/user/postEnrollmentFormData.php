<?php
session_start();
require_once __DIR__ .  '/../../user/models/userPostEnrollmentFormModel.php';

header('Content-Type: application/json');

try {
    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception('Invalid request method');
    }

    // Check for user session
    if (!isset($_SESSION['User']['User-Id'])) {
        throw new Exception('Unrecognized user');
    }

    $userId = $_SESSION['User']['User-Id'];
    $enrollment_form = new EnrollmentForm();
    
    // EDUCATIONAL INFORMATION
    $School_Year_Start = $_POST['start-year'] ?? null;
    $School_Year_End = $_POST['end-year'] ?? null;
    $If_LRN_Returning = $_POST['LRN'] ?? null;
    $Enrolling_Grade_Level = $_POST['grades-tbe'] ?? null;
    $Last_Grade_Level = $_POST['last-grade'] ?? null;
    $Last_Year_Attended = $_POST['last-year'] ?? null;

    // Validate educational information
    if (empty($School_Year_Start) || empty($School_Year_End)) {
        throw new Exception('School year start and end are required');
    }

    if (empty($Enrolling_Grade_Level)) {
        throw new Exception('Enrolling grade level is required');
    }

    // Validate year formats
    if (!is_numeric($School_Year_Start) || !is_numeric($School_Year_End)) {
        throw new Exception('School years must be numeric values');
    }

    if (!empty($Last_Year_Attended) && !is_numeric($Last_Year_Attended)) {
        throw new Exception('Last year attended must be a numeric value');
    }

    // Validate year ranges
    $currentYear = date('Y');
    if ($School_Year_Start < $currentYear || $School_Year_Start > ($currentYear + 1)) {
        throw new Exception('Invalid school year start');
    }

    if ($School_Year_End <= $School_Year_Start || $School_Year_End > ($currentYear + 2)) {
        throw new Exception('Invalid school year end');
    }

    if (!empty($Last_Year_Attended)) {
        if ($Last_Year_Attended > $currentYear) {
            throw new Exception('Last year attended cannot be in the future');
        }
    }

    // EDUCATIONAL BACKGROUND
    $Last_School_Attended = $_POST['lschool'] ?? "";
    $School_Id = $_POST['lschoolID'] ?? "";
    $School_Address = $_POST['lschoolAddress'] ?? "";
    $School_Type = $_POST['educational-choice'] ?? "";
    $Initial_School_Choice = $_POST['fschool'] ?? "";
    $Initial_School_Id = $_POST['fschoolID'] ?? "";
    $Initial_School_Address = $_POST['fschoolAddress'] ?? "";

    //  DISABILITY INFORMATION
    $Have_Special_Condition = $_POST['sn'] ?? "";
    $Special_Condition = $_POST['boolsn'] ?? "";
    $Have_Assistive_Tech = $_POST['at'] ?? "";
    $Assistive_Tech = $_POST['atdevice'] ?? "";

    //  EROLLEE ADDRESS
    $House_Number = $_POST['house-number'] ?? "";
    $Subd_Name = $_POST['subdivision'] ?? "";
    $Brgy_Code = $_POST['barangay'] ?? "";
    $Municipality_Code = $_POST['city-municipality'] ?? "";
    $Province_Code = $_POST['province'] ?? "";
    $Region_Code = $_POST['region'] ?? "";
    $Region = $_POST['region-name'] ?? "";
    $Province_Name = $_POST['province-name'] ?? "";
    $Municipality_Name = $_POST['city-municipality-name'] ?? "";
    $Brgy_Name = $_POST['barangay-name'] ?? "";

    // ENROLLEE PARENTS INFORMATION
    $Father_First_Name = $_POST['Father-First-Name'] ?? "";
    $Father_Last_Name = $_POST['Father-Last-Name'] ?? "";
    $Father_Middle_Name = $_POST['Father-Middle-Name'] ?? "";
    $Father_Parent_Type = "Father";
    $Father_Educational_Attainment = $_POST['F-highest-education'] ?? "";
    $Father_Contact_Number = $_POST['F-Number'] ?? "";
    $FIf_4Ps = $_POST['fourPS'] ?? "";

    $Mother_First_Name = $_POST['Mother-First-Name'] ?? "";
    $Mother_Last_Name = $_POST['Mother-Last-Name'] ?? "";
    $Mother_Middle_Name = $_POST['Mother-Middle-Name'] ?? "";
    $Mother_Parent_Type = "Mother";
    $Mother_Educational_Attainment = $_POST['M-highest-education'] ?? "";
    $Mother_Contact_Number = $_POST['M-Number'] ?? "";
    $MIf_4Ps = $_POST['fourPS'] ?? "";

    $Guardian_First_Name = $_POST['Guardian-First-Name'] ?? "";
    $Guardian_Last_Name = $_POST['Guardian-Last-Name'] ?? "";
    $Guardian_Middle_Name = $_POST['Guardian-Middle-Name'] ?? "";
    $Guardian_Parent_Type = "Guardian";
    $Guardian_Educational_Attainment = $_POST['G-highest-education'] ?? "";
    $Guardian_Contact_Number = $_POST['G-Number'] ?? "";
    $GIf_4Ps = $_POST['fourPS'] ?? "";

    // ENROLLEE INFORMATION
    $Student_First_Name = $_POST['fname'] ?? "";
    $Student_Middle_Name = $_POST['mname'] ?? "";
    $Student_Last_Name = $_POST['lname'] ?? "";
    $Student_Extension = $_POST['extension'] ?? "";
    $Learner_Reference_Number = $_POST['boolLRN'] ?? "";
    $Psa_Number = $_POST['PSA-number'] ?? "";
    $Birth_Date = $_POST['bday'] ?? "";
    $Age = $_POST['age'] ?? "";
    $Sex = $_POST['gender'] ?? "";
    $Religion = $_POST['religion'] ?? "";
    $Native_Language = $_POST['language'] ?? "";
    $If_Cultural = $_POST['group'] ?? "";
    $Cultural_Group = $_POST['community'] ?? "";
    $Student_Email = $_POST['email'] ?? "";
    $Enrollment_Status = "3";

    $isMatchingLrn = $enrollment_form->checkLRN($Learner_Reference_Number);
    $isMatchingPsa = $enrollment_form->checkPSA($Psa_Number);

    if($isMatchingLrn) {
        throw new Exception ('This LRN is already registered in the database');
    }
    if ($isMatchingPsa) {
        throw new Exception ('This PSA number is already registered in the database');
    }

    $filename = "";
    $directory = "";

    // Check if PSA image is uploaded
    if (!isset($_FILES['psa-image']) || $_FILES['psa-image']['error'] !== 0) {
        throw new Exception('PSA image is required');
    }

    // Image handling
    $uploadDirectory = "../../../ImageUploads/". date("Y")."/";
    if (!is_dir($uploadDirectory)) {
        if (!mkdir($uploadDirectory, 0777, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }

    $image = $_FILES['psa-image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageType = $image['type'];

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
        throw new Exception('Failed to upload image');
    }

    // Insert the values into the database
    $result = $enrollment_form->Insert_Enrollee(
        $userId, $School_Year_Start, $School_Year_End, $If_LRN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended,
        $Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address,
        $Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech,
        $House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code,
        $Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Parent_Type, $Father_Educational_Attainment, $Father_Contact_Number, $FIf_4Ps,
        $Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Mother_Parent_Type, $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps,
        $Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Parent_Type, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps,
        $Student_First_Name, $Student_Middle_Name, $Student_Last_Name, $Student_Extension, $Learner_Reference_Number, $Psa_Number, $Birth_Date, $Age, $Sex, $Religion,
        $Native_Language, $If_Cultural, $Cultural_Group, $Student_Email, $Enrollment_Status, $filename, $directory
    );

    // Check if result is an array with the expected structure
    if (!is_array($result) || !isset($result['success'])) {
        throw new Exception('Invalid response from enrollment form processing');
    }

    echo json_encode($result);
} 
catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>