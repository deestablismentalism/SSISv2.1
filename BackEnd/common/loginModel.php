<?php
session_start();
require_once __DIR__ . '/../core/dbconnection.php';
require_once __DIR__ . '/../Exceptions/DatabaseException.php';
date_default_timezone_set('Asia/Manila');
Class loginModel {
    protected $conn;
    
    public function __construct() {
        $this->checkConnection();
    }
    private function checkConnection(): void {
        try {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }
        catch(DatabaseConnectionException $e) {
            throw new DatabaseException(
            'There was a server problem. Please check your Internet connection and contact us upon confirming that you have a connection',
            $e->getCode(),$e);
        }
    }
    private function log_user(int $userId):bool {
        try {
            $sql = "INSERT INTO user_logs(User_Id,Logged_At) VALUES(:userId,:loggedAt)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':userId'=>$userId, ':loggedAt'=> date('Y-m-d H:i:s')]);
            if($stmt->rowCount()===0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt');
            throw new DatabaseException('Failed to log user',0,$e);
        }
    }
    private function log_teacher(int $userId):bool {
        try {
            $sql = "INSERT INTO teacher_logs(User_Id,Logged_At) VALUES(:userId, :loggedAt)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':userId'=>$userId, ':loggedAt'=> date('Y-m-d H:i:s')]);
            if($stmt->rowCount()===0) {
                return false;
            }
            return true;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt');
            throw new DatabaseException('Failed to log teacher',0,$e);
        }
    }
    private function checkStaffs(string $contactNumber):?array{
        try {
            $sql = "SELECT 
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
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':Contact_Number'=>$contactNumber]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        }
        catch(PDOException $e){
            error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt'); 
            throw new DatabaseException('Failed to check staffs',0,$e);
        }
    }
    private function checkUsers(string $contactNumber):?array{
        try {
            $sql = "SELECT users.*, registrations.* FROM users 
                                    JOIN registrations ON users.Registration_Id = registrations.Registration_Id
                                    WHERE registrations.Contact_Number = :Contact_Number";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':Contact_Number'=>$contactNumber]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: [];
        }
        catch(PDOException $e){
            error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt'); 
            throw new DatabaseException('Failed to check staffs',0,$e);
        }
    }
    public function verify_login($User_Typed_Phone_Number, $User_Typed_Password) : array {
        $User_Password = null;
        $User_Typed_Password = trim($User_Typed_Password);
        $User_Typed_Phone_Number = trim($User_Typed_Phone_Number);

        $staffResult = $this->checkStaffs($User_Typed_Phone_Number);

        if($staffResult && password_verify($User_Typed_Password, trim($staffResult['Password']))) {
            unset($_SESSION['User']);
            try {
                $_SESSION['Staff'] = [
                    'User-Id' => (int) $staffResult['User_Id'],
                    'Staff-Id' => $staffResult['staff_staff_id'],
                    'First-Name' => $staffResult['Staff_First_Name'],
                    'Last-Name' => $staffResult['Staff_Last_Name'],
                    'Contact-Number' => $staffResult['Staff_Contact_Number'],
                    'User-Type' => (int) $staffResult['User_Type'],
                    'Staff-Type' => (int) $staffResult['Staff_Type']
                ];
                if($_SESSION['Staff']['Staff-Type'] === 2) {
                    $isTeacherLogged = $this->log_teacher($_SESSION['Staff']['User-Id']);
                    if(!$isTeacherLogged) {
                        error_log('Teacher was not logged',3,__DIR__ . '/../errorLogst.txt');
                    }
                }
            }
            catch(DatabaseException $e) {
               error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt');  
            }
            return[
                'httpcode'=> 201,
                'success' => true,
                'message' => 'Staff found',
                'session' => $_SESSION
            ];
        }
        $user = $this->checkUsers($User_Typed_Phone_Number);
        if($user && password_verify($User_Typed_Password, trim($user['Password']))) {
            unset($_SESSION['Staff']);
            try {
                $_SESSION['User'] = [
                    'User-Id' => (int) $user['User_Id'],
                    'Registration-Id' => (int) $user['Registration_Id'],
                    'First-Name' => $user['First_Name'],
                    'Last-Name' => $user['Last_Name'],
                    'Middle-Name' => $user['Middle_Name'],
                    'Contact-Number' => $user['Contact_Number'],
                    'User-Type' => (int) $user['User_Type']
                ];
                $isUserLogged = $this->log_user($_SESSION['User']['User-Id']);
                if(!$isUserLogged) {
                    error_log('User was not logged',3,__DIR__ . '/../errorLogst.txt');
                }
            }
            catch(DatabaseException $e) {
                error_log("[".date('Y-m-d')."]" .$e->getMessage()."\n",3,__DIR__ . '/../errorLogs.txt');  
            }
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
