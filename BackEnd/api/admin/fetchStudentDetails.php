<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'student' => null
);

// Check if student ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = 'Student ID is required';
    echo json_encode($response);
    exit;
}

$studentId = intval($_GET['id']);

try {
    $db = new Connect();
    $conn = $db->getConnection();
    
    $sql = "SELECT s.Student_Id, s.Enrollee_Id, s.Grade_Level_Id, s.Section_Id, s.Student_Status,
                   e.Student_First_Name, e.Student_Middle_Name, e.Student_Last_Name, 
                   e.Learner_Reference_Number, e.Student_Email,
                   g.Grade_Level, se.Section_Name,
                   ds.Have_Special_Condition, ds.Special_Condition, 
                   ds.Have_Assistive_Tech, ds.Assistive_Tech
            FROM students AS s
            LEFT JOIN enrollee AS e ON s.Enrollee_Id = e.Enrollee_Id
            LEFT JOIN grade_level AS g ON s.Grade_Level_Id = g.Grade_Level_Id
            LEFT JOIN sections AS se ON s.Section_Id = se.Section_Id
            LEFT JOIN disabled_student AS ds ON e.Disabled_Student_Id = ds.Disabled_Student_Id
            WHERE s.Enrollee_Id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
    $stmt->execute();
    
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        $response['success'] = true;
        $response['student'] = $student;
    } else {
        $response['message'] = 'Student not found';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response); 