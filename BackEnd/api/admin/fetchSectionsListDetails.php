<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';

header('Content-Type: application/json');

$sectionsModel = new adminSectionsModel();

try {
    if(!$sectionsModel) {
        echo json_encode(['success' => false, 'message' => 'There was a problem connecting to the database']);
        exit();
    }
    $allSections = $sectionsModel->getSectionsListInformation();

    if(!$allSections) {
        echo json_encode(['success'=> false, 'message' => 'There was a problem with fetching the data']);
        exit();
    }
    echo json_encode($allSections);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message' => $e->getMessage()]);
    exit();
}