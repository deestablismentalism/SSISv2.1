<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminDashboardController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $days =(int)$input['day'] ?? null;

    $controller= new adminDashboardController();
    $response = $controller->apiEnrolleesByDays($days);

    $enrolleeByDay = $response['data'];

    http_response_code($response['httpcode']);

    echo json_encode($response);
    exit();
}