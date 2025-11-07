<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSubjectsController.php';
header('Content-Type: application/json');

try {
    $controller = new adminSubjectsController();
    $response = $controller->apiGetSubjectsGrouped();
    
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success'=> false,
        'message'=> 'Server error: ' . $e->getMessage()
    ]);
    exit();
}
