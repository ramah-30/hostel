<?php
session_start();
include '../db.php';

$sender = $_SESSION["id"];
$property = $_POST["property_id"];
$msg = mysqli_real_escape_string($conn, $_POST["message"]);

$getOwner = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Owner_id FROM properties WHERE Id='$property'"));
$receiver = $getOwner['Owner_id'];

mysqli_query($conn, "INSERT INTO messages(sender_id, receiver_id, property_id, message)
VALUES('$sender', '$receiver', '$property', '$msg')");
?>
