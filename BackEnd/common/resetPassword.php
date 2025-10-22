<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../core/dbconnection.php';

class PasswordReset extends Connect {
    private $pdo;
    
    public function __construct() {
        parent::__construct();
        $this->pdo = $this->getConnection();
    }

    private function cleanPhoneNumber($phone) {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($cleaned, 0, 2) === '09') {
            return $cleaned;
        } else if (substr($cleaned, 0, 2) === '63') {
            return '0' . substr($cleaned, 2);
        }
        
        return $cleaned;
    }

    private function findUserByPhone($phone) {
        $cleaned = $this->cleanPhoneNumber($phone);
        
        $staffStmt = $this->pdo->prepare("
            SELECT u.User_Id, u.User_Type
            FROM users u
            INNER JOIN staffs s ON u.Staff_Id = s.Staff_Id
            WHERE s.Staff_Contact_Number = :phone AND u.Staff_Id IS NOT NULL
        ");
        $staffStmt->execute(['phone' => $cleaned]);
        $staffResult = $staffStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($staffResult) {
            return $staffResult;
        }
        
        $userStmt = $this->pdo->prepare("
            SELECT u.User_Id, u.User_Type
            FROM users u
            INNER JOIN registrations r ON u.Registration_Id = r.Registration_Id
            WHERE r.Contact_Number = :phone AND u.Registration_Id IS NOT NULL
        ");
        $userStmt->execute(['phone' => $cleaned]);
        $userResult = $userStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($userResult) {
            return $userResult;
        }
        
        return null;
    }

    private function verifyToken($phone, $token) {
        $userData = $this->findUserByPhone($phone);
        
        if (!$userData) {
            return null;
        }
        
        $stmt = $this->pdo->prepare("
            SELECT User_Id, Is_Used 
            FROM otp_verification
            WHERE User_Id = :user_id AND Token = :token
        ");
        $stmt->execute([
            'user_id' => $userData['User_Id'],
            'token' => $token
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data && $data['Is_Used'] == 1) {
            return $data['User_Id'];
        }
        
        return null;
    }

    public function resetPassword($phone, $newPassword, $token) {
        try {
            $userId = $this->verifyToken($phone, $token);
            
            if (!$userId) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired reset session. Please request a new OTP.'
                ];
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                UPDATE users 
                SET Password = :password, Must_Change_Password = 0 
                WHERE User_Id = :user_id
            ");
            
            $updateResult = $stmt->execute(['password' => $hashedPassword, 'user_id' => $userId]);
            
            if ($updateResult) {
                $deleteStmt = $this->pdo->prepare("
                    DELETE FROM otp_verification WHERE User_Id = :user_id AND Token = :token
                ");
                $deleteStmt->execute(['user_id' => $userId, 'token' => $token]);

                return [
                    'success' => true,
                    'message' => 'Password reset successfully! You can now login with your new password.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update password. Please try again.'
                ];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred while resetting password. Please try again.'
            ];
        }
    }
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $phone = $input['phone_number'] ?? '';
    $newPassword = $input['new_password'] ?? '';
    $token = $input['token'] ?? '';

    if (empty($phone) || empty($newPassword) || empty($token)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields'
        ]);
        exit;
    }

    $passwordReset = new PasswordReset();
    echo json_encode($passwordReset->resetPassword($phone, $newPassword, $token));

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error occurred'
    ]);
}
?>
