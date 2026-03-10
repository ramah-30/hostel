<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once '../db.php';


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}

$user_id = $_SESSION["id"];


$sql = "SELECT b.id, b.statuses, b.booking_date, p.Names AS property_name, p.Id, p.Locations, p.Price, p.Image1
        FROM bookings b
        JOIN properties p ON b.property_id = p.id
        WHERE b.student_id = '$user_id'
        ORDER BY b.booking_date DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings | U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #f8f9fa;
      overflow-x: hidden;
    }
    .navbar {
      background-color: #0d6efd;
    }
    .booking-card img {
      height: 160px;
      object-fit: cover;
      border-radius: 5px;
    }
    .status-badge {
      font-size: 0.9rem;
      padding: 5px 10px;
      border-radius: 10px;
    }
    .status-pending { background-color: #ffc107; color: black; }
    .status-approved { background-color: #28a745; color: white; }
    .status-rejected { background-color: #dc3545; color: white; }
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
              <a class="nav-link text-dark d-md-none text-md-white" href="SignOut.php">Log Out</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="studentProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
</nav>



<!-- 🧾 My Bookings -->
<div class="container mt-5 pb-5 pt-5">
  <h3 class="fw-bold text-center text-primary mb-4">My Bookings</h3>
  <?php
  if(isset($_SESSION["canceSuccessful"])){
                            echo '<div class="alert alert-primary lead text-center fw-bold">'.$_SESSION["canceSuccessful"].'</div>';
                        }
         unset($_SESSION["canceSuccessful"]);
    ?>
  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row justify-content-center">
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-10 col-md-6 col-lg-4 mb-4 ">
          <div class="card booking-card shadow-sm h-100">
            <img src="../images/<?php echo htmlspecialchars($row['Image1']); ?>" class="card-img-top" alt="Property Image">
            <div class="card-body">
              <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['property_name']); ?></h5>
              <p class="text-muted"><i class="bi bi-geo-alt-fill text-primary"></i> <?php echo htmlspecialchars($row['Locations']); ?></p>
              <p class="fw-bold text-success mb-2">Tsh <?php echo number_format($row['Price']); ?> / month</p>

              <p class="mb-1">
                <strong>Status:</strong> 
                <?php
                  $status = $row['statuses'];
                  $badgeClass = match($status) {
                    'Pending' => 'bg-warning text-dark',
                    'Confirmed' => 'bg-success',
                    'Cancelled' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                ?>
               
                <span class="status-badge text-white <?php echo $badgeClass; ?>">
                  <?php echo ucfirst($status); ?>
                </span>
              </p>

              <p class="small text-muted mb-2">Booked on: <?php echo date('d M Y', strtotime($row['booking_date'])); ?></p>

              <div class="d-flex justify-content-between">
                <?php
                if($status !=="Confirmed"){
                  echo'
                  <form action="cancelBooking.php" method="POST">
                  <input type="hidden" name="booking_id" value="'.$row['id'].'">
                  <button class="btn btn-danger" type="submit" onclick="return confirm(\'Cancel this booking?\');">Cancel</button>
                </form>';}
                else{
                  echo ' 
                  <form action="cancelBooking.php" method="POST">
                  <input type="hidden" name="booking_id" value="'.$row['id'].'">
                  <button class="btn btn-danger" type="submit" onclick="return confirm(\'Cancel this booking?\');">Cancel</button>
                </form>
                  <a href="payments.php?id='.$row['Id'].'" class="btn  btn-success">Pay</a>';}
                ?>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center mb-5 text-muted">You haven’t made any bookings yet.</p>
  <?php endif; ?>
</div>

<footer class="bg-dark w-100 position-fixed bottom-0 text-white text-center py-3">
  <p class="mb-0">&copy; <?php echo date("Y"); ?> U-Rental |Student Housing</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
