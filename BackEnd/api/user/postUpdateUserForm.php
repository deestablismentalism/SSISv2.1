<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/userEditFormModel.php';

header('Content-Type: application/json');
// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

// Get POST data
$postData = json_decode(file_get_contents('php://input'), true);

// Debug: Log specific fields we're having issues with
error_log('LRN value received: ' . ($postData['lrn'] ?? 'not set'));
error_log('PSA value received: ' . ($postData['psa'] ?? 'not set'));
error_log('PSA image data received: ' . (isset($postData['psa_image']) ? 'yes' : 'no'));

if (!isset($postData['enrolleeId'])) {
    echo json_encode(['success' => false, 'error' => 'Enrollee ID is required']);
    exit();
}

// Handle image upload if present
if (isset($postData['psa_image'])) {
    // Get the old image path to delete later
    $model = new userEditFormModel();
    $oldImageData = $model->getPSAImageData($postData['enrolleeId']);
    
    // Create new image directory if it doesn't exist
    $uploadDir = '../imageUploads/' . date('Y') . '/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $time = time();
    $randomString = bin2hex(random_bytes(5)); 
    $uniqueName = $postData['enrolleeId'] . "-" . $time . "-" . $randomString;
    $filename = $uniqueName . '.jpg';
    $filepath = $uploadDir . $filename;
    
    // Save the new image
    if (file_put_contents($filepath, base64_decode($postData['psa_image']))) {
        $postData['psa_image_data'] = [
            'filename' => $filename,
            'filepath' => $filepath
        ];
        
        // Delete old image if it exists
        if ($oldImageData && isset($oldImageData['filepath']) && file_exists($oldImageData['filepath'])) {
            unlink($oldImageData['filepath']);
        }

        // Add PSA image data to formData
        $formData['psa_image'] = [
            'filename' => $filename,
            'filepath' => $filepath
        ];
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save image']);
        exit();
    }
}

// Transform form field names to match database field names
$formData = [
    'first_name' => $postData['first_name'] ?? '',
    'last_name' => $postData['last_name'] ?? '',
    'middle_name' => $postData['middle_name'] ?? '',
    'extension' => $postData['extension'] ?? '',
    'lrn' => trim($postData['lrn'] ?? ''),  // Add trim to remove any whitespace
    'psa' => trim($postData['psa'] ?? ''),  // Add trim to remove any whitespace
    'age' => $postData['age'] ?? '',
    'birthdate' => $postData['birthdate'] ?? '',
    'sex' => $postData['sex'] ?? '',
    'religion' => $postData['religion'] ?? '',
    'native_language' => $postData['native_language'] ?? '',
    'belongs_in_cultural_group' => $postData['belongs_in_cultural_group'] ?? '0',
    'cultural_group' => $postData['cultural_group'] ?? '',
    'email_address' => $postData['email_address'] ?? '',
    'enrolling_grade_level' => $postData['enrolling_grade_level'] ?? '',
    'last_grade_level' => $postData['last_grade_level'] ?? '',
    'last_year_attended' => $postData['last_year_attended'] ?? '',
    'last_school_attended' => $postData['last_school_attended'] ?? '',
    'school_id' => $postData['school_id'] ?? '',
    'school_address' => $postData['school_address'] ?? '',
    'school_type' => $postData['school_type'] ?? '',
    'region' => $postData['region'] ?? '',
    'region_name' => $postData['region_name'] ?? '',
    'province' => $postData['province'] ?? '',
    'province_name' => $postData['province_name'] ?? '',
    'city-municipality' => $postData['city-municipality'] ?? '',
    'city_municipality_name' => $postData['city_municipality_name'] ?? '',
    'barangay' => $postData['barangay'] ?? '',
    'barangay_name' => $postData['barangay_name'] ?? '',
    'subdivision' => $postData['subdivision'] ?? '',
    'house_number' => $postData['house_number'] ?? '',
    'has_a_special_condition' => $postData['has_a_special_condition'] ?? '0',
    'special_condition' => $postData['special_condition'] ?? '',
    'has_assistive_technology' => $postData['has_assistive_technology'] ?? '0',
    'assistive_technology' => $postData['assistive_technology'] ?? ''
];

// Add parent information
$formData['parent_information'] = [
    'father' => [
        'first_name' => $postData['father_first_name'] ?? '',
        'middle_name' => $postData['father_middle_name'] ?? '',
        'last_name' => $postData['father_last_name'] ?? '',
        'educational_attainment' => $postData['father_educational_attainment'] ?? '',
        'contact_number' => $postData['father_contact_number'] ?? '',
        'if_4ps' => $postData['father_4ps_member'] ?? '0'
    ],
    'mother' => [
        'first_name' => $postData['mother_first_name'] ?? '',
        'middle_name' => $postData['mother_middle_name'] ?? '',
        'last_name' => $postData['mother_last_name'] ?? '',
        'educational_attainment' => $postData['mother_educational_attainment'] ?? '',
        'contact_number' => $postData['mother_contact_number'] ?? '',
        'if_4ps' => $postData['mother_4ps_member'] ?? '0'
    ],
    'guardian' => [
        'first_name' => $postData['guardian_first_name'] ?? '',
        'middle_name' => $postData['guardian_middle_name'] ?? '',
        'last_name' => $postData['guardian_last_name'] ?? '',
        'educational_attainment' => $postData['guardian_educational_attainment'] ?? '',
        'contact_number' => $postData['guardian_contact_number'] ?? '',
        'if_4ps' => $postData['guardian_4ps_member'] ?? '0'
    ]
];

// Log the final form data before processing
error_log('Final form data before update: ' . print_r($formData, true));

// Basic validation
$requiredFields = ['first_name', 'last_name', 'lrn', 'birthdate', 'sex'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (empty($formData[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    echo json_encode([
        'success' => false, 
        'error' => 'Required fields are missing: ' . implode(', ', $missingFields)
    ]);
    exit();
}

// Process the update
$model = new userEditFormModel();
$result = $model->updateEnrolleeData($postData['enrolleeId'], $formData);

echo json_encode($result);
exit(); 