<?php
session_start();
require_once __DIR__ . '/../core/dbconnection.php';

Class loginModel {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function verify_login($User_Typed_Phone_Number, $User_Typed_Password) : array {
        $User_Password = null;
        $User_Typed_Password = trim($User_Typed_Password);
        $User_Typed_Phone_Number = trim($User_Typed_Phone_Number);

        $sql_find_staff = "SELECT 
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
                                WHERE staffs.Staff_Contact_Number = :Contact_Number";
                $find_staff = $this->conn->prepare($sql_find_staff);
                $find_staff->bindParam(':Contact_Number', $User_Typed_Phone_Number);
                $find_staff->execute();
                $staffResult = $find_staff->fetch(PDO::FETCH_ASSOC);

        if($staffResult && password_verify($User_Typed_Password, trim($staffResult['Password']))) {
            unset($_SESSION['User']);
            $_SESSION['Staff'] = [
                'User-Id' => $staffResult['User_Id'],
                'Staff-Id' => $staffResult['staff_staff_id'],
                'First-Name' => $staffResult['Staff_First_Name'],
                'Last-Name' => $staffResult['Staff_Last_Name'],
                'Contact-Number' => $staffResult['Staff_Contact_Number'],
                'User-Type' => $staffResult['User_Type'],
                'Staff-Type' => $staffResult['Staff_Type']
            ];
            return[
                'httpcode'=> 201,
                'success' => true,
                'message' => 'Staff found',
                'session' => $_SESSION
            ];
        }
        
        $sql_find_information = "SELECT users.*, registrations.* FROM users 
                                    JOIN registrations ON users.Registration_Id = registrations.Registration_Id
                                    WHERE registrations.Contact_Number = :Contact_Number;";
        $find_information = $this->conn->prepare($sql_find_information);
        $find_information->bindparam(':Contact_Number', $User_Typed_Phone_Number);
        $find_information->execute();
        $user = $find_information->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($User_Typed_Password, trim($user['Password']))) {
            unset($_SESSION['Staff']);
            $_SESSION['User'] = [
                'User-Id' => $user['User_Id'],
                'Registration-Id' => $user['Registration_Id'],
                'First-Name' => $user['First_Name'],
                'Last-Name' => $user['Last_Name'],
                'Middle-Name' => $user['Middle_Name'],
                'Contact-Number' => $user['Contact_Number'],
                'User-Type' => $user['User_Type']
            ];
            return [
                'httpcode'=> 201,
                'success' => true,
                'message' => 'User found',
                'session' => $_SESSION
            ];
        }
        if($staffResult || $user) {
            return[
                'httpcode'=> 400,
                'success' => false,
                'message' => 'Incorrect password. Check your password and try again'
            ];
        }
        else {
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'No account found. Check your number'
            ];
        }
    }
}
?>