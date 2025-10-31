<?php
declare(strict_types=1);
require_once __DIR__ . '/../../teacher/controller/teacherGradesController.php';
require_once  __DIR__ . '/../../Exceptions/IdNotFoundException.php';
session_start();
header('Content-Type: application/json');
try {
    
    $staffId = isset($_SESSION['Staff']['Staff-Id']) ? (int) $_SESSION['Staff']['Staff-Id'] : null;
    $sectionSubjectId = isset($_GET['secSubId']) ? (int) $_GET['secSubId'] : null;
    if(is_null($sectionSubjectId)) {
        throw new IdNotFoundException('Unrecognized section subject. Unable to change');
    }
    if(is_null($staffId)) {
        var_dump($_SESSION);
        die();
        throw new IdNotFoundException('Unauthorized access! Cannot make changes.');
    }
    $controller = new teacherGradesController();
    $response = $controller->apiFetchSectionSubjectStudents($sectionSubjectId, $staffId);

    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit();
}
catch(IdNotFoundException $e) {
    echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
    exit();
}
catch(Exception $e) {
    echo json_encode(['success'=> false, 'message'=> $e->getMessage()]);
    exit();
}