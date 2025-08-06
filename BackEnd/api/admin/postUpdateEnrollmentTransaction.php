<?php
declare(strict_types =1);
header('Content-Type: application/json');

require_once __DIR__ . '/../../admin/models/adminEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';

$isResubmit = (int)$_POST['isResubmit'];
$isConsult = (int)$_POST['isConsult'];
$id = (int)$_POST['id'];
$enrolleeId = (int)$_POST['enrolleeId'];
$status = (int)$_POST['status'];
$adminEnrolleesModel = new adminEnrolleesModel();
$adminTransactionsModel = new adminEnrollmentTransactionsModel();
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message' => 'Wrong method']);
    exit();
}
if(!isset($isResubmit) && !isset($enrolleeId) && !isset($id)) {
    echo json_encode(['success'=> false, 'message' => 'No status or id found']);
    exit();
}
$update = $adminTransactionsModel->updateNeededAction($id, $isResubmit, $isConsult);

if(!$update) {
    echo json_encode(['success'=> false, 'message' => 'Update failed']);
    exit();
}
$updateEnrollee = adminEnrolleesModel->updateEnrollee($enrolleeId, $status);

if($updateEnrollee) {
    echo json_encode($update);
    exit();
}
