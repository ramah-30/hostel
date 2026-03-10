<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $user_id = trim($_POST['user_id']);
    $errors = [];
    include_once '../db.php';
  
   if (empty($firstName)){
             $errors["firstName"] = "* First name is required";
        }
    if (empty($lastName)){
             $errors["lastName"] = "* Last name is required";
        }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors["WrongEmail"] = "* Please Enter a Valid Email Address!";
        }
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
             $errors["InvalidPhone"] = "* Phone number must contain only digits (10).";
        }
    if($errors){
            $_SESSION['editUserError'] = $errors;
            header("location: editUser.php?id={$user_id}");
            die();
        }

        $updateSQL = "UPDATE Users 
                      SET Firstname='$firstName', 
                          Lastname='$lastName', 
                          Email='$email', 
                          PhoneNumber='$phone', 
                          Roles='$role' 
                      WHERE Userid='$user_id'";

        if (mysqli_query($conn, $updateSQL)) {
            $_SESSION['success'] = "User updated successfully!";
            header("location: manageUsers.php");
            exit();
        } else {
            $_SESSION["updateError"] = "Error updating user: " . mysqli_error($conn);
        }
    }