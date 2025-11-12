<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSchedulesController.php';
header('Content-Type: application/json');

try {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
        exit();
    }
    $sectionSubjectId = isset($_POST['section-subject-id']) ? (int)$_POST['section-subject-id'] : null;
    $schedules = isset($_POST['schedules']) ? json_decode($_POST['schedules'],true) : [];
    $controller = new adminSchedulesController();
    $response = $controller->apiPostSectionSchedule($sectionSubjectId,$schedules);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> 'There was an unexpected problem']);
    exit();
}