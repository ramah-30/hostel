<?php

error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    die();
}
include_once '../db.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: Home.php#rental");
    exit();
}

$id = $_GET["id"];
$studentId = $_SESSION["id"];
$sql = "SELECT p.*,s.Email AS User_email FROM properties p
JOIN Users s ON s.Userid = p.Owner_id
 WHERE Id = '$id'";
$result = mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);


if($row>0){
    $propId = $row["Id"];
    $amount = $row["Price"];
$sql2 = "SELECT * FROM bookings WHERE property_id='$propId' AND student_id='$studentId' "; 
$result2 = mysqli_query($conn,$sql2);
if(mysqli_fetch_row($result2)>0){

    $_SESSION["alredyBooked"] = "You have Already Booked these Property";
    header("location:rental.php");
    die();
}  

$sql1 = "INSERT INTO bookings(property_id,student_id,amount) VALUES ('$propId','$studentId','$amount')";
$result1 = mysqli_query($conn,$sql1);

if (!isset($_SESSION["count"])) {
    $_SESSION["count"] = 0; 
}

if($result1){
    $_SESSION["count"]++;  // Increment session count
    $_SESSION["bookSuccess"] = "Property Added to Booking";

include_once '../sendMail.php';

$ownerEmail = $row["User_email"];
$propertyName = $row["Names"];

$message  = "
    <h3>New Booking Alert</h3>
    <p>A student has booked your property: <b>$propertyName</b></p>
    <p>Login to your account to view full details.</p>
";

sendBookingEmail($ownerEmail, $message);

header("location:rental.php");
exit();
}
}else{
    header("location:rental.php?no such property");
    die();
}

