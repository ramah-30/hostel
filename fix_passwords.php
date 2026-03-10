<?php
include 'db.php';

// Correct hashes from Hostel.sql, using single quotes to avoid variable expansion
$student_hash = '$2y$10$C/ckh3/z525xGd/6x1QaSu/qjqpMovBjRRqWLxGnDiDXvpuAGCY.i';
$owner_hash = '$2y$10$gUMgZXNwwzCE8K4wUxzp4OHyaeemDnfZw7itYndxLDbsRspn5A2Ae';
$admin_hash = '$2y$10$cW9lVpB3812TMW024d7gYeC1ZvTUc43FIm4/Xd006g4NoB6Ygdk4m';

$sql = "UPDATE Users SET Pwd = CASE 
    WHEN Userid = 1 THEN '$student_hash'
    WHEN Userid = 2 THEN '$owner_hash'
    WHEN Userid = 3 THEN '$admin_hash'
    ELSE Pwd
END";

if (mysqli_query($conn, $sql)) {
    echo "Passwords updated successfully.\n";
} else {
    echo "Error updating passwords: " . mysqli_error($conn) . "\n";
}
?>
