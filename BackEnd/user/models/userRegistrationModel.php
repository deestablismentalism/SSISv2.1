<?php
require_once 'dbconnection.php';
require_once 'send_password.php';

class Registration {
    protected $conn;
    
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    public function register($First_Name, $Last_Name, $Middle_Name, $Contact_Number, $User_Type) {
        $this->conn->beginTransaction();
        try {
            // Validate phone number
            if (!preg_match('/^09\d{9}$/', $Contact_Number)) {
                throw new Exception("Invalid phone number format. Please use 09XXXXXXXXX.");
            }
    
            // Insert into registrations
            $sql_insert_registers = "INSERT INTO registrations(First_Name, Last_Name, Middle_Name, Contact_Number)
                                    VALUES (:First_Name, :Last_Name, :Middle_Name, :Contact_Number)";
            $insert_register = $this->conn->prepare($sql_insert_registers);
            $insert_register->bindParam(':First_Name', $First_Name);
            $insert_register->bindParam(':Last_Name', $Last_Name);
            $insert_register->bindParam(':Middle_Name', $Middle_Name);
            $insert_register->bindParam(':Contact_Number', $Contact_Number);
            if (!$insert_register->execute()) {
                throw new Exception("Failed to insert registration data.");
            }
            $Registration_Id = $this->conn->lastInsertId();
    
            // Generate password and insert into users
            $password = $this->generatePassword();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
            $sql_insert_password = "INSERT INTO users(Registration_Id, Password, User_Type)
                                    VALUES (:Registration_Id, :Password, :User_Type)";
            $insert_password = $this->conn->prepare($sql_insert_password);
            $insert_password->bindParam(':Registration_Id', $Registration_Id);
            $insert_password->bindParam(':Password', $hashed_password);
            $insert_password->bindParam(':User_Type', $User_Type);
            if (!$insert_password->execute()) {
                throw new Exception("Failed to insert user data.");
            }
    
            // Send password via SMS
            $send_password = new SendPassword();
            $send_password->send_password($Last_Name, $First_Name, $Middle_Name, $Contact_Number, $password);
    
            $this->conn->commit();
    
            return [
                'success' => true,
                'message' => "Registration successful! Your password will be sent to your mobile number shortly.",
                'contact_number' => $Contact_Number
            ];
        }
    
        // Catch DB-related exceptions
        catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->errorInfo[1] === 1062) {
                return [
                    'success' => false,
                    'message' => 'Registration failed: The number you entered is already registered.',
                    'error' => 'duplicate_entry'
                ];
            }
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'error' => 'database'
            ];
        }
    
        // Catch everything else (including SMS failures)
        catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'error' => 'registration_error'
            ];
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