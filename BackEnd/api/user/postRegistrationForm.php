<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'backend_registration.php';
header("Content-Type: application/json");
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $registration = new Registration();
        
        // PERSONAL INFORMATION
        $First_Name = $_POST['Guardian-First-Name'] ?? "";
        $Last_Name = $_POST['Guardian-Last-Name'] ?? "";
        $Middle_Name = $_POST['Guardian-Middle-Name'] ?? "";
        $Contact_Number = $_POST['Contact-Number'] ?? "";
        $User_Type = "3";
        
        if (empty($First_Name) || empty($Last_Name) || empty($Contact_Number)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please fill in all required fields'
            ]);
            exit;
        }
        
        $result = $registration->register($First_Name, $Last_Name, $Middle_Name, $Contact_Number, $User_Type);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Registration failed: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>