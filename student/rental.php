<?php 
session_start();


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}
include_once '../db.php';

  $id =  $_SESSION["id"]; 



  $sql = "SELECT * FROM properties WHERE approval='Approved' AND statuses='available'";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $location = trim($_POST["location"]);
    $type = $_POST["type"];
    $sort = $_POST["sort"];

 
    if (!empty($location)) {
        $sql .= " AND (Locations LIKE '%$location%' OR Names LIKE '%$location%')";
    }


    if (!empty($type) && $type !== "All Types") {
        $sql .= " AND Types = '$type'";
    }

    if ($sort === "Lowest Price") {
        $sql .= " ORDER BY Price ASC";
    } elseif ($sort === "Highest Price") {
        $sql .= " ORDER BY Price DESC";
    } elseif ($sort === "Highest Rating") {
        $sql .= " ORDER BY (SELECT AVG(rating) FROM ratings WHERE property_id = properties.Id) DESC";
    } elseif ($sort === "Lowest Rating") {
        $sql .= " ORDER BY (SELECT AVG(rating) FROM ratings WHERE property_id = properties.Id) ASC";
    } else {
        $sql .= " ORDER BY Id DESC"; 
    }
} else {
    $sql .= " ORDER BY Id DESC"; 
}

 $result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <!-- Navbar-->
<nav class="navbar navbar-expand-md bg-primary ">
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
              <a class="nav-link text-dark fs-4 fw-bold text-md-white position-relative" id="house" href="studentBooking.php">
                <i class="bi bi-house-add-fill"></i>
                <span id="cart-badge" 
                      class="position-absolute fs-6  top-2 start-100 translate-middle badge rounded-pill bg-danger">
                  <?php
                  if(isset($_SESSION["count"])){
                    echo $_SESSION["count"];
                    unset($_SESSION["count"]);
                  }
                  ?>
                </span>
              </a>
            </li>
            <li class="nav-item">
            <a class="nav-link text-dark text-md-white" href="studentProfile.php"><i class="bi bi-person-circle"></i> Profile</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
</nav>
<!-- Side Nav-->
    <nav class="navbar d-md-none shadow">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1">Filters</span>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="search-bar d-flex flex-wrap justify-content-center gap-2">
      <input type="text" name="location" class="form-control w-auto" placeholder="Name or Location">
      <select name="type" class="form-select w-auto">
        <option selected>All Types</option>
        <option>Single Room</option>
        <option>Shared Room</option>
        <option>Apartment</option>
      </select>

      <select name="sort" class="form-select d-inline w-auto my-3">
        <option selected>Sort by</option>
        <option>Lowest Price</option>
        <option>Highest Price</option>
        <option>Highest Rating</option>
        <option>Lowest Rating</option>
        <option>Most Recent</option>
      </select>

      <button type="submit" class="btn btn-dark fw-bold">Search</button>
    </form>
  </div>
</nav>

<div class="container text-center d-none d-md-block">
    <h1 class="fw-bold mb-3">Find Your Perfect Student Home</h1>
    <p class="lead mb-4">Browse verified houses, hostels and apartments near your university</p>

    <!-- Search Bar -->
     <div class="shadow p-3 rounded">
      <h3 class="p-2 text-center text-muted">Filters</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="search-bar d-flex flex-wrap justify-content-center gap-2">
      <input type="text" name="location" class="form-control w-auto" placeholder="Name or Location">
      <select name="type" class="form-select w-auto">
        <option selected>All Types</option>
        <option>Single Room</option>
        <option>Shared Room</option>
        <option>Apartment</option>
      </select>
      <select name="sort" class="form-select d-inline w-auto m-3">
                <option selected>Sort by</option>
                <option>Lowest Price</option>
                <option>Highest Price</option>
                <option>Highest Rating</option>
                <option>Lowest Rating</option>
                <option>Most Recent</option>
           </select>
      <button type="submit" class="btn btn-dark fw-bold">Search</button>
      </form>
     </div>
  </div>

