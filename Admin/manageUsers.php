<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Dashboard - U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Poppins, sans-serif;
      background-color: #f5f6fa;
    }
    .sidebar {
      height: 100vh;
      background-color: #0d6efd;
      color: white;
      padding-top: 20px;
      position: fixed;
      width: 250px;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
    }
    .sidebar a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .content {
      margin-left: 260px;
      padding: 30px;
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
    <h4 class="text-center mb-4"><i class="bi bi-building"></i> Admin Panel</h4>
    <a href="admin.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="#.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageProperties.php"><i class="bi bi-building"></i> Manage Properties</a>
    <a href="manageBooking.php"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
    <a href="adminReports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>
<div class="main-content container">
  <h3 class="fw-bold pt-5 mb-3 text-center text-primary">Manage Users</h3>
  <?php
   if(isset($_SESSION["success"])){
                            echo '
                            <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["success"].'</p></div>';
                        }
         unset($_SESSION["success"]);
   if(isset($_SESSION["delete"])){
                            echo '
                            <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["delete"].'</p></div>';
                        }
         unset($_SESSION["delete"]);
   if(isset($_SESSION["updateError"])){
                            echo ' <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["updateError"].'</p></div>';
                        }
         unset($_SESSION["updateError"]);
  ?>
  <table class="table table-striped align-middle">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      include '../db.php';
      
      $query = mysqli_query($conn, "SELECT * FROM users");
      while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>
          <td>{$row['Userid']}</td>
          <td>{$row['Firstname']} {$row['Lastname']}</td>
          <td>{$row['Email']}</td>
          <td>{$row['Roles']}</td>
          <td><span class='badge bg-success'>Active</span></td>
          <td>
            <a href='editUser.php?id={$row['Userid']}' class='btn btn-sm btn-warning'>Edit</a>
            <a href='deleteUser.php?id={$row['Userid']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>  
</body>
