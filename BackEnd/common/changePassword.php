<?php
require_once 'dbconnection.php';
session_start();    

class ChangePassword {
    protected $conn;
    
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function change_passwordUsers($User_Typed_Password, $User_New_Password, $User_New_Password_Confirm) {
        $User_Id = $_SESSION['User']['User-Id'];
        $sql_find_information = "SELECT users.*, registrations.* FROM users 
                                    JOIN registrations ON users.Registration_Id = registrations.Registration_Id
                                    WHERE users.User_Id = :User_Id;";
        $find_information = $this->conn->prepare($sql_find_information);
        $find_information->bindparam(':User_Id', $User_Id);
        if ($find_information->execute()) {
            $result = $find_information->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $User_Password = $result['Password'];
                $User_Password = trim($User_Password);
                $User_Typed_Password = trim($User_Typed_Password);
                if (password_verify($User_Typed_Password, $User_Password)) {
                    if ($User_New_Password === $User_New_Password_Confirm) {
                        $hashed_password = password_hash($User_New_Password, PASSWORD_DEFAULT);

                        $sql_update_password = "UPDATE users SET Password = :Password WHERE User_Id = :User_Id;";
                        $update_password = $this->conn->prepare($sql_update_password);
                        $update_password->bindparam(':Password', $hashed_password);
                        $update_password->bindparam(':User_Id', $User_Id);
                        
                        if ($update_password->execute()) {
                            return [
                                'success' => true,
                                'message' => 'Password changed successfully.'
                            ];
                        } 
                        
                        else {
                            return  [
                                'success' => false,
                                'message' => 'Failed to change password.'
                            ];
                        }
                    } 
                    
                    else {
                        return [
                            'success' => false,
                            'message' => 'New password and confirmation do not match.'
                        ];  
                    }
                }
                
                else {
                    return[
                        'success' => false,
                        'message' => "Incorrect password."
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            }
        }
    }


    public function change_passwordAdmins($User_Typed_Password_Admin, $User_New_Password_Admin, $User_New_Password_Confirm_Admin) {
        $User_Id = $_SESSION['Admin']['User-Id'];
        $Contact_Number = $_SESSION['Admin']['Contact-Number'];
        $sql_find_information = "SELECT 
                                    users.User_Id AS User_Id,
                                    users.Password,
                                    users.Staff_Id AS user_staff_id,
                                    staffs.Staff_Id AS staff_staff_id,
                                    staffs.Staff_First_Name,
                                    staffs.Staff_Last_Name,
                                    staffs.Staff_Contact_Number,
                                    staffs.Staff_Type,
                                    users.User_Type
                                FROM users 
                                JOIN staffs ON users.Staff_Id = staffs.Staff_Id
                                WHERE staffs.Staff_Contact_Number = :Contact_Number;";
        $find_information = $this->conn->prepare($sql_find_information);
        $find_information->bindparam(':Contact_Number', $Contact_Number);
        if ($find_information->execute()) {
            $result = $find_information->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $User_Password = $result['Password'];
                $User_Password = trim($User_Password);
                $User_Typed_Password_Admin = trim($User_Typed_Password_Admin);
                if (password_verify($User_Typed_Password_Admin, $User_Password)) {
                    if ($User_New_Password_Admin === $User_New_Password_Confirm_Admin) {
                        $hashed_password = password_hash($User_New_Password_Admin, PASSWORD_DEFAULT);

                        $sql_update_password = "UPDATE users SET Password = :Password WHERE User_Id = :User_Id;";
                        $update_password = $this->conn->prepare($sql_update_password);
                        $update_password->bindparam(':Password', $hashed_password);
                        $update_password->bindparam(':User_Id', $User_Id);
                        
                        if ($update_password->execute()) {
                            return [
                                'success' => true,
                                'message' => 'Password changed successfully.'
                            ];
                        } 
                        
                        else {
                            return  [
                                'success' => false,
                                'message' => 'Failed to change password.'
                            ];
                        }
                    } 
                    
                    else {
                        return [
                            'success' => false,
                            'message' => 'New password and confirmation do not match.'
                        ];  
                    }
                }
                
                else {
                    return[
                        'success' => false,
                        'message' => "Incorrect password."
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            }
        }
    }
}