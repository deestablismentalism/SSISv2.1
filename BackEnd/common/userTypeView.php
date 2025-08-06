<?php
declare(strict_types=1);

class UserTypeView {
    
    public function __construct() {
        $this->display();
    }
    private function display() {
        $userType = "";
        if (isset($_SESSION['User']['User-Type'])  && $_SESSION['User']['User-Type'] == 3) {
            $userType = "User";
        }
        else if(isset($_SESSION['Staff']['Staff-Type']) && $_SESSION['Staff']['Staff-Type'] == 2) {
            $userType = "Teacher";
        }
        else if(isset($_SESSION['Staff']['Staff-Type']) && $_SESSION['Staff']['Staff-Type'] == 1) {
            $userType = "Admin";
        }
        if (!empty($userType)) {
            echo $userType;
        }
        else {
            echo "Unknown";
        }
    }
}