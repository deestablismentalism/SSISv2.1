<?php
require_once  __DIR__ . "/../common/loginModel.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $User_Typed_Phone_Number = $_POST['phone_number'];
        $User_Typed_Password = $_POST['password'];

        $verification = new loginModel();
        $response = $verification->verify_login($User_Typed_Phone_Number, $User_Typed_Password);
        http_response_code($response['httpcode']);
        echo json_encode($response);
        exit();
    }
    catch(DatabaseException $e) {
        echo json_encode(['success'=> false,'message'=> $e->getMessage()]);
        exit();
    }
}
else {
    echo json_encode([
        'success' => false,
        'message' => 'An error occured, Please try again.',
        'session' => $_SESSION
    ]);
    exit();
}
?>