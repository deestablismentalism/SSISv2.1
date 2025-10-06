<?php
require_once __DIR__ . '/../core/dbconnection.php';
require_once __DIR__ .  '/../common/sendPassword.php';

class adminStaffRegistration {
    protected $conn;
    
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    
    public function staffInformation($Staff_First_Name, $Staff_Middle_Name, $Staff_Last_Name, $Staff_Email, $Staff_Contact_Number, $Staff_Status, $Staff_Type) {
        $sql_insert_staff_info = "INSERT INTO staffs(Staff_First_Name, Staff_Middle_Name, Staff_Last_Name, Staff_Email, Staff_Contact_Number, Staff_Status, Staff_Type) 
                                VALUES (:Staff_First_Name, :Staff_Middle_Name, :Staff_Last_Name, :Staff_Email, :Staff_Contact_Number, :Staff_Status, :Staff_Type)";
        
        try {
            $insert_staff_info = $this->conn->prepare($sql_insert_staff_info);
            $insert_staff_info->bindParam(':Staff_First_Name', $Staff_First_Name);
            $insert_staff_info->bindParam(':Staff_Middle_Name', $Staff_Middle_Name);
            $insert_staff_info->bindParam(':Staff_Last_Name', $Staff_Last_Name);
            $insert_staff_info->bindParam(':Staff_Email', $Staff_Email);
            $insert_staff_info->bindParam(':Staff_Contact_Number', $Staff_Contact_Number);
            $insert_staff_info->bindParam(':Staff_Status', $Staff_Status);
            $insert_staff_info->bindParam(':Staff_Type', $Staff_Type);

            if ($insert_staff_info->execute()) {
                return $this->conn->lastInsertId();
            } else {
                throw new PDOException('Staff information insert failed.');
            }
        } catch (PDOException $e) {
            // Throw the exception to let registerStaff handle it
            throw $e;
        }
    }

    public function registerStaff($Staff_First_Name, $Staff_Middle_Name, $Staff_Last_Name, $Staff_Email, $Staff_Contact_Number, $Staff_Status, $Staff_Type) {
        try {
            $this->conn->beginTransaction();

            if (!preg_match('/^09\d{9}$/', $Staff_Contact_Number)) {
                $this->conn->rollBack();
                echo ("Invalid phone number format. Please use a valid Philippine mobile number (09XXXXXXXXX).");
                return;
            }   
            // Call staffInformation() inside the transaction
            $Staff_Id = $this->staffInformation($Staff_First_Name, $Staff_Middle_Name, $Staff_Last_Name, $Staff_Email, $Staff_Contact_Number, $Staff_Status, $Staff_Type);

            // Generate password
            $password = $this->generatePassword();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into users table
            $sql_insert_staff_users = "INSERT INTO users(Password, User_Type, Staff_Id) 
                                    VALUES (:Password, :User_Type, :Staff_Id)";
            $insert_staff_users = $this->conn->prepare($sql_insert_staff_users);
            $insert_staff_users->bindParam(':Password', $hashed_password);
            $insert_staff_users->bindParam(':User_Type', $Staff_Type);
            $insert_staff_users->bindParam(':Staff_Id', $Staff_Id);

            if (!$insert_staff_users->execute()) {
                // Rollback the transaction if the insert into users fails
                $this->conn->rollBack();
                echo 'Registration Failed (Users Insert)';
                return;
            }

            // Send password
            try {
                $send_password = new SendPassword();
                $send_password->send_password($Staff_Last_Name, $Staff_First_Name, $Staff_Middle_Name, $Staff_Contact_Number, $password);
            } catch (Exception $e) {
                $this->conn->rollBack();
                echo 'Failed to send password: ' . $e->getMessage();
                return;
            }

            // Commit the transaction if everything is successful
            $this->conn->commit();
            echo 'Registration Successful';
        } catch (PDOException $e) {
            $this->conn->rollBack();

            // Handle duplicate entry error specifically
            if ($e->errorInfo[1] == 1062) {
                echo 'Registration failed: The number you entered is already registered';
            } else {
                echo 'Error: ' . $e->getMessage();
            }
        }
    }

    private function generatePassword() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
}
?>

