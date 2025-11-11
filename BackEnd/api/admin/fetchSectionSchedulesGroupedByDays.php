<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSchedulesController.php';
header('Content-Type: application/json');
try {
    $sectionSubjectId = isset($_GET['sec-sub-id']) ? (int)$_GET['sec-sub-id'] : null;
    $controller = new adminSchedulesController();
    $response = $controller->apiFetchSectionSchedulesGroupedByDays($sectionSubjectId);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
    exit();
}
catch(Throwable $t) {
    error_log("[".date('Y-m-d H:i:s')."]" .$t ."\n",3, __DIR__ . '/../../errorLogs.txt');
    echo json_encode(['success'=> false,'message'=>'There was a syntax problem. Please wait while we look into it']);
    exit();
}