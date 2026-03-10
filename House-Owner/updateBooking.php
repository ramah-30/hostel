<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location: SignIn.php");
    die();
}

$bookingId = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;
$propertId = $_GET['pid'] ?? null;
if ($bookingId && $status) {

    $sql = "UPDATE bookings SET statuses = '$status' WHERE id = '$bookingId'";
    $sql1 = "UPDATE properties SET statuses = 'Booked' WHERE Id = '$propertId'";
    $result = mysqli_query($conn,$sql1);

    if (mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Booking updated successfully!";
    }
}

header("Location:bookings.php");
exit();
