<?php
declare(strict_types = 1);
require_once __DIR__ . '/../../admin/controller/adminSectionsController.php';

header('Content-Type: application/json');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success'=> false, 'message'=> 'Not the appropriate request!']);
        exit();
    }
    $sectionName = $_POST['section-name'] ?? null;
    $sectionGradeLevel = (int)$_POST['section-grade-level'] ?? null;

    $controller = new adminSectionsController();
    $response = $controller->apiAddSectionForm($sectionName, $sectionGradeLevel);

    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message' => $e->getMessage()]);
    exit();
}