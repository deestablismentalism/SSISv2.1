<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSubjectsController.php';
header('Content-Type: application/json');
try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception('Invalid request method');
    }
        $controller = new adminSubjectsController();
        $subjectName = $_POST['subject-name'];

        if (isset($_POST['levels']) && is_array($_POST['levels']) && !empty($_POST['levels'])) {
            $subjectIds = $_POST['levels'];
            $response = $controller->apiPostAddSubject($subjectName, $subjectIds);
            
            http_response_code($response['httpcode']);
            echo json_encode($response);
            exit();
        }
        // Single grade level from select dropdown
        if (isset($_POST['subject-level']) && !empty($_POST['subject-level'])) {
            $subjectLevel = (int)$_POST['subject-level'];
            $response = $controller->apiPostAddSubject($subjectName, $subjectLevel);
            
            http_response_code($response['httpcode']);
            echo json_encode($response);
            exit();
        }
}
catch (Exception $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}

