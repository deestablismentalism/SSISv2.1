<?php
declare(strict_types=1);
require_once __DIR__ . '/../../user/controllers/userEnrolleesController.php';
require_once __DIR__ . '/../../user/models/userEnrolleesModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

header('Content-Type: application/json');
try {
    $controller = new userEnrolleesController();
    $model = new userEnrolleesModel();
    
    if (!isset($_GET['editId'])) {
        throw new IdNotFoundException('Enrollee ID not found');
    }
    $enrolleeId = isset($_GET['editId']) ? (int) $_GET['editId'] : null;
    
    // Check if resubmission is allowed before fetching data
    $canResubmit = $model->canResubmit($enrolleeId);
    if(!$canResubmit) {
        http_response_code(403);
        echo json_encode([
            'success'=> false, 
            'message'=> 'Resubmission is not allowed. You have already resubmitted this form or need consultation with the school.'
        ]);
        exit();
    }
    
    $response= $controller->apiEnrolleeData($enrolleeId); //REF: 3.5.3
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
    exit();
}
