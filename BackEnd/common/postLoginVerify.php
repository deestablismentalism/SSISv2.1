<?php
    require_once  __DIR__ . "/./loginVerify2.php";
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: application/json');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $User_Typed_Phone_Number = $_POST['phone_number'];
    $User_Typed_Password = $_POST['password'];

    $verification = new VerifyLogin();
    $response = $verification->verify_login($User_Typed_Phone_Number, $User_Typed_Password);
    echo json_encode($response);
    }
    else {
        echo json_encode([
            'success' => false,
            'message' => 'An error occured, Please try again.',
            'session' => $_SESSION
        ]);
        exit;
    }
?>