<!-- Body-->
   <section id="rental" class="detail" >
    <div class="row">
        <div class="col">
            <h2 class="fw-bold text-center">Available Rentals</h2>
    <p class="text-secondary text-center">We have different Houses, Hostels and Apartments you can rent</p>
    <div class="container-lg">
      <?php
      if(isset($_SESSION["alredyBooked"])){
                            echo '<div class="alert alert-danger lead text-center fw-bold">'.$_SESSION["alredyBooked"].'</div>';
                        }
         unset($_SESSION["alredyBooked"]);
      if(isset($_SESSION["bookSuccess"])){
                            echo '<div class="alert alert-success lead text-center fw-bold">'.$_SESSION["bookSuccess"].'</div>';
                        }
         unset($_SESSION["bookSuccess"]);
    ?>
      <div class="row justify-content-center">
            <?php
      if(mysqli_num_rows($result)>0) {
          while($row2 = mysqli_fetch_assoc($result)){
            $imgPath = '../images/'.$row2['Image1'];
      echo'
        <div class="col-11 col-md-6 col-lg-4 my-4 d-flex justify-content-center">
          <div class="card cards my-2 shadow " style="height:100%; width:18rem;">
            <!-- Card Image-->
            <img src="'.$imgPath.'" class="card-img-top" style="height: 200px; " alt="Property Image">
            <!-- Card Body-->
            <div class="card-body">
              <h5 class="card-title fw-bold">'.$row2["Names"].'</h5>
              <p class="text-muted mb-2"><i class="bi bi-geo-alt-fill text-primary"></i>'.$row2["Locations"].'</p>

              <ul class="list-unstyled small mb-3">
                <li><i class="bi bi-door-open"></i> Room Type: '.$row2["Types"].'</li>
                <li><i class="bi bi-house-door"></i> Available Rooms: '.$row2["Available_rooms"].'</li>';
                
                ?>
                <?php
                  $anems = explode(',',$row2["Amenities"]);
                  
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
                   echo'</ul>';
          ?>
          <?php
          echo'
              <p class="fw-bold fs-5 text-success">Tsh '.number_format($row2["Price"]).'/Month</p>

              <div class="d-flex justify-content-between">
                <a href="viewProperty.php?id='.$row2["Id"].'" class="btn btn-outline-primary btn-sm">View Details</a>
                <a href="bookProperty.php?id='.$row2["Id"].'" class="btn btn-primary btn-sm">Book Now</a>
              </div>
            </div>
            <!-- Card Footer-->
            <div class="card-footer bg-white border-0">
              <div class="text-warning small" id="rating-container-'.$row2['Id'].'">';
                $propId = $row2['Id'];
                $ratingSql = "SELECT AVG(rating) as avg_rating FROM ratings WHERE property_id = '$propId'";
                $ratingResult = mysqli_query($conn, $ratingSql);
                $ratingRow = mysqli_fetch_assoc($ratingResult);
                $avgRating = $ratingRow['avg_rating'] ? round($ratingRow['avg_rating'], 1) : 0;

                for($i=1; $i<=5; $i++){
                    $iconClass = 'bi-star';
                    if($i <= $avgRating){
                        $iconClass = 'bi-star-fill';
                    } elseif ($i - 0.5 <= $avgRating) {
                        $iconClass = 'bi-star-half';
                    }
                    echo '<span class="text-warning"><i class="bi '.$iconClass.'"></i></span>';
                }
                echo ' <small class="text-muted" id="avg-rating-'.$propId.'">('.(($avgRating > 0) ? $avgRating : '0.0').')</small>
              </div>
            </div>
          </div>
        </div>';
                }
              }
              else{
                echo '<div class="text-center text-muted py-5"><h4>No properties found for your search.</h4></div>';
              }
      ?>
      </div>
    </div>
  </section>

  <!-- Fotter-->
     <Section>
      <div class="container-fluid bg-primary mt-3 p-4 text-white">
        <div class="d-block d-md-flex gap-5">
          <div>
            <h3 class="text-center fw-bold">Our Mission</h3>
            <p class="display">To simplify the student rental experience by connecting students with verified and affordable housing options — all in one trusted online platform.</p>
          </div>
          <div>
            <h3 class="text-center fw-bold">Our Vision</h3>
            <p class="display">To become Tanzania’s leading digital student housing solution,making off-campus living easier, safer, and smarter for every student.</p>
          </div>
        </div>
        <div class="text-center">
            <h3>Follow Us On</h3>
            <a  href="" class="btn text-white fs-5"><i class="bi bi-instagram"></i></a>
            <a  href="" class="btn text-white fs-5"><i class="bi bi-facebook"></i></i></a>
            <a  href="" class="btn text-white fs-5"><i class="bi bi-whatsapp"></i></a>
            <a  href="" class="btn text-white fs-5"><i class="bi bi-twitter-x"></i></a>
          </div>
        <div class="text-center mb-5">
            <i class="bi bi-telephone-fill"></i>
            <span>+255 785643289 / +255 678429087</span>
          </div>
      </div>
     </Section>
     <footer class="bg-dark w-100 position-fixed bottom-0 text-white text-center py-3">
  <p class="mb-0">&copy; <?php echo date("Y"); ?> U-Rental |Student Housing</p>
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>

</script>
</body>
</html>