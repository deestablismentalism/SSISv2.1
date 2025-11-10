<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');
//CREDENTIALS
$servername = 'mysql-ssis-test.alwaysdata.net';
$dbname = 'ssis-test_database';
$username = 'ssis-test';
$pw = 'SSISdatabasetest123';
try {
    // PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $pw);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // SET PROPER TIMEZONE
    $conn->exec("SET time_zone = '+08:00'");
    // EXPIRE QUERY
    $sql = "UPDATE school_year_details
            SET Is_Expired = 1
            WHERE Ending_Date < CURDATE()
            AND Is_Expired = 0";
    //RETURN AFFECTED ROWS
    $affectedRows = $conn->exec($sql); 
    echo "[" . date("Y-m-d H:i:s") . "] School year expiration check done. Rows updated: $affectedRows\n";
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
// CLOSE CONNECTION
$conn = null;
?>