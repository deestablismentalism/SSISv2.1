<?php
declare(strict_types=1);
require_once __DIR__ . '/../../teacher/controllers/teacherGradesController.php';
header('Content-Type: application/json');
try {
    $jsonData = file_get_contents('php://input');
    $dataArray = json_decode($jsonData, true);
    $controller = new teacherGradesController();
    $response = $controller->apiPostStudentGrades($dataArray);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
    exit();
}