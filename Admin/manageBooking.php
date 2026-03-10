<?php
session_start();


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}
include_once '../db.php';
$sql = "SELECT 
            b.id, b.statuses, b.booking_date,
            s.Firstname AS student_name, s.Lastname AS student_lastname, s.Email AS student_email,
            o.Firstname AS owner_name, o.Lastname AS owner_lastname,
            p.Names AS property_name, p.Locations, p.Price
        FROM bookings b
        JOIN Users s ON b.student_id = s.Userid
        JOIN properties p ON b.property_id = p.Id
        JOIN Users o ON p.Owner_id = o.Userid
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Bookings - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
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
    .container { margin-top: 60px; }
    .table td, .table th { vertical-align: middle; }
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
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="manageProperties.php"><i class="bi bi-building"></i> Manage Properties</a>
    <a href="#"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
    <a href="adminReports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

<div class=" main-content container">
  <h2 class="text-center text-primary fw-bold mb-4"><i class="bi bi-bookmark-check"></i> Manage Bookings</h2>
     <?php
   if(isset($_SESSION["admindeleteBoking"])){
                            echo '
                            <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["admindeleteBoking"].'</p></div>';
                        }
         unset($_SESSION["admindeleteBoking"]);
         ?>
  <div class="table-responsive bg-white shadow rounded p-3">
    <table class="table table-bordered table-striped align-middle text-center">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Student</th>
          <th>Student Email</th>
          <th>Property</th>
          <th>Owner</th>
          <th>Location</th>
          <th>Price (Tsh)</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $status = $row['statuses'];
                // Dynamic badge style based on status
                $badgeClass = match ($status) {
                    'Pending' => 'bg-warning text-dark',
                    'Confirmed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-secondary'
                };

                echo "<tr>
                    <td>{$count}</td>
                    <td>{$row['student_name']} {$row['student_lastname']}</td>
                    <td>{$row['student_email']}</td>
                    <td>{$row['property_name']}</td>
                    <td>{$row['owner_name']} {$row['owner_lastname']}</td>
                    <td>{$row['Locations']}</td>
                    <td>" . number_format($row['Price']) . "</td>
                    <td><span class='badge {$badgeClass}'>{$status}</span></td>
                    <td>{$row['booking_date']}</td>
                    <td>
                      <a href='adminDeleteBooking.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this booking?');\"><i class='bi bi-trash'></i> Delete</a>
                    </td>
                  </tr>";
                $count++;
            }
        } else {
            echo "<tr><td colspan='10' class='text-center text-muted'>No bookings found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
