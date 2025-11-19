<?php
declare(strict_types=1);

// Suppress all errors initially
error_reporting(0);
ini_set('display_errors', '0');

// Start output buffering
ob_start();

// Start session
session_start();

// Include required files
require_once __DIR__ .  '/../../user/controllers/userEnrollmentFormController.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

// Clear buffer and set headers
ob_clean();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

// Log all requests for debugging
error_log("[ADMIN ENROLLMENT] Request received at " . date('Y-m-d H:i:s') . "\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== "POST") {
        http_response_code(405);
        echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
        exit();
    }
    
    // Check for Staff session
    if (!isset($_SESSION['Staff']['Staff-Id'])) {
        error_log("[ADMIN ENROLLMENT] Unauthorized: No Staff session\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
        http_response_code(401);
        echo json_encode(['success'=> false, 'message'=> 'Unauthorized: Please log in as admin']);
        exit();
    }
    
    // Verify admin privileges
    if (!isset($_SESSION['Staff']['User-Type']) || $_SESSION['Staff']['User-Type'] != 1) {
        error_log("[ADMIN ENROLLMENT] Forbidden: User-Type = " . ($_SESSION['Staff']['User-Type'] ?? 'null') . "\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
        http_response_code(403);
        echo json_encode(['success'=> false, 'message'=> 'Forbidden: Admin privileges required']);
        exit();
    }
    
    $controller = new userEnrollmentFormController();
    $enrolleesModel = new adminEnrolleesModel();
    $studentsModel = new adminStudentsModel();
    
    // Extract and sanitize POST data
    $School_Year_Start = isset($_POST['start-year']) ? (int)$_POST['start-year'] : null;
    $School_Year_End = isset($_POST['end-year']) ? (int)$_POST['end-year'] : null;
    $hasLRN = isset($_POST['bool-LRN']) ? (int)$_POST['bool-LRN'] : null;
    $Enrolling_Grade_Level = isset($_POST['grades-tbe']) ? (int)$_POST['grades-tbe'] : null;
    $Last_Grade_Level = isset($_POST['last-grade']) ? (int)$_POST['last-grade'] : null;
    $Last_Year_Attended = isset($_POST['last-year']) ? (int)$_POST['last-year'] : null;
    
    $Last_School_Attended = $_POST['lschool'] ?? '';
    $School_Id = isset($_POST['lschoolID']) && $_POST['lschoolID'] !== '' ? (int)$_POST['lschoolID'] : null;
    $School_Address = $_POST['lschoolAddress'] ?? '';
    $School_Type = $_POST['school-type'] ?? 'Public';
    $Initial_School_Choice = $_POST['fschool'] ?? '';
    $Initial_School_Id = isset($_POST['fschoolID']) && $_POST['fschoolID'] !== '' ? (int)$_POST['fschoolID'] : null;
    $Initial_School_Address = $_POST['fschoolAddress'] ?? '';
    
    $Student_First_Name = $_POST['fname'] ?? '';
    $Student_Middle_Name = $_POST['mname'] ?? null;
    $Student_Last_Name = $_POST['lname'] ?? '';
    $Student_Extension = $_POST['extension'] ?? null;
    $Learner_Reference_Number = isset($_POST['LRN']) && $_POST['LRN'] !== '' ? (int)$_POST['LRN'] : null;
    $Birth_Date = $_POST['bday'] ?? '';
    $Age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $Sex = $_POST['gender'] ?? 'Female';
    $Religion = $_POST['religion'] ?? '';
    $Native_Language = $_POST['language'] ?? '';
    $If_Cultural = isset($_POST['group']) ? (int)$_POST['group'] : 1;
    $Cultural_Group = $_POST['community'] ?? null;
    $Student_Email = $_POST['email'] ?? '';
    
    $Have_Special_Condition = isset($_POST['sn']) ? (int)$_POST['sn'] : 1;
    $Special_Condition = $_POST['boolsn'] ?? null;
    $Have_Assistive_Tech = isset($_POST['at']) ? (int)$_POST['at'] : 1;
    $Assistive_Tech = $_POST['atdevice'] ?? null;
    
    $House_Number = isset($_POST['house-number']) ? (int)$_POST['house-number'] : 0;
    $Subd_Name = $_POST['subdivision'] ?? '';
    
    $barangayValue = $_POST['barangay'] ?? '';
    $Brgy_Code = (isset($barangayValue) && is_numeric($barangayValue) && $barangayValue !== '') ? (int)$barangayValue : 0;
    $Brgy_Name = $_POST['barangay-name'] ?? ($barangayValue && !is_numeric($barangayValue) ? $barangayValue : '');
    
    $cityValue = $_POST['city-municipality'] ?? '';
    $Municipality_Code = (isset($cityValue) && is_numeric($cityValue) && $cityValue !== '') ? (int)$cityValue : 0;
    $Municipality_Name = $_POST['city-municipality-name'] ?? ($cityValue && !is_numeric($cityValue) ? $cityValue : '');
    
    $Province_Code = isset($_POST['province']) ? (int)$_POST['province'] : 0;
    $Province_Name = $_POST['province-name'] ?? '';
    $Region_Code = isset($_POST['region']) ? (int)$_POST['region'] : 0;
    $Region = $_POST['region-name'] ?? '';
    
    $Father_First_Name = $_POST['Father-First-Name'] ?? '';
    $Father_Last_Name = $_POST['Father-Last-Name'] ?? '';
    $Father_Middle_Name = $_POST['Father-Middle-Name'] ?? null;
    $Father_Educational_Attainment = $_POST['F-highest-education'] ?? 'Hindi Nakapag-aral';
    $Father_Contact_Number = $_POST['F-Number'] ?? '';
    $FIf_4Ps = isset($_POST['fourPS']) ? ($_POST['fourPS'] === 'yes' ? 1 : 0) : 0;
    
    $Mother_First_Name = $_POST['Mother-First-Name'] ?? '';
    $Mother_Last_Name = $_POST['Mother-Last-Name'] ?? '';
    $Mother_Middle_Name = $_POST['Mother-Middle-Name'] ?? null;
    $Mother_Educational_Attainment = $_POST['M-highest-education'] ?? 'Hindi Nakapag-aral';
    $Mother_Contact_Number = $_POST['M-Number'] ?? '';
    $MIf_4Ps = isset($_POST['fourPS']) ? ($_POST['fourPS'] === 'yes' ? 1 : 0) : 0;
    
    $Guardian_First_Name = $_POST['Guardian-First-Name'] ?? '';
    $Guardian_Last_Name = $_POST['Guardian-Last-Name'] ?? '';
    $Guardian_Middle_Name = $_POST['Guardian-Middle-Name'] ?? null;
    $Guardian_Educational_Attainment = $_POST['G-highest-education'] ?? 'Hindi Nakapag-aral';
    $Guardian_Contact_Number = $_POST['G-Number'] ?? '';
    $GIf_4Ps = isset($_POST['fourPS']) ? ($_POST['fourPS'] === 'yes' ? 1 : 0) : 0;
    
    $Enrollment_Status = 1; // Auto-enrolled
    $image = $_FILES['psa-image'] ?? null;
    
    // Log attempt
    error_log("[ADMIN ENROLLMENT] Attempting to enroll: {$Student_First_Name} {$Student_Last_Name}, LRN: {$Learner_Reference_Number}, Status: {$Enrollment_Status}\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
    
    // Call controller
    $response = $controller->apiPostAddEnrollee(
        null, $School_Year_Start, $School_Year_End, $hasLRN, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended,
        $Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address,
        $Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech,
        $House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code,
        $Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Educational_Attainment, $Father_Contact_Number, $FIf_4Ps,
        $Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps,
        $Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps,
        $Student_First_Name, $Student_Last_Name, $Student_Middle_Name, $Student_Extension, $Learner_Reference_Number, $Birth_Date, $Age, $Sex, $Religion,
        $Native_Language, $If_Cultural, $Cultural_Group, $Student_Email, $Enrollment_Status, null, null);
    
    // Log result
    error_log("[ADMIN ENROLLMENT] Result: " . json_encode($response) . "\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
    
    // If enrollment was successful and status is 1 (Enrolled), add to students table and mark as handled
    if ($response['success'] && $Enrollment_Status === 1 && isset($response['data']['enrollee_id'])) {
        $enrolleeId = (int)$response['data']['enrollee_id'];
        
        try {
            // Set Is_Handled to 1
            $handledResult = $enrolleesModel->setIsHandledStatus($enrolleeId, 1);
            if (!$handledResult) {
                error_log("[ADMIN ENROLLMENT] Failed to set Is_Handled for Enrollee ID: $enrolleeId\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
            }
            
            // Insert into students table
            $studentInsert = $studentsModel->insertEnrolleeToStudent($enrolleeId);
            if (!$studentInsert) {
                error_log("[ADMIN ENROLLMENT] Failed to insert enrollee to students table for Enrollee ID: $enrolleeId\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
                $response['message'] = 'Enrollment successful but failed to add to students table';
            } else {
                error_log("[ADMIN ENROLLMENT] Successfully inserted Enrollee ID: $enrolleeId to students table\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
            }
        } catch (Exception $e) {
            error_log("[ADMIN ENROLLMENT] Error in post-enrollment processing: " . $e->getMessage() . "\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
        }
    }
    
    // Clear buffer
    ob_clean();
    
    // Send response
    http_response_code($response['httpcode'] ?? 200);
    echo json_encode($response);
    
} catch(Exception $e) {
    // Clear buffer
    ob_clean();
    
    // Log error
    error_log("[ADMIN ENROLLMENT] Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", 3, __DIR__ . '/../../adminEnrollmentLog.txt');
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

// End output buffering
ob_end_flush();
exit();
?>