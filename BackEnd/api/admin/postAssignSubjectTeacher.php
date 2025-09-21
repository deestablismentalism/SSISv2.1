<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/controller/adminTeacherController.php';
header('Content-Type: application/json');
try {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
        exit();
    }
    
    $staffId = isset($_POST['subject-teacher']) ? (int)$_POST['subject-teacher'] : 0;
    $sectionSubjectsId = isset($_POST['section-subject-id']) ? (int)$_POST['section-subject-id'] : 0;
    
    $controller  = new adminTeacherController();
    $response = $controller->apiPostAssignTeacher($staffId, $sectionSubjectsId);

    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}