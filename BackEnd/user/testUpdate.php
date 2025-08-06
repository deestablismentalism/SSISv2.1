<?php
require_once __DIR__ . '/../models/userEditFormModel.php';

header('Content-Type: application/json');

// Get POST data
$postData = json_decode(file_get_contents('php://input'), true);

if (!isset($postData['enrolleeId']) || !isset($postData['firstName'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$model = new userEditFormModel();
$result = $model->testSingleUpdate($postData['enrolleeId'], $postData['firstName']);

echo json_encode($result);
exit(); 