<?php 
session_start();
if(!isset($_SESSION["id"])){
  header("location:../SignIn.php");
  die();
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
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h3 class="text-center mb-4 fw-bold"><i class="bi bi-house-door"></i> U-Hostel</h3>
    <a href="owner.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="#.php"><i class="bi bi-plus-circle"></i> Add Property</a>
    <a href="MyProperties.php"><i class="bi bi-building"></i> My Properties</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="ownerChat.php"><i class="bi bi-chat-fill"></i> Chats</a>
    <a href="ownerProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="../SignOut.php" class="btn w-50 btn-dark mx-3"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content">
    <?php
    if(isset($_SESSION["addSuccessful"])){
                            echo '<div class="alert alert-primary lead text-center fw-bold">'.$_SESSION["addSuccessful"].'</div>';
                        }
         unset($_SESSION["addSuccessful"]);
    ?>
    <h2 class="fw-bold text-center mb-3 text-primary"><i class="bi bi-building"></i> Add New Property</h2>


  <form action="propertyHandle.php" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
      <label class="form-label fw-bold">Property Name</label>
      <input type="text" name="name" class="form-control">
      <?php 
      if(isset($_SESSION["errors"]["emptyName"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyName"].'</small>';
      }
      ?>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Location</label>
      <input type="text" name="location" class="form-control">
       <?php 
      if(isset($_SESSION["errors"]["emptyLocation"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyLocation"].'</small>';
      }
      ?>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Property Type</label>
      <select name="type" class="form-select">
        <option value="Single Room">Single Room</option>
        <option value="Shared Room">Self Room</option>
        <option value="Apartment">Apartment</option>
        <option value="Apartment">Hostel</option>
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Monthly Rent (TZS)</label>
        <input type="number" name="price" class="form-control">
         <?php 
      if(isset($_SESSION["errors"]["emptyRent"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyRent"].'</small>';
      }
      ?>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">Available Rooms</label>
        <input type="number" name="rooms" class="form-control">
         <?php 
      if(isset($_SESSION["errors"]["emptyRoom"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyRoom"].'</small>';
      }
      ?>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Amenities</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Wi-Fi">
        <label class="form-check-label">Wi-Fi</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Water">
        <label class="form-check-label">Water</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Electricity">
        <label class="form-check-label">Electricity</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Security">
        <label class="form-check-label">Security</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Parking">
        <label class="form-check-label">Parking</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="amenities[]" value="Furnished">
        <label class="form-check-label">Furnished</label>
      </div>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control" id="description" rows="3" placeholder="Optional"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Cover Property Image</label>
      <input type="file" name="image[]" class="form-control m-2">
       <?php 
      if(isset($_SESSION["errors"]["emptyCover"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyCover"].'</small>';
      }
      ?>
      <label class="form-label fw-bold">Additional Property Image</label>
      <input type="file" name="image[]" class="form-control m-2">
      <input type="file" name="image[]" class="form-control m-2">
      <input type="file" name="image[]" class="form-control m-2">
       <?php 
      if(isset($_SESSION["errors"]["emptyAddtional"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyAddtional"].'</small>';
      }

      if(isset($_SESSION["largeImages"])){
         echo '<small class="text-danger">'. $_SESSION["largeImages"].'</small>';
      }
      if(isset($_SESSION["uploadError"])){
         echo '<small class="text-danger">'. $_SESSION["uploadError"].'</small>';
      }
      if(isset($_SESSION["wrongFormat"])){
         echo '<small class="text-danger">'.$_SESSION["wrongFormat"].'</small>';
      }

      unset($_SESSION["errors"]);
      unset($_SESSION["largeImages"]);
      unset($_SESSION["uploadError"]);
      unset($_SESSION["wrongFormat"]);

      ?>
    </div>

    <div class="d-flex justify-content-center">
      <button type="submit" class="btn btn-primary px-4 fw-bold">Add Property</button>
    </div>

  </form>
  </div>
</body>
</html>