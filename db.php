<?php

$dbHost = "localhost";
$dbUserName = "root";
$dbName = "hostels";
$dbPassword ="";


$conn = mysqli_connect($dbHost,$dbUserName,$dbPassword,$dbName);

if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}
