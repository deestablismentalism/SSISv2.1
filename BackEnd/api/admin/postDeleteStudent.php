<?php
declare(strict_types=1);
require_once __DIR__ . '/../server_side/dbconnection.php';

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

// Check if student ID is provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    $response['message'] = 'Student ID is required';
    echo json_encode($response);
    exit;
}

$studentId = intval($_POST['id']);

try {
    // Create database connection
    $db = new Connect();
    $conn = $db->getConnection();
    
    // Start transaction
    $conn->beginTransaction();
    
    // First, get the Enrollee_Id to use in the next query
    $sql = "SELECT Enrollee_Id FROM students WHERE Enrollee_Id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
    $stmt->execute();
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        $response['message'] = 'Student not found';
        echo json_encode($response);
        exit;
    }
    
    // Delete from students table
    $sql = "DELETE FROM students WHERE Enrollee_Id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Commit the transaction
    $conn->commit();
    
    $response['success'] = true;
    $response['message'] = 'Student deleted successfully';
    
} catch (PDOException $e) {
    // Rollback the transaction on error
    if ($conn) {
        $conn->rollBack();
    }
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response); 