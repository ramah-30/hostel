<?php
session_start();
include_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    $email = $token;

    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION["error"] = "Please fill in all fields.";
        header("Location: resetPassword.php?token=" . urlencode($token));
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION["error"] = "Passwords do not match.";
        header("Location: resetPassword.php?token=" . urlencode($token));
        exit();
    }

    if (strlen($newPassword) < 8) {
        $_SESSION["error"] = "Password must be at least 8 characters long.";
        header("Location: resetPassword.php?token=" . urlencode($token));
        exit();
    }

    $hashed = password_hash($newPassword, PASSWORD_BCRYPT);

    $sql = "UPDATE Users SET Pwd ='$hashed' WHERE Code ='$email'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $_SESSION["pwdsuccess"] = "Password updated successfully!";
        header("Location: SignIn.php");
        exit();
    } else {
        $_SESSION["error"] = "Something went wrong. Please try again.";
        header("Location: resetPassword.php?token=$token");
        exit();
    }
}
?>
