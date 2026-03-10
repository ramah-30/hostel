<?php
session_start();
include '../db.php';

$ownerId = $_SESSION["id"];
$studentId = $_GET['student_id'];
$propertyId = $_GET['property_id'];

$sql = "SELECT * FROM messages 
        WHERE property_id='$propertyId' 
        AND ((sender_id='$ownerId' AND receiver_id='$studentId') 
        OR  (sender_id='$studentId' AND receiver_id='$ownerId'))
        ORDER BY sent_at ASC";

$result = mysqli_query($conn, $sql);

while($m = mysqli_fetch_assoc($result)) {
    $class = ($m['sender_id'] == $ownerId) ? "me-msg" : "them-msg";
    echo "<div class='chat-msg {$class} w-50'>{$m['message']}</div>";
}
?>
