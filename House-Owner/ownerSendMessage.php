<?php
session_start();
include '../db.php';

$ownerId = $_SESSION["id"];
$studentId = $_POST['student_id'];
$propertyId = $_POST['property_id'];
$message = mysqli_real_escape_string($conn, $_POST['message']);

$sql = "
INSERT INTO messages (property_id, sender_id, receiver_id, message)
VALUES ('$propertyId', '$ownerId', '$studentId', '$message')
";

mysqli_query($conn, $sql);

echo "OK";
?>
