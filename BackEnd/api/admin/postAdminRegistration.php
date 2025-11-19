<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminTeacherController.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
    exit();
}
$controller = new adminTeacherController();

$firstName = $_POST['first-name'] ?? null;
$middleName = $_POST['middle-name'] ?? null;
$lastName = $_POST['last-name'] ?? null;
$adminEmail = $_POST['admin-email'] ?? null;
$contactNumber = $_POST['contact-number'] ?? null;

$response = $controller->apiPostRegisterAdmin($firstName, $middleName, $lastName, $adminEmail, $contactNumber);
http_response_code($response['httpcode']);
echo json_encode($response);
exit();

?>
