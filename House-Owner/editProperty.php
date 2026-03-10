<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "House Owner") {
    header("location:../SignIn.php");
    die();
}
include_once '../db.php';
$ownerId = $_SESSION["id"];

$propertyId = $_GET['id'];

    $propertyId = $_GET['id'];
    $query = "SELECT * FROM properties WHERE id = '$propertyId' AND Owner_id = '$ownerId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $property = mysqli_fetch_assoc($result);
    } else {
        die("Property not found or access denied.");
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Property - U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: Poppins, sans-serif; }
    .container { margin-top: 70px; max-width: 900px; }
    .form-control, .form-select { border-radius: 10px; }
    .img-preview { height: 120px; object-fit: cover; border-radius: 10px; }
  </style>
</head>
<body>

<div class="container bg-white shadow rounded p-4">
  <h2 class="text-primary fw-bold mb-4"><i class="bi bi-pencil-square"></i> Edit Property</h2>


  <form action="editPropertyHandle.php" method="POST" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6">
        <input type="hidden" name="property_id" value="<?= htmlspecialchars($propertyId) ?>">
        <label class="form-label fw-bold">Property Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($property['Names']) ?>" class="form-control">
         <?php 
      if(isset($_SESSION["errors"]["emptyName"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyName"].'</small>';
      }
      ?>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Location</label>
        <input type="text" name="location" value="<?= htmlspecialchars($property['Locations']) ?>" class="form-control">
         <?php 
      if(isset($_SESSION["errors"]["emptyLocation"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyLocation"].'</small>';
      }
      ?>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Monthly Rent (Tsh)</label>
        <input type="number" name="price" value="<?= htmlspecialchars($property['Price']) ?>" class="form-control">
           <?php 
      if(isset($_SESSION["errors"]["emptyRent"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyRent"].'</small>';
      }
      ?>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Available Rooms</label>
        <input type="number" name="available_rooms" value="<?= htmlspecialchars($property['Available_rooms']) ?>" class="form-control">
          <?php 
      if(isset($_SESSION["errors"]["emptyRoom"])){
         echo '<small class="text-danger">'. $_SESSION["errors"]["emptyRoom"].'</small>';
      }
      ?>
      </div>

      <?php
$selectedAmenities = array_map('trim', explode(',', $property['Amenities']));


$availableAmenities = ["Wi-Fi", "Water", "Security", "Parking", "Electricity", "Furnished"];
?>

<div class="col-12">
  <label class="form-label fw-bold">Amenities</label>
  <div class="row">
    <?php foreach ($availableAmenities as $amenity): ?>
      <div class="col-6 col-md-4 mb-2">
        <div class="form-check">
          <input 
            class="form-check-input" 
            type="checkbox" 
            name="amenities[]" 
            value="<?= htmlspecialchars($amenity) ?>"
            id="amenity_<?= htmlspecialchars($amenity) ?>"
            <?= in_array($amenity, $selectedAmenities) ? 'checked' : '' ?>
          >
          <label class="form-check-label" for="amenity_<?= htmlspecialchars($amenity) ?>">
            <?= htmlspecialchars($amenity) ?>
          </label>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

      <div class="mb-6">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control" id="description" rows="3" placeholder="Optional"><?= htmlspecialchars($property['Descriptions']) ?></textarea>
    </div>

      <!-- Image upload previews -->
      <h5 class="fw-bold mt-4">Property Images</h5>
      <div class="row">
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <?php
          // Get image name from database
          $imageFile = $property["Image$i"];

          // If the image is null or empty, use a placeholder
          $imageSrc = "../images/" . htmlspecialchars($imageFile);
          if(!empty($imageFile)){
            echo '<div class="col-md-3 text-center">
            <img src="'.$imageSrc.'"
            class="img-preview mb-2 w-50 border rounded" 
            alt="Image">
        <input type="file" name="image[]" class="form-control form-control-sm">
      </div>';
          }else{
            echo '<div class="col-md-3 text-center">
                  <i class="bi text-center fs-5 bi-upload"></i>
                  <input type="file" name="image[]" class="form-control form-control-sm">
                  </div>';
          }
          
        ?>
  
    <?php endfor; ?>
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

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save"></i> Update</button>
        <a href="MyProperties.php" class="btn btn-secondary px-4">Cancel</a>
      </div>
    </div>
  </form>
</div>

</body>
</html>
