<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../teacher/controllers/teacherDashboardController.php';

// Check if staff is logged in
if(!isset($_SESSION['Staff']['Staff-Id'])) {
    http_response_code(401);
    echo json_encode([
        'httpcode' => 401,
        'success' => false,
        'message' => 'Unauthorized access',
        'data' => []
    ]);
    exit();
}

$staffId = (int)$_SESSION['Staff']['Staff-Id'];
$controller = new teacherDashboardController();
$response = $controller->apiDashboardCharts($staffId);

http_response_code($response['httpcode']);
echo json_encode($response);
exit();
