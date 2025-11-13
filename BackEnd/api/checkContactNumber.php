<?php
declare(strict_types=1);
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../core/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$contactNumber = $_POST['contact_number'] ?? null;

if (empty($contactNumber)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Contact number is required']);
    exit();
}

// Validate phone number format
if (!preg_match('/^09\d{9}$/', $contactNumber)) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid phone number format. Please use 09XXXXXXXXX.'
    ]);
    exit();
}

try {
    $db = new Connect();
    $conn = $db->getConnection();
    
    // Check staffs table
    $staffStmt = $conn->prepare("
        SELECT Staff_Contact_Number 
        FROM staffs 
        WHERE Staff_Contact_Number = :contact_number
        LIMIT 1
    ");
    $staffStmt->execute([':contact_number' => $contactNumber]);
    $staffExists = $staffStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($staffExists) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'exists' => true,
            'table' => 'staffs',
            'message' => 'This contact number is already registered as a staff member.'
        ]);
        exit();
    }
    
    // Check registrations table
    $registrationStmt = $conn->prepare("
        SELECT Contact_Number 
        FROM registrations 
        WHERE Contact_Number = :contact_number
        LIMIT 1
    ");
    $registrationStmt->execute([':contact_number' => $contactNumber]);
    $registrationExists = $registrationStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($registrationExists) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'exists' => true,
            'table' => 'registrations',
            'message' => 'This contact number is already registered in the system.'
        ]);
        exit();
    }
    
    // Contact number is available
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'exists' => false,
        'table' => null,
        'message' => 'Contact number is available'
    ]);
    
} catch (PDOException $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Contact number check error: " . $e->getMessage() . "\n", 3, __DIR__ . '/../errorLogs.txt');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while checking the contact number. Please try again.'
    ]);
} catch (Exception $e) {
    error_log("[" . date('Y-m-d H:i:s') . "] Contact number check error: " . $e->getMessage() . "\n", 3, __DIR__ . '/../errorLogs.txt');
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An unexpected error occurred. Please try again.'
    ]);
}
exit();
