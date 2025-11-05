<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSectionsController.php';

header('Content-Type: application/json');
try {
    $controller = new adminSectionsController();
    $response = $controller->apiSectionsListInformation();

    http_response_code($response['httpcode']);

    echo json_encode($response);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message' => 'Something went wrong on our side. Please wait for a while']);
    exit();
}