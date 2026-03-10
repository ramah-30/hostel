<?php

session_start();

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}
include '../db.php';
$id = $_GET['id'];

$sql = "DELETE FROM bookings WHERE id=$id";
mysqli_query($conn, $sql);
$_SESSION["admindeleteBoking"] = "Property Deleted Successfull!";
header("Location: manageBooking.php");
exit();
?>
