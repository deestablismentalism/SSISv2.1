<?php
declare(strict_types=1);
require_once __DIR__ . '/../core/dbconnection.php';
require_once __DIR__ . '/./sendPassword.php';
require_once __DIR__ . '/../Exceptions/DatabaseException.php';

class Registration {
    protected $conn;
    
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    private function insertToRegistration(string $fName,string $lName, string $mName, string $cpNumber) : int {
        try {
            $sql = "INSERT INTO registrations(First_Name, Last_Name, Middle_Name, Contact_Number)
                                    VALUES (:First_Name, :Last_Name, :Middle_Name, :Contact_Number)";
           $stmt = $this->conn->prepare($sql);
           $stmt->bindParam(':First_Name', $fName);
           $stmt->bindParam(':Last_Name', $lName);
           $stmt->bindParam(':Middle_Name', $mName);
           $stmt->bindParam(':Contact_Number', $cpNumber);

            $result = $stmt->execute();
            if(!$result) {
                throw new DatabaseException('Something went wrong with resigtration', 0);
            }
            return (int)$this->conn->lastInsertId();
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to register user',0,$e);
        }
    }
    private function insertUser(int $registrationId, string $hashPassword, int $userType) : bool {
        try {
            $sql = "INSERT INTO users(Registration_Id, Password, User_Type)
                                    VALUES (:Registration_Id, :Password, :User_Type)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':Registration_Id', $registrationId);
            $stmt->bindParam(':Password', $hashPassword);
            $stmt->bindParam(':User_Type', $userType);

            $result = $stmt->execute();

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to insert user',0,$e);
        }
    }
    public function registerAndInsert(string $First_Name, string $Last_Name, string $Middle_Name, string $Contact_Number,int $userType) : array {
        $this->conn->beginTransaction();
        try {
            if(empty($First_Name) || empty($Last_Name)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> empty($First_Name) ? 'First name cannot be empty' : 'Last name cannot be empty',
                    'data'=> []
                ];
            }
            if(empty($Contact_Number)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Please enter a contact number',
                    'data'=> []
                ];
            }
            // Validate phone number format
            if (!preg_match('/^09\d{9}$/', $Contact_Number)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Invalid phone number format. Please use a valid Philippine mobile number (09XXXXXXXXX).',
                    'data'=> []
                ];
            }
            //Registration
            $registrationId = $this->insertToRegistration($First_Name, $Last_Name, $Middle_Name, $Contact_Number);
            // Generate and hash password
            $password = $this->generatePassword();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); 
            // Insert into users table
            $insertUser = $this->insertUser($registrationId,$hashed_password, $userType);
            if(!$insertUser) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'User insert failed',
                    'data'=> []
                ];
            }
            $send_password = new SendPassword();
            $isSent = $send_password->send_password($Last_Name, $First_Name, $Middle_Name, $Contact_Number, $password);
            if($isSent) {
                $this->conn->commit();
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'isSent'=> $isSent,
                    'message'=> 'Password sent'
                ];
            }
            $this->conn->rollBack();
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'Failed to send password',   
            ];
        }
        catch(SMSFailureException $e) {
            $this->conn->rollBack();
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'SMS ERROR: ' .$e->getMessage(),
                'data'=> []
            ];
        }
        catch(DatabaseException $e) {
            $this->conn->rollBack();
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' .$e->getMessage(),
                'data'=> []
            ];
        }
        catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->errorInfo[1] === 1062) {
                return [
                    'httpcode'=> 409,
                    'success' => false,
                    'message' => 'Registration failed: The number you entered is already registered',
                    'error' => 'duplicate_entry'
                ];
            }
            return [
                'httpcode'=> 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'error' => 'database'
            ];
        }
        
        catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'error' => 'registration_error'
            ];
        }
    }
    
    private function generatePassword() : string{
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < 8; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $password;
    }
}