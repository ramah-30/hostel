<?php
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}
include '../db.php';
$id = $_GET['id'];

$sql = "UPDATE properties SET approval='Approved' WHERE id=$id";
mysqli_query($conn, $sql);
$_SESSION["approved"] = "Property Approved";
header("Location: manageProperties.php");
exit();
?>
