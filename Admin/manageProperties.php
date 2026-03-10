<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}

include '../db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Properties | U-Hostel</title>
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
    .property-img {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center mb-4"><i class="bi bi-building"></i> Admin Panel</h4>
    <a href="admin.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manageUsers.php"><i class="bi bi-people"></i> Manage Users</a>
    <a href="#" class="bg-primary"><i class="bi bi-building"></i> Manage Properties</a>
    <a href="manageBooking.php"><i class="bi bi-calendar-check"></i> Manage Bookings</a>
    <a href="adminReports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="fw-bold mb-4 text-primary text-center">Manage Properties</h2>
    <?php
   if(isset($_SESSION["admindelete"])){
                            echo '
                            <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["admindelete"].'</p></div>';
                        }
         unset($_SESSION["admindelete"]);

   if(isset($_SESSION["approved"])){
                            echo '
                            <div class="d-flex mt-4 justify-content-center"><p class="alert alert-primary lead text-center w-50 fw-bold">'.$_SESSION["approved"].'</p></div>';
                        }
         unset($_SESSION["approved"]);
         ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle shadow-sm">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Property Name</th>
            <th>Location</th>
            <th>Owner</th>
            <th>Price (TZS)</th>
            <th>Approval</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = mysqli_query($conn, "SELECT p.*, u.Firstname, u.Lastname FROM properties p JOIN Users u ON p.owner_id = u.Userid ORDER BY p.created_at DESC");
          if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
              $statusBadge = $row['Statuses'] == 'Approved' ? 'bg-success' : 'bg-warning';
              echo "
              <tr>
                <td>{$row['Id']}</td>
                <td><img src='../images/{$row['Image1']}' class='property-img'></td>
                <td>{$row['Names']}</td>
                <td>{$row['Locations']}</td>
                <td>{$row['Firstname']} {$row['Lastname']}</td>
                <td>".number_format($row['Price'])."</td>
                <td><span class='bg-primary rounded text-white p-1'>{$row['approval']}</span></td><td>";?>
                <?php
                  $status = $row['Statuses'];
                  $badgeClass = match($status) {
                    'Confirmed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-warning text-white'
                  }
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
                <td>
                  <?php if($row['approval'] === "Approved"){
                  }else{
                      echo'
                        <a href="adminApprove.php?id='.$row['Id'].'" class="btn btn-sm btn-success"><i class="bi bi-check2"></i></a>';
                  } ?>
                  <a href='adminDeleteProperty.php?id=<?=$row['Id']?>' class='btn btn-sm btn-danger' onclick="return confirm('Are you sure you want to delete this property?')"><i class='bi bi-trash'></i></a>
                </td>
              </tr>
              <?php
            }
          } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No properties found</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
