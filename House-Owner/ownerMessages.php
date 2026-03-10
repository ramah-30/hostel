<?php
session_start();

if(!isset($_SESSION["id"])){
  header("location:../SignIn.php");
  die();
}

include_once '../db.php';



$ownerId = $_SESSION["id"];

$sql = "
SELECT 
    m.property_id,
    p.Names AS property_name,
    u.Userid AS student_id,
    u.Firstname,
    u.Lastname,
    u.Email
FROM messages m
JOIN properties p ON m.property_id = p.Id
JOIN users u ON u.Userid = m.sender_id
WHERE p.Owner_id = '$ownerId'
GROUP BY m.property_id, m.sender_id
ORDER BY m.id DESC
";
$result = mysqli_query($conn, $sql);
