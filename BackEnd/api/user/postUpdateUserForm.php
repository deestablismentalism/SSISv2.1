<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../user/controllers/userEnrollmentFormController.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

header('Content-Type: application/json');
// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}
try {
    // Check if request contains files (FormData) or JSON
    $hasFiles = !empty($_FILES);
    
    // Get POST data based on content type
    if ($hasFiles) {
        // FormData submission with files
        $postData = $_POST;
        $files = $_FILES;
    } else {
        // JSON submission without files
        $postData = json_decode(file_get_contents('php://input'), true);
        $files = [];
    }
    
    if(!isset($_SESSION['User']['User-Id'])) {
        throw new IdNotFoundException('User ID not found');
    }
    $userId = (int) $_SESSION['User']['User-Id'];
    $controller = new userEnrollmentFormController();
    $response = $controller->apiUpdateEnrolleeInfo($userId, $postData, $files); //REF: F 3.5.1
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit(); 
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}