<?php

session_start();

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}
include '../db.php';
$id = $_GET['id'];

$sql = "DELETE FROM properties WHERE Id=$id";
mysqli_query($conn, $sql);
$_SESSION["admindelete"] = "Property Deleted Successfull!";
header("Location: manageProperties.php");
exit();
?>
