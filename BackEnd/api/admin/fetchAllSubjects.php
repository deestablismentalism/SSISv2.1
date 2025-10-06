<?php

require_once __DIR__ . '/../../admin/controller/adminSchedulesController.php';

try {
    $controller = new adminSchedulesController();
    $response = $controller->apiFetchAllSectionSubjects();
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> 'There was a problem. ' . $e->getMessage()]);
    exit();
}