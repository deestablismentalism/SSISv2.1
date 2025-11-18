<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/models/adminEditTeacherInfoModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

if (!isset($_POST['staff_id']) || !isset($_POST['status']) || !isset($_POST['position']) || !isset($_POST['staff_type'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
    exit();
}

$staffId = intval($_POST['staff_id']);
$status = $_POST['status'];
$position = $_POST['position'];
$staffType = $_POST['staff_type'];

$model = new adminEditInformation();
$result = $model->editTeacherInformation($status, $position, $staffType, $staffId);

echo json_encode($result);
?>