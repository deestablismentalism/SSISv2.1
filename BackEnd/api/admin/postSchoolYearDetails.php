<?php
declare(strict_types=1);
require_once __DIR__ . '/../../admin/controllers/adminSystemManagementController.php';
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false,'message'=> 'Invalid Request method']);
    exit();
}
$yearStart = $_POST['school-year-start'] ?? null;
$yearEnd = $_POST['school-year-end'] ?? null;
$controller = new adminSystemManagementController();
$response = $controller->apiUpsertSchoolYearDetails($yearStart,$yearEnd);
http_response_code($response['httpcode']);
echo json_encode($response);
exit();