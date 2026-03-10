<?php

error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once '../db.php';

// Redirect if not logged in
if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $bookingid = trim($_POST["booking_id"]);

    $sql = "DELETE FROM bookings WHERE id ='$bookingid';";
    $sql2 = "UPDATE properties SET Statuses='available'";
    mysqli_query($conn,$sql2);
    if(mysqli_query($conn,$sql)){
        $_SESSION["count"]--;
        $_SESSION["canceSuccessful"]="Your Booking has been canceled Sucessfully!";
         header("location: StudentBooking.php");
    exit();
    }
}