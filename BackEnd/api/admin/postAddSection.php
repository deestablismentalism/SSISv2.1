<?php
declare(strict_types = 1);
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message'=> 'Not the appropriate request!']);
    exit();
}
try {

    $sectionsModel = new adminSectionsModel();

    $sectionName = $_POST['section-name'] ?? null;
    $sectionGradeLevel =$_POST['section-grade-level'] ?? null;

    if(empty($sectionName)) {
        echo json_encode(['success'=> false, 'message'=> 'Section name cannot be empty!']);
        exit();
    }
    $isExistingSectionName = $sectionsModel->checkIfSectionNameExists($sectionName);
    if($isExistingSectionName) {
        echo json_encode($isExistingSectionName);
        exit();
    }
    $update = $sectionsModel->insertSections($sectionName, $sectionGradeLevel);

    if(!$update) {
        echo json_encode(['success'=> false, 'message'=> 'update failed']);
        exit();
    }
    echo json_encode($update);
    exit();
}
catch(PDOException $e) {
    echo json_encode(['success'=> false, 'message' => $e->getMessage()]);
    exit();
}