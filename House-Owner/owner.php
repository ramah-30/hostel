<?php
session_start();

if(!isset($_SESSION["id"])){
  header("location:../SignIn.php");
  die();
}

include_once '../db.php';
$id = $_SESSION["id"];

$sql = "SELECT COUNT(*) AS total_properties FROM properties WHERE Owner_id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$sql5 = "SELECT * FROM properties WHERE Owner_id = '$id'";
$result5 = mysqli_query($conn, $sql5);
$row5 = mysqli_fetch_assoc($result5);

$totalProperties = $row['total_properties'];
 
$sql1 = "SELECT COUNT(*) AS total_bookings FROM bookings b
JOIN properties p ON b.property_id = p.Id
JOIN users s ON b.student_id = s.Userid
WHERE p.Owner_id = '$id'";

$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$totalBooking = $row1['total_bookings'];

$sql2 = "
SELECT 
    b.id AS booking_id,
    b.booking_date,
    b.statuses,
    b.amount,
    s.Firstname AS first_name,
    s.Lastname AS last_name,
    s.Email AS student_email,
    s.PhoneNumber AS student_phone,
    p.Names AS property_name,
    p.Locations
FROM bookings b
JOIN properties p ON b.property_id = p.Id
JOIN users s ON b.student_id = s.Userid
WHERE p.Owner_id = '$id'
ORDER BY b.booking_date DESC LIMIT 5";

$result2 = mysqli_query($conn, $sql2);


$sql3 = "SELECT 
  SUM(amount) AS total_revenue,
  COUNT(b.id) AS total_tenants
FROM bookings b
JOIN properties p ON b.property_id = p.Id
WHERE p.Owner_id = '$id' AND b.statuses = 'Confirmed'
GROUP BY p.Id, p.Names, p.Price;
";

$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);

$amount= $row3['total_revenue'];
$totalTenants = $row3['total_tenants'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Dashboard - U-Rental</title>
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
    
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h3 class="text-center mb-4 fw-bold"><i class="bi bi-house-door"></i> U-Rental</h3>
    <a href="#"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="addProperty.php"><i class="bi bi-plus-circle"></i> Add Property</a>
    <a href="Myproperties.php"><i class="bi bi-building"></i> My Properties</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="ownerChat.php"><i class="bi bi-chat-fill"></i> Chats</a>
    <a href="ownerProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <h2 class="fw-bold mb-3">Welcome,<?php echo $_SESSION["data"]["Firstname"]." ". $_SESSION["data"]["Lastname"]  ?> 👋</h2>
    <p class="text-muted">Here’s what’s happening with your properties:</p>

    <!-- Stats Cards -->
    <div class="row">
      <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h5><i class="bi bi-building"></i> Properties</h5>
            <p class="fs-4 fw-bold"><?php echo $totalProperties;?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
          <div class="card-body">
            <h5><i class="bi bi-person-check"></i> Bookings</h5>
            <p class="fs-4 fw-bold"><?php echo $totalBooking;?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
          <div class="card-body">
            <h5><i class="bi bi-people"></i> Tenants</h5>
            <p class="fs-4 fw-bold"><?php echo $totalTenants ?? 0; ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card mt-4 shadow">
      <div class="card-header bg-primary text-white fw-bold">
        Recent Booking Requests
      </div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>Property</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
             <?php while($row2 = mysqli_fetch_assoc($result2)){?>
            <tr>
              <td><?= htmlspecialchars($row2['first_name']).' '.htmlspecialchars($row2['last_name']) ?></td>
              <td><?= htmlspecialchars($row2['property_name']) ?></td>
              <td><?= htmlspecialchars(date('d M Y', strtotime($row2['booking_date']))) ?></td>
              <?php
                  $status = $row2['statuses'];
                  $badgeClass = match($status) {
                    'Pending' => 'bg-warning text-dark',
                    'Confirmed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                ?>
              <td><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
 

</body>
</html>