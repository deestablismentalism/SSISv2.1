<?php
session_start();
require_once __DIR__ .'/../core/dbconnection.php';

Class VerifyLogin {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    public function verify_login($User_Typed_Phone_Number, $User_Typed_Password) {
        $User_Password = null;
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
        $find_information->bindparam(':Contact_Number', $User_Typed_Phone_Number);
        if ($find_information->execute()) { 
            $result = $find_information->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $User_Password = $result['Password'];
                $User_Password = trim($User_Password);
                $User_Typed_Password = trim($User_Typed_Password);
                if (password_verify($User_Typed_Password, $User_Password)) {

                    $_SESSION['Admin'] = [
                        'User-Id' => $result['User_Id'],
                        'Staff-Id' => $result['staff_staff_id'],
                        'First-Name' => $result['Staff_First_Name'],
                        'Last-Name' => $result['Staff_Last_Name'],
                        'Contact-Number' => $result['Staff_Contact_Number'],
                        'User-Type' => $result['User_Type'],
                        'Staff-Type' => $result['Staff_Type']
                    ];
                    return [
                        'success' => true,
                        'message' => 'Login successful.',
                    ];
                    exit();
                }
                
                else {
                    return [
                        'success' => false,
                        'message' => 'Incorrect Password.',
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            }
        }
    }
}

?>

