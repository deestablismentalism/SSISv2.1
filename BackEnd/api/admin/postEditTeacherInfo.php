<?php
require_once '../server_side/edit_teacher_info.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

class EditInformationController {
    protected $editInformation;
    protected $Staff_Id;
    public function __construct() {
        $this->editInformation = new EditInformation();
    }

    public function handleRequest() {
        if (isset($_POST['status']) && isset($_POST['position'])) {
            $ConvertStatus = $_POST['status'];
            $Position = $_POST['position'];
            $Staff_Id = $_POST['staff_id'];
            return $this->editInformation->editTeacherInformation($ConvertStatus, $Position, $Staff_Id);
        } else {
            return [
                'success' => false,
                'message' => 'Missing POST parameters.',
                'data' => $_POST, // Debugging output
            ];
        }
    }
}

try {
    $controller = new EditInformationController();
    $response = $controller->handleRequest();
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
    ]);
}
?>