<?php
session_start();
require_once __DIR__ . '/../../core/dbconnection.php';

header('Content-Type: application/json');

try {
    $db = new Connect();
    $conn = $db->getConnection();
    
    // Count all enrollees
    $sql = "SELECT Enrollment_Status, COUNT(*) as count FROM enrollee GROUP BY Enrollment_Status";
    $stmt = $conn->query($sql);
    $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get total count
    $totalSql = "SELECT COUNT(*) as total FROM enrollee";
    $totalStmt = $conn->query($totalSql);
    $total = $totalStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get recent enrollees
    $recentSql = "SELECT Enrollee_Id, Student_First_Name, Student_Last_Name, Learner_Reference_Number, 
                         Enrollment_Status, User_Id, Enrolled_At 
                  FROM enrollee 
                  ORDER BY Enrollee_Id DESC 
                  LIMIT 10";
    $recentStmt = $conn->query($recentSql);
    $recent = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get enrollees with null User_Id (admin-created)
    $adminCreatedSql = "SELECT COUNT(*) as admin_created FROM enrollee WHERE User_Id IS NULL";
    $adminCreatedStmt = $conn->query($adminCreatedSql);
    $adminCreated = $adminCreatedStmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total_enrollees' => $total['total'],
        'status_breakdown' => $statusCounts,
        'admin_created_count' => $adminCreated['admin_created'],
        'recent_enrollees' => $recent,
        'status_legend' => [
            '1' => 'Enrolled/Approved',
            '2' => 'Rejected',
            '3' => 'Pending',
            '4' => 'Archived'
        ]
    ], JSON_PRETTY_PRINT);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
