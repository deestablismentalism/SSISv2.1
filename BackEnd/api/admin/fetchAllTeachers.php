<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminTeachersModel.php';
header('Content-Type: application/json');

$sectionsModel = new adminTeachersModel();

if(empty($sectionsModel)) {
    echo json_encode(['message' => 'No Teachers found.']);
    exit();
}
if(!$sectionsModel) {
    echo json_encode(['success' => false, 'message' => 'There was a failure during fetch']);
    exit();
}

$fetchTeachers = $sectionsModel->selectAllTeachers();

if(!$fetchTeachers) {
    echo json_encode(['success'=> false, 'message' => 'An error occured']);
    exit();
}

echo json_encode($fetchTeachers);
exit();