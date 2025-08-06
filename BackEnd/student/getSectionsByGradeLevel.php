<?php
declare(strict_types=1);
require_once __DIR__ . '/../server_side/studentsModel.php';

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'sections' => []
);

// Check if grade level ID is provided
if (!isset($_GET['grade_level_id']) || empty($_GET['grade_level_id'])) {
    $response['message'] = 'Grade level ID is required';
    echo json_encode($response);
    exit;
}

$gradeLevelId = intval($_GET['grade_level_id']);

try {
    // Create studentsModel instance
    $studentModel = new studentsModel();
    
    // Get sections for the grade level
    $sections = $studentModel->getSectionsByGradeLevel($gradeLevelId);
    
    $response['success'] = true;
    $response['sections'] = $sections;
    
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response); 