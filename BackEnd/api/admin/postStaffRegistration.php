<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controller/adminTeacherController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
    exit();
}
$controller = new adminTeacherController();

$firstName = $_POST['first-name'] ?? null;
$middleName = $_POST['middle-name'] ?? null;
$lastName = $_POST['last-name'] ?? null;
$staffEmail = $_POST['staff-email'] ?? null;
$contactNumber = $_POST['contact-number'] ?? null;
$status = 1;
$staffType = 2;

$response = $controller->apiPostRegisterTeacher($firstName, $middleName, $lastName, $staffEmail, $contactNumber, $status, $staffType);
http_response_code($response['httpcode']);
echo json_encode($response);
exit();

?>