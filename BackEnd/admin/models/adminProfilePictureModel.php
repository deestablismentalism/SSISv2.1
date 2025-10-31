<?php
require_once __DIR__ . '/../../core/dbconnection.php';

class adminProfilePictureModel {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    
    public function fetchProfilePicture($userId) {
        try {
            $sql = "SELECT pd.Directory, pd.File_Name 
                    FROM users u 
                    LEFT JOIN profile_directory pd ON u.Profile_Picture_Id = pd.Profile_Picture_Id 
                    WHERE u.User_Id = :userId";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."] Database error fetching profile picture: " . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            return null;
        }
    }
}
