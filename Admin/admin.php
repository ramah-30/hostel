<?php
session_start();

if(!isset($_SESSION["id"])){
  header("location:../SignIn.php");
  die();
}

include_once '../db.php';

$sql = "SELECT COUNT(*) AS total_users FROM Users WHERE Roles IN ('Student', 'House Owner');";//Mark
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$totalUsers = $row["total_users"];

$sql1 = "SELECT COUNT(*) AS total_properties FROM properties WHERE approval='Approved';";
$result1 = mysqli_query($conn,$sql1);
$row1 = mysqli_fetch_assoc($result1);
$totalProperties = $row1["total_properties"];

$sql2 = "SELECT COUNT(*) AS total_booking FROM bookings;";
$result2 = mysqli_query($conn,$sql2);
$row2 = mysqli_fetch_assoc($result2);
$totalBooking = $row2["total_booking"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #0d6efd;
      color: white;
      position: fixed;
      top: 0; left: 0;
      width: 250px;
      padding-top: 2rem;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #0b5ed7;
    }
    .main-content {
      margin-left: 250px;
      padding: 2rem;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center mb-4"><i class="bi bi-house-door"></i> Admin Panel</h4>
    <a href="#.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageProperties.php"><i class="bi bi-building"></i> Manage Properties</a>
    <a href="manageBooking.php"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
    <a href="adminReports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="fw-bold mb-4">Dashboard Overview</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="bi bi-people display-5 text-primary"></i>
            <h5 class="mt-2">Total Users</h5>
            <p class="fs-4 fw-bold text-dark"><?php echo $totalUsers;?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="bi bi-building display-5 text-success"></i>
            <h5 class="mt-2">Active Properties</h5>
            <p class="fs-4 fw-bold text-dark"><?php echo $totalProperties; ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="bi bi-calendar-check display-5 text-warning"></i>
            <h5 class="mt-2">Total Bookings</h5>
            <p class="fs-4 fw-bold text-dark"><?php echo $totalBooking; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
