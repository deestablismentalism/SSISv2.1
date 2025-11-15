<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../admin/controllers/reportCardController.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Invalid request method');
    }
    
    $controller = new reportCardController();
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if ($id === null) {
        throw new Exception('ID is required');
    }
    
    $response = $controller->getSubmissionById($id);
    
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

