<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location:../SignIn.php");
    die();
}

$ownerId = $_SESSION["id"];


$sql = "SELECT * FROM properties WHERE Owner_id = '$ownerId' ORDER BY Id DESC";
$result = mysqli_query($conn, $sql);
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
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h3 class="text-center mb-4 fw-bold"><i class="bi bi-house-door"></i> U-Hostel</h3>
    <a href="owner.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="addProperty.php"><i class="bi bi-plus-circle"></i> Add Property</a>
    <a href="#"><i class="bi bi-building"></i> My Properties</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="ownerChat.php"><i class="bi bi-chat-fill"></i> Chats</a>
    <a href="ownerProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="../SignOut.php" class="btn w-50 btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <?php
    if(isset($_SESSION["success"])){
                            echo '<div class="alert alert-primary lead text-center fw-bold">'.$_SESSION["success"].'</div>';
                        }
         unset($_SESSION["success"]);
    if(isset($_SESSION["delete"])){
                            echo '<div class="alert alert-primary lead text-center fw-bold">'.$_SESSION["delete"].'</div>';
                        }
         unset($_SESSION["delete"]);
    if(isset($_SESSION["error"])){
                            echo '<div class="alert alert-danger lead text-center fw-bold">'.$_SESSION["error"].'</div>';
                        }
         unset($_SESSION["error"]);
    ?>
    <h2 class="fw-bold text-center text-primary mb-4"><i class="bi bi-building"></i> My Properties</h2>

  

  <?php if (mysqli_num_rows($result) > 0): ?>
  <div class="row justify-content-center">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="col-10 col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow property-card">
          <!-- Display first image if available -->
          <?php
            $imgPath = '../images/'.$row['Image1'];
          ?>
          <img src="<?= $imgPath ?>" class="card-img-top" style="height: 300px; " alt="Property Image">

          <div class="card-body">
            <h5 class="fw-bold"><?= htmlspecialchars($row["Names"]) ?></h5>
            <p class="text-muted mb-2"><i class="bi bi-geo-alt-fill text-primary"></i> <?= htmlspecialchars($row["Locations"]) ?></p>
            <p class="mb-1"><i class="bi bi-door-open"></i>Rooms Type: <?= htmlspecialchars($row["Types"]) ?> </p>
            <p><i class="bi bi-house-door"></i> Available Rooms: <?= htmlspecialchars($row["Available_rooms"]) ?> </p>
            <ul class="list-unstyled small mb-3">
          <?php
                  $anems = explode(',',$row["Amenities"]);
                  
                  foreach($anems as $anem){
                    if($anem === "Wi-Fi"){
                      echo '<li><i class="bi bi-wifi"></i> Wi-Fi Included</li>';
                    }
                    if($anem === "Water"){
                      echo '<li><i class="bi bi-droplet"></i> Water Available</li>';
                    }
                    if($anem === "Electricity"){
                      echo '<li><i class="bi bi-lightbulb"></i>Electricity Available</li>';
                    }
                    if($anem === "Security"){
                      echo '<li><i class="bi bi-shield-check"></i> Security</li>';
                    }
                    if($anem === "Parking"){
                      echo '<li><i class="bi bi-p-circle"></i></i> Parking Available</li>';
                    }
                    if($anem === "Furnished"){
                      echo '<li><i class="bi bi-house-check"></i> Furnished</li>';
                    }
                  }
                  
          ?></ul>
            <p class="mb-1"><i class="bi bi-coin"></i> <span class="fw-bold text-success">Tsh <?= number_format($row["Price"]) ?></span>/month</p>

            <div class="d-flex justify-content-between mt-3" >
              <a href="editProperty.php?id=<?= $row['Id'] ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i> Edit</a>
              <a href="deleteProperty.php?id=<?= $row['Id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this property?');">
                <i class="bi bi-trash"></i> Delete
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php else: ?>
    <div class="alert alert-info text-center">No properties added yet. <a href="add_property.php" class="fw-bold text-primary">Add your first one!</a></div>
  <?php endif; ?>
  </div>
</body>
</html>