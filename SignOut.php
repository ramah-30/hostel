<?php
session_start();


$_SESSION = [];


unset($_SESSION["id"]);
session_destroy();


header("Location:SignIn.php");
exit();
?>
