<?php
include "../db.php";

$property_id = $_GET["property_id"];
$student_id = $_GET["student_id"];

$sql = "SELECT * FROM messages
        WHERE property_id='$property_id' AND sender_id='$student_id'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {


    echo "<div class='chat-message'>{$row['message']}</div>";
}
?>
