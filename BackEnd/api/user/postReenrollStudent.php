<?php
declare(strict_types=1);
require_once __DIR__ . '/../../user/models/userStudentsModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
header('Content-Type: application/json');
try {
    $model = new userStudentsModel();
    $studentId = isset($_POST['student-id']) ? (int)$_POST['student-id'] : null;
    $status = isset($_POST['active-status']) ? (int)$_POST['active-status'] : null;
    if(is_null($studentId)) {
        throw new IdNotFoundException('Student ID not found');
    }
    $response = $model->reEnrollStudent($studentId,$status);
    if($response) {
        echo json_encode(['success'=> true,'message'=> 'Student re-enrolled successfully']);
        exit();
    }
    else {
        echo json_encode(['success'=> false,'message'=> 'Student failed to re-enroll']);
        exit();
    }
}
catch(IdNotFoundException $e) {
    http_response_code(400);
    echo json_encode(['success'=> false,'message'=> 'There was a server problem: ' .$e->getMessage()]);
    exit();
}
catch(DatabaseException $e) {
    http_response_code(500);
    echo json_encode(['success'=> false,'message'=> 'There was a server problem: ' .$e->getMessage()]);
    exit();
}
