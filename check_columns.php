<?php
require 'BackEnd/core/dbconnection.php';
$db = new Connect();
$conn = $db->getConnection();
$stmt = $conn->query('DESCRIBE profile_directory');
echo "Columns in profile_directory table:\n";
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}
?>
