<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminTeacherController.php';
header('Content-Type: application/json');

try {
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success'=> false, 'message'=> 'Method not allowed']);
        exit();
    }
    
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
    
    if(!isset($data['assignments']) || !is_array($data['assignments'])) {
        http_response_code(400);
        echo json_encode(['success'=> false, 'message'=> 'Invalid data format']);
        exit();
    }
    
    $controller = new adminTeacherController();
    $successCount = 0;
    $failedCount = 0;
    $errors = [];
    
    foreach($data['assignments'] as $assignment) {
        $staffId = isset($assignment['staff_id']) ? (int)$assignment['staff_id'] : null;
        $sectionSubjectId = isset($assignment['section_subject_id']) ? (int)$assignment['section_subject_id'] : 0;
        
        if(empty($sectionSubjectId)) {
            $failedCount++;
            $errors[] = "Invalid section subject ID";
            continue;
        }
        
        $response = $controller->apiPostAssignTeacher($staffId, $sectionSubjectId);
        
        if($response['success']) {
            $successCount++;
        } else {
            $failedCount++;
            $errors[] = $response['message'];
        }
    }
    
    if($successCount > 0 && $failedCount === 0) {
        http_response_code(200);
        echo json_encode([
            'success'=> true,
            'message'=> "Successfully assigned {$successCount} teacher(s)",
            'data'=> ['success_count'=> $successCount]
        ]);
    } elseif($successCount > 0 && $failedCount > 0) {
        http_response_code(207);
        echo json_encode([
            'success'=> true,
            'message'=> "Partially successful: {$successCount} succeeded, {$failedCount} failed",
            'data'=> [
                'success_count'=> $successCount,
                'failed_count'=> $failedCount,
                'errors'=> $errors
            ]
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success'=> false,
            'message'=> 'All assignments failed',
            'data'=> ['errors'=> $errors]
        ]);
    }
    exit();
}
catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success'=> false,
        'message'=> 'Server error: ' . $e->getMessage()
    ]);
    exit();
}
