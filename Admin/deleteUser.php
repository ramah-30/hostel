<?php
include '../db.php';
session_start();

if(!isset($_SESSION["id"])){
  header("location:../SignIn.php");
  die();
}
$id = $_GET['id'];

$sql = "DELETE FROM Users WHERE Userid=$id";
mysqli_query($conn, $sql);
$_SESSION['delete'] = "User Deleted successfully!";
header("Location: manageUsers.php");
exit();
?>