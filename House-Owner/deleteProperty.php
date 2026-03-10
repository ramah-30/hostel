<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location:../SignIn.php");
    die();
}
include_once '../db.php';

$ownerId = $_SESSION["id"];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Invalid property ID.";
    header("Location: MyProperties.php");
    exit();
}

$id = $_GET["id"];


$query = "SELECT * FROM properties WHERE id = '$id' AND Owner_id = '$ownerId'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) !== 1) {
    $_SESSION['error'] = "You do not have permission to delete this property.";
    header("Location: MyProperties.php");
    exit();
}


$sql = "DELETE FROM properties WHERE Id = '$id';";
if(mysqli_query($conn,$sql)){
$_SESSION["delete"] = "Property Deleted Succesfull";
}else{
    $_SESSION['error'] = "Error deleting property: " . mysqli_error($conn);
}

header("location:MyProperties.php");
die();

