<?php

declare(strict_types =1);

require_once __DIR__ .'/../core/dbconnection.php';
require_once __DIR__ . '/../Exceptions/DatabaseException.php';

class getGradeLevels {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    //MODEL
    private function gradeLevelquery() : array {
        try {
            $sql = "SELECT * FROM grade_level";
            $stmt = $this->conn->prepare($sql);
            $stmt-> execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        catch(PDOException $e) {
            throw new DatabaseException('Failed to fetch grade levels');
        }
    }
    //CONTROLLER
    private function returnValues() : array {
        try {
            $data = $this->gradeLevelQuery();

            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Grade levels are empty',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Grade levels successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabseException $e) {
            return [
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data'=>[]
            ];        
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=>'Error: '.$e->getMessage(),
                'data'=> []
            ];
        }

    }
    public function createSelectValues() {
        try {
            $data = $this->returnValues();
            if(!$data['success']) {
                echo '<p>' .$data['message']. '</p>';
            }
            else {
                $gradeLevels = $data['data'];
                foreach($gradeLevels as $rows) {
                    echo '<option value=' . $rows['Grade_Level_Id'] .'>' . 
                    htmlspecialchars($rows['Grade_Level'])  . '</option>';
                }
            }
        }
        catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function createCheckBoxes() {
        try {
            $data = $this->returnValues();
            if(!$data['success']) {
                echo '<p>'.$data['message'].'</p>';
            }
            else {
                $gradeLevels = $data['data'];
                foreach($gradeLevels as $rows) {
                    echo '<div class="input-container">
                        </label><input type="checkbox" name="levels[]" value="'. $rows['Grade_Level_Id'] .'">'
                    .htmlspecialchars($rows['Grade_Level']).' </label>
                    </div>';
                }
            }
        }
        catch(PDOException $e) {
            echo "Error" . $e->getMessage();
        }
    }
}