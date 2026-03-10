<?php
session_start();
include '../db.php';

$user = $_SESSION["id"];
$property = $_GET["property_id"];

$sql = "
SELECT *
FROM messages
WHERE property_id = '$property'
AND (sender_id = '$user' OR receiver_id = '$user')
";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $isMine = $row["sender_id"] == $user;

    echo $isMine
      ? "<div style='text-align:right;margin:5px'><span class='badge bg-primary p-2'>{$row['message']}</span></div>"
      : "<div style='text-align:left;margin:5px'><span class='badge bg-secondary p-2'>{$row['message']}</span></div>";
}
?>
