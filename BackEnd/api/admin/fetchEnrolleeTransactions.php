<?php
require_once __DIR__ . '/../../core/dbconnection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$id = (int) $_POST['id'];

try {
    $db = new Connect();
    $conn = $db->getConnection();
    $sql = "SELECT Enrollment_Transaction_Id, Remarks, Enrollment_Status, Can_Resubmit, Need_Consultation FROM enrollment_transactions WHERE Enrollee_Id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No transactions found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
