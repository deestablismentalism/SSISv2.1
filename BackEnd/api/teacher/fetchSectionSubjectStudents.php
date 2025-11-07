<?php
declare(strict_types=1);

// Turn off error display, but log them
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffering to catch any unwanted output
while (ob_get_level() > 0) {
    ob_end_clean();
}
ob_start();

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        ob_clean();
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Fatal error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line'],
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
        ob_end_flush();
        exit();
    }
});

require_once __DIR__ . '/../../teacher/controllers/teacherGradesController.php';
require_once  __DIR__ . '/../../Exceptions/IdNotFoundException.php';

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {
    $staffId = isset($_SESSION['Staff']['Staff-Id']) ? (int) $_SESSION['Staff']['Staff-Id'] : null;
    $sectionSubjectId = isset($_GET['secSubId']) ? (int) $_GET['secSubId'] : null;
    
    if(is_null($sectionSubjectId) || $sectionSubjectId <= 0) {
        throw new IdNotFoundException('Unrecognized section subject. Unable to view Students.');
    }
    
    if(is_null($staffId) || $staffId <= 0) {
        throw new IdNotFoundException('Unauthorized access! Cannot access Student grades.');
    }
    
    try {
        $controller = new teacherGradesController();
        $response = $controller->apiFetchSectionSubjectStudents($sectionSubjectId, $staffId);
        
        // Log if response is empty or invalid for debugging
        if (empty($response) || !is_array($response)) {
            error_log('Empty or invalid response from controller. SectionSubjectId: ' . $sectionSubjectId . ', StaffId: ' . $staffId);
            $response = [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Controller returned empty or invalid response',
                'data' => []
            ];
        }
    } catch (Throwable $controllerError) {
        error_log('Controller error: ' . $controllerError->getMessage() . ' in ' . $controllerError->getFile() . ':' . $controllerError->getLine());
        throw $controllerError;
    }

    // Ensure response has all required fields
    if (!is_array($response)) {
        $response = [
            'httpcode' => 500,
            'success' => false,
            'message' => 'Invalid response format from controller',
            'data' => []
        ];
    }
    
    if (!isset($response['httpcode'])) {
        $response['httpcode'] = 200;
    }
    
    if (!isset($response['success'])) {
        $response['success'] = false;
    }
    
    if (!isset($response['message'])) {
        $response['message'] = '';
    }
    
    if (!isset($response['data'])) {
        $response['data'] = [];
    }

    // Clean any output buffer and send JSON
    ob_clean();
    http_response_code($response['httpcode']);
    
    // Log response for debugging
    error_log('Sending response: ' . print_r($response, true));
    
    $jsonResponse = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    if ($jsonResponse === false) {
        error_log('JSON encode error: ' . json_last_error_msg() . ' - Response: ' . print_r($response, true));
        ob_clean();
        $jsonResponse = json_encode([
            'success' => false,
            'message' => 'Error encoding response: ' . json_last_error_msg(),
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
    }
    
    if (empty($jsonResponse)) {
        error_log('JSON response is empty after encoding');
        $jsonResponse = json_encode([
            'success' => false,
            'message' => 'Response encoding resulted in empty string',
            'data' => []
        ], JSON_UNESCAPED_UNICODE);
    }
    
    echo $jsonResponse;
    $outputLength = ob_get_length();
    error_log('Output length before flush: ' . $outputLength);
    ob_end_flush();
    exit();
}
catch(IdNotFoundException $e) {
    ob_clean();
    http_response_code(403);
    echo json_encode([
        'success'=> false,
        'message'=> $e->getMessage(), 
        'data'=> []
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit();
}
catch(Throwable $e) {
    ob_clean();
    error_log('fetchSectionSubjectStudents Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    error_log('Stack trace: ' . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success'=> false, 
        'message'=> 'An error occurred while fetching students: ' . $e->getMessage(),
        'data'=> []
    ], JSON_UNESCAPED_UNICODE);
    ob_end_flush();
    exit();
}