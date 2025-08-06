<?php

require_once __DIR__ . "/core/dbconnection.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $db = new Connect();
        $conn = $db->getConnection();
        $data = getLRN($conn);

        $conn->beginTransaction();
        if (empty($data)) {
            echo '<p> No rows found </p>';
        }
        $updatedLrn = 4000000000001;
        foreach($data as $rows) {
            $id = $rows['Enrollee_Id'];
            $updated = updateLRN($conn, $updatedLrn, $id);
            $updatedLrn++;
            if ($updated) {
                echo "<p>Updated to $updatedLrn</p>";
            }
        }
        $conn->commit();
    }
    catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

}

function getLRN($conn) {
    try {
    
        $sql = "SELECT Enrollee_Id, Psa_Number FROM enrollee WHERE Psa_Number = 2147483647";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function updateLRN($conn, $lrn, $id) {
    try {
        $sql = "UPDATE enrollee SET Psa_Number = :lrn WHERE Enrollee_Id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':lrn', $lrn);
        $stmt->bindValue(':id', $id);
        $result = $stmt->execute();

        if ($result) {
            return $result;
        } else {
            throw new PDOException('Did not update');
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}