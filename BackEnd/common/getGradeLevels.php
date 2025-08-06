<?php

declare(strict_types =1);

require_once __DIR__ .'/../core/dbconnection.php';

class getGradeLevels {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }

    private function gradeLevelquery() {
        $sql = "SELECT * FROM grade_level";
        $stmt = $this->conn->prepare($sql);
        $stmt-> execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function createSelectValues() {

        try {
            $data = $this->gradeLevelQuery();
            if ($data) {
                foreach($data as $rows) {
                echo '<option value=' . $rows['Grade_Level_Id'] .'>' . 
                htmlspecialchars($rows['Grade_Level'])  . '</option>';
                }
            }
            else {
                echo "<option> No values found </option>";
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function createCheckBoxes() {
        try {
            $data = $this->gradeLevelQuery();
            if ($data) {
                foreach($data as $rows) {
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