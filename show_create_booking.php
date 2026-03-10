<?php
include 'db.php';

$sql = "SHOW CREATE TABLE bookings";
$result = mysqli_query($conn, $sql);

if ($result) {
    print_r(mysqli_fetch_assoc($result));
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
