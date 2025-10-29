<?php
require_once __DIR__ . '/../models/adminProfilePictureModel.php';

class adminProfilePictureView {
    private $model;
    
    public function __construct() {
        $this->model = new adminProfilePictureModel();
    }
    
    public function getProfilePicturePath($userId) {
        try {
            $result = $this->model->fetchProfilePicture($userId);
            
            if ($result && isset($result['Directory']) && isset($result['File_Name'])) {
                return $result['Directory'] . $result['File_Name'];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] Error fetching profile picture: " . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            return null;
        }
    }
}
