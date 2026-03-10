<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

   $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    if(empty($email) && empty($message)){
      $_SESSION["emptyMessage"] = "* Fill in All the Fields Before Submiting";
      header("location:Home.php#contactUs");
    die();
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      $_SESSION["Wrongmail"] = "* Enter a Valid Email Address";
      header("location:Home.php#contactUs");
    die();
    }

    // Admin Email
    $adminEmail = "softwaredev668@gmail.com"; // change to real email

    // Email body for admin
    $body = "
        <h2>New Contact Message</h2>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Message:</strong><br>$message</p>
        <hr>
        <small>Sent from U-Hostel Contact Form</small>
    ";

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'softwaredev668@gmail.com';
        $mail->Password   = 'u u s x d x u n i c b x g r b w'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email sending
        $mail->setFrom($email);
        $mail->addAddress($adminEmail);

        $mail->isHTML(true);
        $mail->Subject = "New Contact Message from $email";
        $mail->Body    = $body;

        $mail->send();

        $_SESSION["sent"] = "Sent Successfully";
    header("location:Home.php#contactUs");
    die();

    } catch (Exception $e) {
    die($e);
    }
}