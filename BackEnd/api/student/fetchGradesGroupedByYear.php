<?php
declare(strict_types=1);
require_once __DIR__ . '/../../student/controllers/studentClassController.php';
header('Content-Type: application/json');
try {
    $studentId = isset($_GET['student-id']) ? (int)$_GET['student-id'] : null;
    $controller = new studentClassController();
    $response = $controller->apiHistoricalStudentGradeRecords($studentId);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message' => 'Something went wrong on our side. Please wait for a while']);
    exit();
}