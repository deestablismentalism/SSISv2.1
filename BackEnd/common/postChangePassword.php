<?php
require_once 'change_password.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['User']) && isset($_SESSION['User']['User-Type']) && $_SESSION['User']['User-Type'] == 3) {
        $User_Typed_Password = $_POST['old-password'];
        $User_New_Password = $_POST['new-password'];
        $User_New_Password_Confirm = $_POST['confirm-password'];
    
        $change_password = new ChangePassword();
        $response = $change_password->change_passwordUsers($User_Typed_Password, $User_New_Password, $User_New_Password_Confirm);
        echo json_encode($response);
        exit;
    } 
    else if (isset($_SESSION['Admin']) && in_array($_SESSION['Admin']['User-Type'], [1, 2, 3])){
        $User_Typed_Password = $_POST['old-password'];
        $User_New_Password = $_POST['new-password'];
        $User_New_Password_Confirm = $_POST['confirm-password'];

        $change_password = new ChangePassword();
        $response = $change_password->change_passwordAdmins($User_Typed_Password, $User_New_Password, $User_New_Password_Confirm);
        echo json_encode($response);
        exit;
    }
    else {
        echo json_encode([
            'success' => false,
            'message' => 'User session not found or invalid user type.',
            'session' => $_SESSION
        ]);
        exit;
    }
}
?>