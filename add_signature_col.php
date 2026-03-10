<?php
include 'db.php';

$sql = "ALTER TABLE Users ADD COLUMN Signature LONGTEXT DEFAULT NULL";

if (mysqli_query($conn, $sql)) {
    echo "Column 'Signature' added successfully.";
} else {
    echo "Error adding column: " . mysqli_error($conn);
}
?>
