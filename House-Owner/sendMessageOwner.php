<?php
session_start();
include "../db.php";

$message = $_POST["message"];
$property_id = $_POST["property_id"];
$owner_id = $_POST["owner_id"];
$student_id = $_POST["student_id"];
$sender = "owner";

$sql = "INSERT INTO messages (property_id,sender_id, receiver_id, message)
        VALUES ('$property_id', '$owner_id', '$student_id', '$message')";

mysqli_query($conn, $sql);

echo "OK";
?>
