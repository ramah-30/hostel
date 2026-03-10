<?php
session_start();
include_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION["id"])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$student_id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $property_id = $_POST['property_id'];
    $rating = (int)$_POST['rating'];

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating']);
        exit();
    }

    // Check if user already rated this property
    $checkSql = "SELECT * FROM ratings WHERE property_id = '$property_id' AND student_id = '$student_id'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // Update existing rating
        $sql = "UPDATE ratings SET rating = '$rating' WHERE property_id = '$property_id' AND student_id = '$student_id'";
    } else {
        // Insert new rating
        $sql = "INSERT INTO ratings (property_id, student_id, rating) VALUES ('$property_id', '$student_id', '$rating')";
    }
    
    if (mysqli_query($conn, $sql)) {
        // Calculate new average
        $avgSql = "SELECT AVG(rating) as avg_rating, COUNT(*) as count FROM ratings WHERE property_id = '$property_id'";
        $avgResult = mysqli_query($conn, $avgSql);
        $avgRow = mysqli_fetch_assoc($avgResult);
        $newAvg = round($avgRow['avg_rating'], 1);
        $count = $avgRow['count'];

        echo json_encode(['success' => true]); 
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
