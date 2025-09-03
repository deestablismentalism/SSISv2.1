<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';
header('Content-Type: application/json');

$sectionsModel = new adminSectionsModel();

if(empty($sectionsModel)) {
    echo json_encode(['message' => 'No Teachers found.']);
    exit();
}
if(!$sectionsModel) {
    echo json_encode(['success' => false, 'message' => 'There was a failure during fetch']);
    exit();
}

$fetchTeachers = $sectionsModel->getAllTeachers();

if(!$fetchTeachers) {
    echo json_encode(['success'=> false, 'message' => 'An error occured']);
    exit();
}

echo json_encode($fetchTeachers);
exit();