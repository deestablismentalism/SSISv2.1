<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSchedulesController.php';
header('Content-Type: application/json');
try {
    $controller = new adminSchedulesController();
    $response = $controller->apiGetSectionScheduleSummaryByGradeLevel();
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Excpetion $e) {
    echo json_encode(['success'=> false,'message'=> 'sdjdsg']);
    exit();
}