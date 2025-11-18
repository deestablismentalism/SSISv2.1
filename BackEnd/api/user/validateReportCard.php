<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../admin/controllers/reportCardController.php';
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
    $controller = new reportCardController();
    
    // Get student info for validation
    $studentName = $_POST['student_name'] ?? '';
    $studentLrn = $_POST['student_lrn'] ?? '';
    
    // Get report card files
    $reportCardFront = $_FILES['report-card-front'] ?? null;
    $reportCardBack = $_FILES['report-card-back'] ?? null;
    
    if (empty($studentName)) {
        throw new Exception('Student name is required');
    }
    
    if (empty($studentLrn)) {
        throw new Exception('Student LRN is required');
    }
    
    if (empty($reportCardFront)) {
        throw new Exception('Report card front image is required');
    }
    
    if (empty($reportCardBack)) {
        throw new Exception('Report card back image is required');
    }
    
    // Generate session ID for tracking this validation
    $sessionId = session_id();
    
    // Process report card with validation_only flag
    $response = $controller->processReportCardUpload(
        $userId,
        $studentName,
        $studentLrn,
        $reportCardFront,
        $reportCardBack,
        null, // enrolleeId = null (not created yet)
        $sessionId,
        1  // validation_only = 1
    );
    
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit();
}
