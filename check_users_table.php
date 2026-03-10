<?php
include 'db.php';

$sql = "SELECT count(*) FROM Users";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Table Users exists. Count: " . mysqli_fetch_row($result)[0];
} else {
    echo "Table Users DOES NOT exist. Error: " . mysqli_error($conn);
    
    // Try lowercase
    $sql_lower = "SELECT count(*) FROM users";
    $result_lower = mysqli_query($conn, $sql_lower);
    if ($result_lower) {
        echo "\nTable users (lowercase) exists. Count: " . mysqli_fetch_row($result_lower)[0];
    } else {
        echo "\nTable users (lowercase) DOES NOT exist.";
    }
}
?>
