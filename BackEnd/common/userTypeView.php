<?php
declare(strict_types=1);

class UserTypeView {
    private $userType;
    private $sessionValue;
    public function __construct(int $session) {
        $this->sessionValue = $session;
    }
    private function display() : string { 
        $this->userType = "";
        if(!isset($this->sessionValue) || empty($this->sessionValue)) {
            $this->userType = 'Unknown';
        }
        if (isset($_SESSION['User']['User-Type']) && $this->sessionValue === 3) {
            $this->userType = "User";
        }
        else if(isset($_SESSION['Staff']['Staff-Type']) && $this->sessionValue === 2) {
            $this->userType = "Teacher";
        }
        else if(isset($_SESSION['Staff']['Staff-Type']) && $this->sessionValue === 1) {
            $this->userType = "Admin";
        }
        return $this->userType;
    }
    public function __toString() : string {
        return $this->display();
    }
}