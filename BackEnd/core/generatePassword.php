<?php
declare(strict_types=1);
class generatePassword {
    private string $password = '';
    private string $charSet;

    public function __construct() {
        $this->charSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $this->setPassword();
    }
    private function setPassword() : void {
        for($i = 0; $i < 8; $i++) {
            $this->password .= $this->charSet[random_int(0, strlen($this->charSet) - 1)];
        }
    }
    public function getPassword() : string {
        return $this->password;
    }
}