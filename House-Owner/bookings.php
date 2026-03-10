<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location:../SignIn.php");
    die();
}

$ownerId = $_SESSION['id'];


$sql = "
SELECT 
    b.id AS booking_id,
    b.booking_date,
    b.statuses,
    b.amount,
    s.Firstname AS student_name,
    s.Email AS student_email,
    s.PhoneNumber AS student_phone,
    p.Names AS property_name,
    p.Id AS id,
    p.Locations
FROM bookings b
JOIN properties p ON b.property_id = p.Id
JOIN users s ON b.student_id = s.Userid
WHERE p.Owner_id = '$ownerId'
ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);

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
    <h3 class="text-center mb-4 fw-bold"><i class="bi bi-house-door"></i> U-Hostel</h3>
    <a href="owner.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="addProperty.php"><i class="bi bi-plus-circle"></i> Add Property</a>
    <a href="Myproperties.php"><i class="bi bi-building"></i> My Properties</a>
    <a href="#"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="ownerChat.php"><i class="bi bi-chat-fill"></i> Chats</a>
    <a href="ownerProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="../SignOut.php"  class="btn w-50  btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="fw-bold text-primary mb-4">
    <i class="bi bi-journal-bookmark-fill"></i>Bookings
  </h2>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="table-responsive shadow rounded">
      <table class="table table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Student</th>
            <th>Contact</th>
            <th>Property</th>
            <th>Location</th>
            <th>Booking Date</th>
            <th>Amount (Tsh)</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $count++ ?></td>
              <td><?= htmlspecialchars($row['student_name']) ?></td>
              <td>
                <small><?= htmlspecialchars($row['student_email']) ?><br>
                <?= htmlspecialchars($row['student_phone']) ?></small>
              </td>
              <td><?= htmlspecialchars($row['property_name']) ?></td>
              <td><?= htmlspecialchars($row['Locations']) ?></td>
              <td><?= htmlspecialchars(date('d M Y', strtotime($row['booking_date']))) ?></td>
              <td><strong><?= number_format($row['amount']) ?></strong></td>
              <td>
                <?php
                  $status = $row['statuses'];
                  $badgeClass = match($status) {
                    'Pending' => 'bg-warning text-dark',
                    'Confirmed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
              </td>
              <td>
                <?php if ($status === 'Pending'): ?>
                  <a href="updateBooking.php?id=<?= $row['booking_id'] ?>&status=Confirmed&pid=<?= $row['id'] ?>" 
                     class="btn mb-2 btn-sm btn-success">Confirm</a>
                  <a href="updateBooking.php?id=<?= $row['booking_id'] ?>&status=Cancelled" 
                     class="btn mb-2 btn-sm btn-danger">Delete</a>
                <?php else: ?>
                  <a href="cancelBooking.php?id=<?= $row['booking_id'] ?>&status=Pending&pid=<?= $row['id'] ?>" 
                     class="btn mb-2 btn-sm btn-danger">Cancel</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle"></i> No bookings found for your properties yet.
    </div>
  <?php endif;  ?>
  </div>

</body>
</html>
