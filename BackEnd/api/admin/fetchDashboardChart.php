<?php

declare(strict_types =1);
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/controllers/adminDashboardController.php';

$controller = new adminDashboardController();
$response = $controller->apiDashboardChart();

http_response_code($response['httpcode']);
echo json_encode($response);
exit();
    


