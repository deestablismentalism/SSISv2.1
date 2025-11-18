<?php 
declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
header('Content-Type: application/json');

try {
    $sectionId = isset($_POST['section-id']) ? (int)$_POST['section-id'] : null;
    if(is_null($sectionId)) {
        throw new IdNotFoundException('Subject ID not found');
    }
    $sections = new adminSectionsModel();
    $response = $sections->deleteSection($sectionId);
    if(!$response) {
        echo json_encode(['success'=> false,'message'=> 'Section deletion failed']);
        exit();
    }
    echo json_encode(['success'=> true,'message'=>'Section deleted successfully']);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}
catch(DatabaseException $e) {
    echo json_encode(['success'=> false,'message'=>  'There was a server problem: ' .$e->getMessage()]);
    exit();
}
catch(Throwable $t) {
    error_log("[".date('Y-m-d H:i:s')."]" . $t . "\n", 3, __DIR__  . '/../../errorLogs.txt');
    echo json_encode(['success'=> false,'message'=>'There was a syntax problem. Please wait while we look into it']);
    exit();
}