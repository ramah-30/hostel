<?php
include 'db.php';

$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_row($result)) {
        echo $row[0] . "\n";
    }
} else {
    echo "Error showing tables: " . mysqli_error($conn);
}
?>
