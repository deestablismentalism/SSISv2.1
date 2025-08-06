<?php
declare(strict_types=1);

require_once __DIR__ . '/dbconnection.php';

class AdminInfo {
    protected $conn;

    public function __construct(){
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

}