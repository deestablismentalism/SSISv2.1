<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../staff/controllers/staffEnrollmentController.php';
header("Content-Type: application/json");

try {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message'=> 'Invalid request method']);
        exit();
    }
    
    if(!isset($_SESSION['Staff']) || !in_array($_SESSION['Staff']['Staff-Type'], [1,2])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }
    
    // Extract POST values
    $enrolleeId = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $status = isset($_POST['status']) ? (int)$_POST['status'] : null;
    $remarks = $_POST['remarks'] ?? '';
    $staffId = isset($_SESSION['Staff']['Staff-Id']) ? (int)$_SESSION['Staff']['Staff-Id'] : null;
    
    // Validate required fields
    if(is_null($enrolleeId) || is_null($status) || is_null($staffId)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }
    
    // Call controller
    $controller = new staffEnrollmentController();
    $response = $controller->apiPostUpdateEnrolleeStatus($staffId, $status, $enrolleeId, $remarks);

    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    error_log("[".date('Y-m-d H:i:s')."] API Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error occurred']);
    exit();
}
