<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

function sendBookingEmail($toEmail, $messageBody, $attachmentPath = null) {

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'softwaredev668@gmail.com'; // Replace
        $mail->Password   = 'y k y i d v l y e v h c k n k m'; // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender and Receiver
        $mail->setFrom('softwaredev668@gmail.com', 'U-Hostel');
        $mail->addAddress($toEmail);

        // Add attachment if provided
        if ($attachmentPath && file_exists($attachmentPath)) {
            $mail->addAttachment($attachmentPath, 'Rental_Contract.pdf');
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Booking Received - U-Hostel';
        $mail->Body    = $messageBody;

        $mail->send();
        return true;

    } catch (Exception $e) {
        // Optional: log $e->getMessage() for debugging
        return false;
    }
}
