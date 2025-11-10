<?php
declare(strict_types=1);
require_once __DIR__ . '/../core/dbconnection.php';
require_once __DIR__ . '/../Exceptions/DatabaseException.php';
class isAcademicYearSet {
    protected $conn;
    //POPERTIES
    protected $isError = false;
    protected $errorMessage = '';
    protected $isNotSet = false;
    protected $message = '';
    public function __construct() {
        $this->init();
    }
    private function init():void {
        try {
            $db = new Connect();
            $this->conn = $db->getConnection();
        }
        catch(DatabaseConnectionException $e) {
            $this->isError = true;
            $this->message = $e->getMessage();
            return;
        }
    }
    public function displayError():void {
        if($this->isError) {
            echo '<div class="error-message" style="color:black;text-align:center;">'.htmlspecialchars($this->errorMessage).'</div>';
        }
    }
    private function getSchoolYearDetailsId():?array {
        try {
            $sql = "SELECT * FROM school_year_details WHERE Is_Expired = 0";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
        catch(PDOException $e) {
            error_log("[".date('Y-m-d')."]".$e->getMessage()." \n",3,__DIR__ . '/../errorLogs.txt');
            throw new DatabaseException('Failed to check if school year details exist',0,$e);
        } 
    }
    public function isSet():bool {
        $isset = $this->getSchoolYearDetailsId();
        return !is_null($isset);
    }
    private function viewSchoolYearDetailsId():array {
        try {
            $data = $this->getSchoolYearDetailsId();
            if(is_null($data))  {
                return [
                    'success'=> true,
                    'message'=> 'There is no school year details set',
                    'school_year'=> $data
                ];
            }
            return [
                'success'=> true,
                'message'=> 'School year details fetched',
                'school_year'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait while we look into it',
                'data'=> []
            ];
        }
    }
    public function displaySchoolYearDetails():void {
        try {
            $sy = $this->viewSchoolYearDetailsId();
            if(!$sy['success']) {
                $this->isError = true;
                $this->errorMessage = $sy['message'];
                return;
            }
            if($sy['success'] && is_null($sy['school_year'])) {
                echo '<div class="message" style="color:black;text-align:center;">'.$sy['message'].'. 
                <a href="./admin_system_management.php">Set Academic year now?</a></div>';
            }
            else {
                $data = $sy['school_year'];
                echo '<div class="message" style="color:black;text-align:center;"> 
                These section details are for Academic year '.$data['start_year'].'-'.$data['end_year'].'</div>';
            }
        }
        catch(Throwable $t) {
            $this->isError = true;
            $this->errorMessage = 'There was a syntax problem. Please wait while we look into it';
            return;
        }
    }
}