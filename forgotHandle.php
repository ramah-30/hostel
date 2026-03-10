<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        $_SESSION["error"] = "Please Enter your Email Address.";
        header("Location: ForgotPassword.php");
        exit();
    }

   $_SESSION["email"] = $email;
    $sql = "SELECT * FROM Users WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $var = rand(1,50);
        $randcode = rand(000000,999999);
        $sql1 = "UPDATE Users SET Code='$randcode' WHERE Email = '$email'";
        $result1 = mysqli_query($conn,$sql1);

        include_once 'sendverification.php';

        $message = "
         <h3>Verification Code</h3>
        <p>Your Verication Code is: <b>$randcode</b></p>
        <p>Enter The code to reset your Password .</p>
        ";

        sendBookingEmail($email, $message);

    } else {
        header("Location: ForgotPassword.php");
        $_SESSION["error"] = "No account found with that Email address.";
        die();
    }

    header("Location: verification.php?var=$var");
    exit();
} else {

    header("Location: ForgotPassword.php");
    exit();
}
