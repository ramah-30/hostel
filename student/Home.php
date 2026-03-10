<?php 
session_start();


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}
include_once '../db.php';

  $id =  $_SESSION["id"]; 


  $sql = "SELECT * FROM Users WHERE Userid = '$id'";
  $result = mysqli_query($conn,$sql);

  $row = mysqli_fetch_assoc($result);

  $sql1 = "SELECT * FROM properties WHERE approval='Approved' AND statuses='available'  ORDER BY Id DESC LIMIT 6";
  $result2 = mysqli_query($conn,$sql1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
     <link rel="stylesheet" href="../style.css">
</head>
<body>
  <!-- Navbar-->
<nav class="navbar navbar-expand-md bg-primary fixed-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
      <a class="navbar-brand text-white" href="#">
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
              <a class="nav-link text-dark text-md-white" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="#rental">Rentals</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="#aboutUs">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-dark text-md-white" href="#contactUs">Contact Us</a>
            </li>
           <li class="nav-item">
              <a class="nav-link text-dark fs-4 fw-bold text-md-white position-relative" id="house" href="studentBooking.php">
                <i class="bi bi-house-add-fill"></i>
                <span id="cart-badge" 
                      class="position-absolute fs-6  top-2 start-100 translate-middle badge rounded-pill bg-danger">
                  <?php
                  if(isset($_SESSION["count"])){
                    echo $_SESSION["count"];
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
  <!-- Intro Images-->
<section id="intro">
  <div id="carouselExampleCaptions" class="carousel slide">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../images/flats-4417311_1920.jpg" class="d-block w-100" alt="Single and Masters House">
        <div class="carousel-caption">
          <h5>Single and Masters House</h5>
          <p>Spacious private and shared houses with full amenities, a homey environment for students seeking comfort and convenience.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../images/beds-182965_1920.jpg" class="d-block w-100" alt="Hostel">
        <div class="carousel-caption my-5">
          <h5>Hostel</h5>
          <p>Affordable and secure student hostel just minutes from campus ideal for focused study and a social living experience.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../images/apartnment.jpg" class="d-block w-100" alt="Apartments">
        <div class="carousel-caption my-5">
          <h5>Apartments</h5>
          <p>Modern apartment offering comfort, privacy, and great city views perfect for students who value independence.</p>
        </div>
      </div>
    </div>
   </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  </section>

  <!-- Booking Cards-->
  <section id="rental">
    <h2 class="fw-bold text-center my-3">Rentals</h2>
    <p class="text-secondary text-center">We have different Houses, Hostels and Apartments you can rent</p>
    <div class="container-md">
      <div class="row justify-content-center">
      <?php
      if(mysqli_num_rows($result2)>0) {
          while($row2 = mysqli_fetch_assoc($result2)){
            $imgPath = '../images/'.$row2['Image1'];
      echo'
        <div class="col-8 col-md-6 col-lg-4 my-4 ">
          <div class="card shadow" style="width: 18rem; height:100%;">
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
                <a href="bookProperty.php?id='.$row2["Id"].'"  class="btn btn-primary btn-sm">Book Now</a>
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
              }else{
               echo' <p class="text-dark text-center">No property has been listed for rental yet! </p>';
              }
      ?>
      </div>
       <div class="d-flex justify-content-center m-5">
          <a href="rental.php" class="btn btn-lg btn-light text-primary border-primary rounded-5">View More</a>
        </div>
      </div>
  </section>

  <!-- About Us-->
   
   <section id="aboutUs">
    <h2 class="fw-bold text-center">About Us</h2>
    <div class="aboutUs py-3">
    <div class="container-xxl">
      <div class="row">
        <div class="col text-center col-md-7 col-lg-6 col-xxl-5">
          <img src="../images/flats-4417311_1920.jpg" class="img" style="max-width: 420px; height: 350px;" alt="">
        </div>
        <div class="col-12 my-2 col-md-5 col-lg-6 col-xxl-6 ">
          <h1>Who Are We!</h1>
         <p class="display">U-Rental is a modern online platform that helps university and college students in Tanzania find and rent safe, affordable housing near their campuses.</p>
        <p class="display">We understand how stressful it can be to search for a room walking from street to street, dealing with unreliable agents, or finding hostels already full.
        That’s why we built U-Rental, a digital space where students and house owners connect directly and manage rentals easily.</p>
      </div>
    </div>
  </div>
   </section>


   <!-- Services -->
    <section id="services" >
      <h2 class="fw-bold text-center my-3">Our Services</h2>
      <div class="container-lg">
        <div class="row text-center ">
          <div class="d-flex justify-content-center my-3 col-lg-4">
        <div class="card  text-lead text-white bg-secondary shadow" style="width: 18rem;">
          <div class="card-body">
            <h1 class="card-title"><i class="bi bi-geo-alt-fill"></i></h1>
            <h3 class="card-subtitle mb-2 text-body-white">Location-Based Search</h3>
            <p class="card-text">Quickly find Apertments,Houses and Hostels near your university or college.</p>
          </div></div></div>
          <div class="d-flex justify-content-center my-3 col-lg-4">
        <div class="card  text-white text-lead bg-primary shadow" style="width: 18rem;">
          <div class="card-body">
            <h1 class="card-title"><i class="bi bi-coin"></i></h1>
            <h3 class="card-subtitle mb-2 text-body-primary">Affordable Options</h3>
            <p class="card-text">Filter by price range to match your student budget.</p>
          </div></div></div>
          <div class="d-flex justify-content-center my-3 col-lg-4">
        <div class="card text-white text-lead bg-warning  shadow" style="width: 18rem;">
          <div class="card-body">
            <h1 class="card-title"><i class="bi bi-shield-lock-fill"></i></h1>
            <h3 class="card-subtitle mb-2 text-body-white">Secure Bookings</h3>
            <p class="card-text">Every booking is recorded and confirmed through the system.</p>
          </div>
        </div></div>
      </div>
    </section>


    <!--Contact -->
    <section id="contactUs">
      <h2 class="fw-bold text-center my-3">Contact Us</h2>
       <?php
        if(isset($_SESSION["sent"])){
                            echo '
                            <div class="d-flex justify-content-center"><div class="alert alert-success  lead text-center fw-bold w-50">'.$_SESSION["sent"].'</div></div>';
                        }
         unset($_SESSION["sent"]);
         ?>
      <div class="row justify-content-center">  
          <div class="col-8 col-md-6 col-lg-8 col-xl-6 bg-primary p-4 rounded">
            <form action="homeHandle.php" method="post" >
              <div class="mb-3 text-white">
                <label for="exampleFormControlInput1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
               <?php
                  if(isset($_SESSION["Wrongmail"])){
                                     echo '<small class="text-danger text-center p-2 rounded bg-white fw-bold ">'.$_SESSION["Wrongmail"].'</small>';
                                  }
                  unset($_SESSION["Wrongmail"]);
              ?>
              </div>
            <div class="mb-3 text-white">
                <label for="exampleFormControlTextarea1" class="form-label">Say Something</label>
                <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
              <?php
                if(isset($_SESSION["emptyMessage"])){
                          echo '<small class="text-danger text-center p-2 rounded bg-white fw-bold">'.$_SESSION["emptyMessage"].'</small>';
                        }
                 unset($_SESSION["emptyMessage"]);
              ?>
              <button type="submit" class="btn btn-light mt-3">Submit</button>
            </form>
          </div>
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
  const badge = document.getElementById("cart-badge");
  const house = document.getElementById("house"); // Fixed variable reference

  if(house){
      house.addEventListener("click",()=>{
      badge.classList.add("d-none");
    })
  }



</script>
</body>
</html>