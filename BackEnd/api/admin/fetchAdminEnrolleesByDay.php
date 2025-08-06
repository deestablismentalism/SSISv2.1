<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminDashboardModel.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $days =(int)$input['day'] ?? null;
    $enrolleeCount = new adminDashboardModel();

    $enrolleeByDay = $enrolleeCount->EnrolleesByDays($days);

    if(empty($days)) {
        echo json_encode(['success' => false, 'message' => 'Empty input']);
        exit();
    }

    if(!$enrolleeByDay) {
        echo json_encode(['success'=> false, 'message' => 'failed to fetch']);
        exit();
    }
    $result = [];
    foreach($enrolleeByDay as $days => $rows) {
        $result[] = 
            ['label' => $days, 'value' => $rows];
    }
    echo json_encode($result);
    exit();
}