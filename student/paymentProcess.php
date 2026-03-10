<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"])) {
    header("location:../SignIn.php");
    exit();
}

if (!isset($_GET['success'])) {
    die("Invalid Payment!");
}

$student = $_SESSION["id"];
$property_id = $_GET['property_id'];
$amount = $_GET['amount'];

$sql = "INSERT INTO bookings (property_id, student_id, statuses, amount, created_at)
        VALUES ('$property_id', '$student', 'Confirmed', '$amount', NOW())";

mysqli_query($conn, $sql);

$_SESSION["success"] = "Payment completed successfully!";
header("location:../studentBooking.php");
exit();
?>
