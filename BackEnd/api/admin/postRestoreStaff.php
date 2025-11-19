<?php 
declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminTeachersModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
header('Content-Type: application/json');

try {
    $subjectId = isset($_POST['subject-id']) ? (int)$_POST['subject-id'] : null;
    if(is_null($subjectId)) {
        throw new IdNotFoundException('Subject ID not found');
    }
    $subjects = new adminTeachersModel();
    $response = $subjects->restoreStaff($subjectId);
    if(!$response) {
        echo json_encode(['success'=> false,'message'=> 'Teacher was not restored']);
        exit();
    }
    echo json_encode(['success'=> true,'message'=>'Teacher restored successfully']);
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