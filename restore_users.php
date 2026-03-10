<?php
include 'db.php';

// Create Table - Removed DEFAULT curdate() which can be problematic
$sql_create = "CREATE TABLE IF NOT EXISTS `Users` (
  `Userid` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PhoneNumber` varchar(30) NOT NULL,
  `Roles` enum('Student','House Owner','Admin') DEFAULT 'Student',
  `Pwd` varchar(255) NOT NULL,
  `Dateofregister` date NOT NULL,
  `Nation_Id` varchar(30) DEFAULT NULL,
  `Code` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conn, $sql_create)) {
    echo "Table Users created successfully.\n";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "\n";
}

// Insert Data
$sql_insert = "INSERT INTO `Users` (`Userid`, `Firstname`, `Lastname`, `Email`, `PhoneNumber`, `Roles`, `Pwd`, `Dateofregister`, `Nation_Id`, `Code`) VALUES
(1, 'Rama', 'Yusah', 'ramahfx3@gmail.com', '0674487114', 'Student', '$2y$10$C/ckh3/z525xGd/6x1QaSu/qjqpMovBjRRqWLxGnDiDXvpuAGCY.i', '2025-11-16', NULL, 0),
(2, 'Juma', 'Hamis', 'softwaredev668@gmail.com', '0674487114', 'House Owner', '$2y$10$gUMgZXNwwzCE8K4wUxzp4OHyaeemDnfZw7itYndxLDbsRspn5A2Ae', '2025-11-16', '12345678912345678900', 0),
(3, 'Admin', 'Admin', 'admin@gmail.com', '0674487114', 'Admin', '$2y$10$cW9lVpB3812TMW024d7gYeC1ZvTUc43FIm4/Xd006g4NoB6Ygdk4m', '2025-11-16', NULL, 0)
ON DUPLICATE KEY UPDATE Email=VALUES(Email)";

if (mysqli_query($conn, $sql_insert)) {
    echo "Sample users inserted successfully.\n";
} else {
    echo "Error inserting users: " . mysqli_error($conn) . "\n";
}

// Check/Add Signature Column
$check_col = "SHOW COLUMNS FROM Users LIKE 'Signature'";
$result = mysqli_query($conn, $check_col);
if (mysqli_num_rows($result) == 0) {
    $sql_alter = "ALTER TABLE Users ADD COLUMN Signature LONGTEXT DEFAULT NULL";
    if (mysqli_query($conn, $sql_alter)) {
        echo "Column 'Signature' added successfully.\n";
    } else {
        echo "Error adding column: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "Column 'Signature' already exists.\n";
}
?>
