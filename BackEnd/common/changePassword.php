<?php
require_once '..\core\dbconnection.php';
session_start();    

class ChangePassword {
    protected $conn;
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function change_passwordUsers($User_Typed_Password, $User_New_Password, $User_New_Password_Confirm) {
        try {
            $User_Id = $_SESSION['User']['User-Id'];

            $sql = "SELECT Password FROM users WHERE User_Id = :User_Id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':User_Id', $User_Id, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                return [
                    'success' => false,
                    'message' => 'Database error while fetching user.'
                ];
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            }

            $storedPassword = $result['Password'];
            $User_Typed_Password = trim($User_Typed_Password);

            if (!password_verify($User_Typed_Password, $storedPassword)) {
                return [
                    'success' => false,
                    'message' => 'Incorrect password.'
                ];
            }

            if ($User_New_Password !== $User_New_Password_Confirm) {
                return [
                    'success' => false,
                    'message' => 'New password and confirmation do not match.'
                ];
            }

            if (strlen($User_New_Password) < 8) {
                return [
                    'success' => false,
                    'message' => 'New password must be at least 8 characters long.'
                ];
            }

            $hashed_password = password_hash($User_New_Password, PASSWORD_DEFAULT);

            $sql_update = "UPDATE users SET Password = :Password WHERE User_Id = :User_Id";
            $update = $this->conn->prepare($sql_update);
            $update->bindParam(':Password', $hashed_password, PDO::PARAM_STR);
            $update->bindParam(':User_Id', $User_Id, PDO::PARAM_INT);

            if ($update->execute()) {
                return [
                    'success' => true,
                    'message' => 'Password changed successfully.'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to change password.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }

    public function change_passwordAdmins($User_Typed_Password_Admin, $User_New_Password_Admin, $User_New_Password_Confirm_Admin) {
        try {
            $User_Id = $_SESSION['Staff']['User-Id'];
            $Contact_Number = $_SESSION['Staff']['Contact-Number'];

            $sql = "SELECT Password FROM users 
                    JOIN staffs ON users.Staff_Id = staffs.Staff_Id
                    WHERE staffs.Staff_Contact_Number = :Contact_Number";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':Contact_Number', $Contact_Number, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                return [
                    'success' => false,
                    'message' => 'Database error while fetching admin.'
                ];
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Admin not found.'
                ];
            }

            $storedPassword = $result['Password'];
            $User_Typed_Password_Admin = trim($User_Typed_Password_Admin);

            if (!password_verify($User_Typed_Password_Admin, $storedPassword)) {
                return [
                    'success' => false,
                    'message' => 'Incorrect password.'
                ];
            }

            if ($User_New_Password_Admin !== $User_New_Password_Confirm_Admin) {
                return [
                    'success' => false,
                    'message' => 'New password and confirmation do not match.'
                ];
            }

            if (strlen($User_New_Password_Admin) < 8) {
                return [
                    'success' => false,
                    'message' => 'New password must be at least 8 characters long.'
                ];
            }

            $hashed_password = password_hash($User_New_Password_Admin, PASSWORD_DEFAULT);

            $sql_update = "UPDATE users SET Password = :Password WHERE User_Id = :User_Id";
            $update = $this->conn->prepare($sql_update);
            $update->bindParam(':Password', $hashed_password, PDO::PARAM_STR);
            $update->bindParam(':User_Id', $User_Id, PDO::PARAM_INT);

            if ($update->execute()) {
                return [
                    'success' => true,
                    'message' => 'Password changed successfully.'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to change password.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }
}
