<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controller/adminSchedulesController.php';
header('Content-Type: application/json');

try {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
        exit();
    }
    $sectionSubjectId = isset($_POST['section-subject-id']) ? (int)$_POST['section-subject-id'] : 0;
    $day = (int)$_POST['schedule-day'];
    $timeStart = $_POST['time-start'];
    $timeEnd = $_POST['time-end'];

    $controller = new adminSchedulesController();
    $response = $controller->apiPostSectionSchedule($sectionSubjectId, $day, $timeStart, $timeEnd);
    
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> 'There was an unexpected problem']);
    exit();
}