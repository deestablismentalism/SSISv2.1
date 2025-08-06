<?php
declare(strict_types=1);
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';

$adminEnrolleesModel = new adminEnrolleesModel();
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message' => 'Wrong method']);
    exit();
}

$status = (int)$_POST['status'];
$enrolleeId = (int)$_POST['id'];

if(!isset($status) && !isset($enrolleeId)) {
    echo json_encode(['success'=> false, 'message' => 'No status or id found']);
    exit();
}
$update = $adminEnrolleesModel->updateEnrollee($id, $status);

if(!$update) {
    echo json_encode(['success'=> false, 'message' => 'Update failed']);
    exit();
}

echo json_encode($update);
exit();
