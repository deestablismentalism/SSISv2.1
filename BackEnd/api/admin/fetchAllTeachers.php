<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/controller/adminTeacherController.php';
header('Content-Type: application/json');

try {
    $controller = new adminTeacherController();
    $sectionSubjectId = isset($_GET['sec-sub-id']) ? (int)$_GET['sec-sub-id'] : 0;
    $response = $controller->apiFetchCurrentlyAssignedTeacher($sectionSubjectId);

    http_response_code($response['httpcode']);

    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> 'There is a problem']);
    exit();
}