<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
  header("location:../SignIn.php");
  exit();
}


$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Users WHERE Roles IN ('Student', 'House Owner')"));
$students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Users WHERE Roles='Student'"));
$owners = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Users WHERE Roles='House Owner'"));

$properties = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties"));
$approvedProps = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties WHERE approval='Approved'"));
$pendingProps = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM properties WHERE approval='Pending'"));

$bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings"));
$confirmedBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE statuses='Confirmed'"));
$cancelledBookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE statuses='Cancelled'"));

$revenue = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(p.Price),0) AS total_revenue
    FROM bookings b 
    JOIN properties p ON b.property_id = p.Id
    WHERE b.statuses = 'Confirmed'
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Reports - U-Hostel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: Poppins, sans-serif; }
    .sidebar {
      background-color: #0d6efd;
      color: white;
      height: 100vh;
      position: fixed;
      top: 0; left: 0;
      width: 250px;
      padding-top: 1rem;
      transition: transform 0.3s ease-in-out;
      z-index: 1;
    }
    .sidebar a {
      color: white;
      display: block;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 8px;
    }
    .sidebar a:hover {
      background-color: #0b5ed7;
    }
    .main-content {
      margin-left: 250px;
      padding: 2rem;
    }
    .card {
      border-radius: 15px;
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-3px);
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
        padding: 1.5rem;
      }
      .toggle-btn {
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
<h4 class="text-center mb-4"><i class="bi bi-house-door"></i> Admin Panel</h4>
    <a href="admin.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageProperties.php"><i class="bi bi-building"></i> Manage Properties</a>
    <a href="manageBooking.php"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
    <a href="#"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Mobile toggle button -->
<button class="btn btn-primary toggle-btn d-lg-none" id="toggleSidebar">
  <i class="bi bi-list fs-3"></i>
</button>

<!-- Main Content -->
<div class="main-content">
  <div class="container-fluid">
    <h2 class="text-center fw-bold mb-4 text-primary"><i class="bi bi-bar-chart"></i> System Reports</h2>

    <div class="row g-4">
      <!-- Users -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-people text-primary"></i> Total Users</h5>
          <p class="fs-4 fw-bold"><?= $users['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-person-fill text-success"></i> Students</h5>
          <p class="fs-4 fw-bold"><?= $students['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-house-fill text-warning"></i> House Owners</h5>
          <p class="fs-4 fw-bold"><?= $owners['total'] ?></p>
        </div>
      </div>

      <!-- Properties -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-buildings text-primary"></i> Properties</h5>
          <p class="fs-4 fw-bold"><?= $properties['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-check-circle-fill text-success"></i> Approved Properties</h5>
          <p class="fs-4 fw-bold"><?= $approvedProps['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-clock-history text-warning"></i> Pending Properties</h5>
          <p class="fs-4 fw-bold"><?= $pendingProps['total'] ?></p>
        </div>
      </div>

      <!-- Bookings -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-journal-bookmark-fill text-primary"></i> Total Bookings</h5>
          <p class="fs-4 fw-bold"><?= $bookings['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-check2-circle text-success"></i> Confirmed Bookings</h5>
          <p class="fs-4 fw-bold"><?= $confirmedBookings['total'] ?></p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card shadow text-center p-3">
          <h5><i class="bi bi-x-circle text-danger"></i> Cancelled Bookings</h5>
          <p class="fs-4 fw-bold"><?= $cancelledBookings['total'] ?></p>
        </div>
      </div>

      <!-- Revenue -->
      <div class="col-12">
        <div class="card shadow text-center bg-success text-white p-4">
          <h3><i class="bi bi-cash-coin"></i> Total Revenue</h3>
          <p class="fs-2 fw-bold">Tsh <?= number_format($revenue['total_revenue']) ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Toggle sidebar for mobile
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('sidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('active');
  });
</script>

</body>
</html>
