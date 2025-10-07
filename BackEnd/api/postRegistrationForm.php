<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../common/registration.php';
header("Content-Type: application/json");
    
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
    exit();
}
    $controller = new Registration();
        
    // PERSONAL INFORMATION
    $First_Name = $_POST['Guardian-First-Name'] ?? null;
    $Last_Name = $_POST['Guardian-Last-Name'] ?? null;
    $Middle_Name = $_POST['Guardian-Middle-Name'] ?? null;
    $Contact_Number = $_POST['Contact-Number'] ?? null;
    $User_Type = 3;
    
    
    $response = $controller->registerAndInsert($First_Name, $Last_Name, $Middle_Name, $Contact_Number, $User_Type);
    http_response_code($response['httpcode']);
    echo json_encode($response);
    exit(); 

?>