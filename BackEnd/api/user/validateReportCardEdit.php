<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../admin/controllers/reportCardController.php';
require_once __DIR__ . '/../../user/models/userEnrolleesModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Check for user session
    if (!isset($_SESSION['User']['User-Id'])) {
        throw new Exception('User session not found');
    }
    
    $userId = (int)$_SESSION['User']['User-Id'];
    $enrolleeId = isset($_POST['enrollee_id']) ? (int)$_POST['enrollee_id'] : null;
    
    if (!$enrolleeId) {
        throw new Exception('Enrollee ID is required');
    }
    
    // Get enrollee information to extract student details
    $enrolleesModel = new userEnrolleesModel();
    $enrolleeData = $enrolleesModel->getEnrolleeInformation($enrolleeId);
    
    if (empty($enrolleeData)) {
        throw new Exception('Enrollee not found');
    }
    
    $studentName = trim($enrolleeData['Student_First_Name'] . ' ' . 
                       ($enrolleeData['Student_Middle_Name'] ?? '') . ' ' . 
                       $enrolleeData['Student_Last_Name']);
    $studentLrn = (string)($enrolleeData['Learner_Reference_Number'] ?? '');
    $enrollingGradeLevel = (int)($enrolleeData['Enrolling_Grade_Level'] ?? 0);
    
    // Check if Kinder 1 (grade level 1) - skip validation
    if ($enrollingGradeLevel === 1) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Kinder 1 students are exempt from report card validation',
            'data' => [
                'status' => 'approved',
                'exemption_reason' => 'Kinder 1 - No report card required'
            ]
        ]);
        exit();
    }
    
    if (empty($studentName)) {
        throw new Exception('Student name is required');
    }
    
    if (empty($studentLrn)) {
        throw new Exception('Student LRN is required');
    }
    
    if (empty($enrollingGradeLevel)) {
        throw new Exception('Enrolling grade level is required');
    }
    
    // Get report card files
    $reportCardFront = $_FILES['report-card-front'] ?? null;
    $reportCardBack = $_FILES['report-card-back'] ?? null;
    
    if (!$reportCardFront || !$reportCardBack) {
        throw new Exception('Both report card images are required');
    }
    
    if ($reportCardFront['error'] !== UPLOAD_ERR_OK || $reportCardBack['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error uploading report card images');
    }
    
    // Generate session ID for tracking this validation
    $sessionId = session_id();
    
    // Initialize controller and validate
    $controller = new reportCardController();
    $response = $controller->processReportCardUpload(
        $userId,
        $studentName,
        $studentLrn,
        $reportCardFront,
        $reportCardBack,
        $enrolleeId, // Pass enrollee ID for update context
        $sessionId,
        1  // validation_only = 1 (just validate, don't store permanently)
    );
    
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => []
    ]);
    exit();
}
