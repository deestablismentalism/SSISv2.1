<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../admin/controllers/reportCardController.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $controller = new reportCardController();
    
    $studentName = $_POST['student_name'] ?? null;
    $studentLrn = $_POST['student_lrn'] ?? null;
    $enrolleeId = isset($_POST['enrollee_id']) ? (int)$_POST['enrollee_id'] : null;
    $reportCardFront = $_FILES['report_card_front'] ?? null;
    $reportCardBack = $_FILES['report_card_back'] ?? null;
    
    if (empty($studentName) || empty($studentLrn)) {
        throw new Exception('Student name and LRN are required');
    }
    
    if (empty($reportCardFront)) {
        throw new Exception('Report card front image is required');
    }
    
    if (empty($reportCardBack)) {
        throw new Exception('Report card back image is required');
    }
    
    $userId = isset($_SESSION['User']['User-Id']) ? (int)$_SESSION['User']['User-Id'] : null;
    
    $response = $controller->processReportCardUpload($userId, $studentName, $studentLrn, $reportCardFront, $reportCardBack, $enrolleeId);
    
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

