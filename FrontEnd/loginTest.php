<?php
require_once __DIR__ . '/../BackEnd/core/dbconnection.php';

$db = new Connect();
$conn = $db->getConnection();

$User_Typed_Password = 'test';
$User_Typed_Phone_Number = '09354876649';
$sql_find_staff = "SELECT 
users.User_Id AS User_Id,
users.Password,
users.Staff_Id AS user_staff_id,
staffs.Staff_Id AS staff_staff_id,
staffs.Staff_First_Name,
staffs.Staff_Last_Name,
staffs.Staff_Contact_Number,
staffs.Staff_Type,
users.User_Type
FROM users 
JOIN staffs ON users.Staff_Id = staffs.Staff_Id
WHERE staffs.Staff_Contact_Number = :Contact_Number";
$find_staff = $conn->prepare($sql_find_staff);
$find_staff->bindParam(':Contact_Number', $User_Typed_Phone_Number);
$find_staff->execute();
$staffResult = $find_staff->fetch(PDO::FETCH_ASSOC);

if($staffResult && password_verify($User_Typed_Password, trim($staffResult['Password']))) {
    echo 'correct';
}
else {
    echo 'wrong';
}