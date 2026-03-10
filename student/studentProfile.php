<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}

$studentId = $_SESSION["id"];
$query = "SELECT * FROM Users WHERE Userid = '$studentId'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Profile - U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Poppins, sans-serif;
    }
    .profile-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 30px;
      margin-top: 80px;
    }
    
    .info-label {
      font-weight: bold;
      color: #555;
    }
  </style>
</head>
<body>
<!-- Navbar-->
<nav class="navbar navbar-expand-md bg-primary fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <a class="navbar-brand text-white" href="Home.php">
        <span class="fw-bold">
          <i class="bi bi-house-door-fill"></i>
          U-Rental
        </span>
      </a>
    <div class="d-flex align-items-center">
      <!-- Toggle button -->
      <button class="navbar-toggler bg-white" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
      <!-- Off-Canvas-->
      <div class="offcanvas offcanvas-end  text-center" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h2 class="offcanvas-title text-primary fw-bold" id="offcanvasNavbarLabel">Menu</h2>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
          <!-- Nav Links-->
          <ul class="navbar-nav nav-underline justify-content-end align center flex-grow-1 pe-3">
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" aria-current="page" href="Home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="Home.php#rental">Rentals</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="Home.php#aboutUs">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="Home.php#contactUs">Contact Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark fs-4 fw-bold text-md-white" href="studentBooking.php"><i class="bi bi-house-add-fill"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
</nav>

<div class="container">
  <div class="profile-card text-center">
      <?php if (!empty($_SESSION["error"])): ?>
    <div class="alert alert-danger"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION["success"])): ?>
    <div class="alert alert-success"><?= $_SESSION["success"]; unset($_SESSION["success"]); ?></div>
  <?php endif; ?>
    <h3 class="fw-bold text-primary"><?= htmlspecialchars($student['Firstname'] . ' ' . $student['Lastname']); ?></h3>
    <p class="text-muted mb-1"><i class="bi bi-envelope"></i> <?= htmlspecialchars($student['Email']); ?></p>
    <p class="text-muted mb-1"><i class="bi bi-phone"></i> <?= htmlspecialchars($student['PhoneNumber']); ?></p>
    <p class="text-muted mb-3"><i class="bi bi-person"></i> Role: <?= htmlspecialchars($student['Roles']); ?></p>

    <div class="d-flex justify-content-center gap-3">
      <a href="editStudentProfile.php" class="btn btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit Profile</a>
      <a href="../SignOut.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</div>
<footer class="bg-dark w-100 position-fixed bottom-0 text-white text-center py-3">
  <p class="mb-0">&copy; <?php echo date("Y"); ?> U-Rental |Student Housing</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
