<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['id'])) {
        // Handle unauthenticated user - maybe redirect to login or show error
        // For now, redirect back with error
         header("location:../SignIn.php");
         exit();
    }

    $property_id = $_POST['property_id'];
    $student_id = $_SESSION['id']; // Assuming session stores user I
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    $sql = "INSERT INTO reviews (property_id, student_id, review_text) VALUES ('$property_id', '$student_id', '$review_text')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION["reviwed"] = "Review submitted";
        header("location:viewProperty.php?id=$property_id");
    } else {
        $_SESSION["reviwederror"] = "Error submitting review";
        header("location:viewProperty.php?id=$property_id");
    }
} else {
    header("location:../index.php"); // Redirect if accessed directly
}
?>
