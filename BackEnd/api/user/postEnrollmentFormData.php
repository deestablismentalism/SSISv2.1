<?php
declare(strict_types=1);
session_start();
require_once __DIR__ .  '/../../user/controllers/userEnrollmentFormController.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

header('Content-Type: application/json');
try {
    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception('Invalid request method');
    }
    // Check for user session
    if (!isset($_SESSION['User']['User-Id'])) {
        throw new IdNotFoundException('Unrecognized user');
    }

    $userId = isset($_SESSION['User']['User-Id']) ? (int) $_SESSION['User']['User-Id'] : null;
    $controller = new userEnrollmentFormController();
    // EDUCATIONAL INFORMATION
    $School_Year_Start = isset($_POST['start-year']) ? (int)$_POST['start-year']: null;
    $School_Year_End = isset($_POST['end-year']) ? (int)$_POST['end-year'] : null;
    $hasLRN = isset($_POST['bool-LRN']) ? (int)$_POST['bool-LRN'] : null;
    $Enrolling_Grade_Level = isset($_POST['grades-tbe']) ? (int)$_POST['grades-tbe'] : null;
    $Last_Grade_Level = isset($_POST['last-grade']) ? (int)$_POST['last-grade'] : null;
    $Last_Year_Attended = isset($_POST['last-year']) ? (int)$_POST['last-year'] : null;
    // EDUCATIONAL BACKGROUND
    $Last_School_Attended = $_POST['lschool'] ?? null;
    $School_Id = isset($_POST['lschoolID']) ? (int)$_POST['lschoolID'] : null;
    $School_Address = $_POST['lschoolAddress'] ?? null;
    $School_Type = $_POST['school-type'] ?? null;
    $Initial_School_Choice = $_POST['fschool'] ?? null;
    $Initial_School_Id = isset($_POST['fschoolID']) ? (int)$_POST['fschoolID'] : null;
    $Initial_School_Address = $_POST['fschoolAddress'] ?? null;
    // ENROLLEE INFORMATION
    $Student_First_Name = $_POST['fname'] ?? null;
    $Student_Middle_Name = $_POST['mname'] ?? null;
    $Student_Last_Name = $_POST['lname'] ?? null;
    $Student_Extension = $_POST['extension'] ?? null;
    $Learner_Reference_Number = isset($_POST['LRN']) ? (int)$_POST['LRN'] : null;
    $Birth_Date = $_POST['bday'] ?? null;
    $Age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $Sex = $_POST['gender'] ?? null;
    $Religion = $_POST['religion'] ?? null;
    $Native_Language = $_POST['language'] ?? null;
    $If_Cultural = isset($_POST['group']) ? (int)$_POST['group'] : null;
    $Cultural_Group = $_POST['community'] ?? null;
    $Student_Email = $_POST['email'] ?? null;
    //  DISABILITY INFORMATION
    $Have_Special_Condition = isset($_POST['sn']) ? (int)$_POST['sn'] : 0;
    $Special_Condition = $_POST['boolsn'] ?? null;
    $Have_Assistive_Tech = isset($_POST['at']) ? (int)$_POST['at'] : 0;
    $Assistive_Tech = $_POST['atdevice'] ?? null;
    //  EROLLEE ADDRESS
    $House_Number = isset($_POST['house-number']) ? (int)$_POST['house-number'] : null;
    $Subd_Name = $_POST['subdivision'] ?? null;
    // Handle barangay - should be code (string) from dropdown or text from manual input
    $barangayValue = $_POST['barangay'] ?? null;
    $Brgy_Code = (!empty($barangayValue) && $barangayValue !== 'Select a Barangay first') ? (string)$barangayValue : null;
    $Brgy_Name = $_POST['barangay-name'] ?? $_POST['barangay'] ?? null;
    // Handle city/municipality - could be code (string) from dropdown or text from manual input
    $cityValue = $_POST['city-municipality'] ?? null;
    $Municipality_Code = (!empty($cityValue) && $cityValue !== 'Select a City/Municipality first' && !preg_match('/^Select/', $cityValue)) ? (string)$cityValue : null;
    $Municipality_Name = $_POST['city-municipality-name'] ?? $_POST['city-municipality'] ?? null;
    //HANDLE Province
    $provinceValue = $_POST['province'] ?? null;
    $Province_Code = (!empty($provinceValue) && $provinceValue !== 'Select a Province first' && !preg_match('/^Select/', $provinceValue)) ? (string)$provinceValue : null;
    $Province_Name = $_POST['province-name'] ?? $_POST['province'] ?? null;
    //HANDLE Region
    $regionValue = $_POST['region'] ?? null;
    $Region_Code = (!empty($regionValue) && !preg_match('/^Select/', $regionValue)) ? (string)$regionValue : null;
    $Region = $_POST['region-name'] ?? $_POST['region'] ?? null;
    // ENROLLEE PARENTS INFORMATION
    //GUARDIAN
    $Guardian_First_Name = $_POST['Guardian-First-Name'] ?? null;
    $Guardian_Last_Name = $_POST['Guardian-Last-Name'] ?? null;
    $Guardian_Middle_Name = $_POST['Guardian-Middle-Name'] ?? null;
    $Guardian_Parent_Type = $_POST['G-Relationship'] ?? 'Guardian';
    $Guardian_Educational_Attainment = $_POST['G-highest-education'] ?? null;
    $Guardian_Contact_Number = $_POST['G-Number'] ?? null;
    $GIf_4Ps = isset($_POST['fourPS']) ? ($_POST['fourPS'] === 'yes' ? 1 : 0) : null;
    // ENROLLEE STATUS(PENDING)
    $Enrollment_Status = 3;
    //REPORT CARD IMAGES (FRONT AND BACK)
    $reportCardFront = $_FILES['report-card-front'] ?? null;
    $reportCardBack = $_FILES['report-card-back'] ?? null;
    
    // Get session ID to check for existing validation
    $sessionId = session_id();
    
    // Check if report card was pre-validated in this session
    require_once __DIR__ . '/../../admin/models/reportCardModel.php';
    $reportCardModel = new reportCardModel();
    $existingValidation = $reportCardModel->getSubmissionBySessionId($sessionId);
    
    // If validation exists and status is 'rejected', prevent enrollment
    if ($existingValidation && $existingValidation['status'] === 'rejected') {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'Report card was rejected. Please resubmit valid images.',
            'data' => [
                'flag_reason' => $existingValidation['flag_reason'] ?? 'Report card images do not meet requirements'
            ]
        ]);
        exit();
    }
    
    // Insert the values into the database
    $response = $controller->apiPostAddEnrollee(
        $userId, $School_Year_Start, $School_Year_End, $hasLRN, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended,
        $Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address,
        $Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech,
        $House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code,
        $Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Parent_Type, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps,
        $Student_First_Name,$Student_Last_Name,$Student_Middle_Name,$Student_Extension, $Learner_Reference_Number,$Birth_Date, $Age, $Sex, $Religion,
        $Native_Language, $If_Cultural, $Cultural_Group, $Student_Email, $Enrollment_Status, $reportCardFront, $reportCardBack);
    //SET CONTROLLER HTTP RESPONSE CODE
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
} 
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}
catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}
?>