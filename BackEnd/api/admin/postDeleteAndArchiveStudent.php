<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminStudentsController.php';
// Return the response as JSON
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false,'message'=> 'Invalid request method']);
    exit();
}
try {
    $studentId = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $controller = new adminStudentsController();
    $response = $controller->apiDeleteAndArchiveStudent($studentId);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false,'message'=>$e->getMessage()]);
    exit();
}
catch(Throwable $t) {
    error_log("[".date('Y-m-d H:i:s')."]" .$t ."\n",3, __DIR__ . '/../../errorLogs.txt');
    echo json_encode(['success'=> false,'message'=> 'There was a syntax problem. Please wait for it to be fixed']);
    exit();
}