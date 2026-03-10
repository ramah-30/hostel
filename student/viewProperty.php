<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
include_once '../db.php';

// Redirect if not logged in
/*if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}*/

$id = $_GET["id"];

$sql = "SELECT p.*, s.PhoneNumber AS num FROM properties p JOIN Users s ON s.Userid = p.OWner_id  WHERE Id='$id';";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

// Fetch Reviews and Ratings
// Join reviews with ratings to show the rating associated with the review if it exists
$reviews_sql = "SELECT r.*, u.Firstname, u.Lastname, rt.rating as user_rating 
                FROM reviews r 
                JOIN Users u ON r.student_id = u.Userid 
                LEFT JOIN ratings rt ON r.property_id = rt.property_id AND r.student_id = rt.student_id
                WHERE r.property_id = '$id' 
                ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);
$reviews = [];

// Calculate Average Rating independently from ratings table
$avg_sql = "SELECT AVG(rating) as avg_rating FROM ratings WHERE property_id = '$id'";
$avg_result = mysqli_query($conn, $avg_sql);
$avg_row = mysqli_fetch_assoc($avg_result);
$avg_rating = $avg_row['avg_rating'] ? round($avg_row['avg_rating'], 1) : 0;

while($r = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $r;
}

// Fetch Current User's Rating
$my_rating = 0;
if(isset($_SESSION['id'])){
    $my_id = $_SESSION['id'];
    $my_rating_sql = "SELECT rating FROM ratings WHERE property_id = '$id' AND student_id = '$my_id'";
    $my_rating_result = mysqli_query($conn, $my_rating_sql);
    if($my_row = mysqli_fetch_assoc($my_rating_result)){
        $my_rating = $my_row['rating'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Property</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
    body {
        background: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    .property-container {
        margin-top: 7rem;
        margin-bottom: 5rem;
    }

    /* Card Layout */
    .property-card {
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: 0.3s ease;
    }

    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    /* Main Image */
    .main-img {
        width: 100%;
        height: 330px;
        object-fit: cover;
        border-radius: 16px;
    }

    /* Gallery */
    .gallery-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        transition: .3s;
    }
    .gallery-img:hover {
        transform: scale(1.05);
    }

    /* Amenities */
    .amenities-box {
        background: #eef2f7;
        padding: 12px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .amenities-box i {
        color: #0d6efd;
        font-size: 18px;
    }

    .price-box {
        font-size: 25px;
        font-weight: 700;
        color: #009933;
    }

    .btn-primary-custom {
        padding: 12px 25px;
        font-weight: 600;
        font-size: 17px;
        border-radius: 10px;
        background: #0d6efd;
        border: none;
    }

    .btn-primary-custom:hover {
        background: #0b5cd6;
    }

    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
    }
   /* Floating Chat Icon */
#chat-icon {
  position: fixed;
  bottom: 25px;
  right: 25px;
  background: #0d6efd;
  color: white;
  width: 55px;
  height: 55px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
  cursor: pointer;
  box-shadow: 0px 4px 10px rgba(0,0,0,0.3);
  z-index: 9999;
}

/* Chat Window */
#chat-window {
  position: fixed;
  bottom: 90px;
  right: 25px;
  width: 320px;
  max-height: 480px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
  display: none;
  flex-direction: column;
  overflow: hidden;
  animation: popUp 0.3s ease;
  z-index: 9999;
}

@keyframes popUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

#chat-header {
  background: #0d6efd;
  color: white;
  padding: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

#chat-header #close-chat {
  cursor: pointer;
  font-size: 22px;
}

#chat-body {
  padding: 12px;
  height: 320px;
  overflow-y: auto;
  background: #f1f5ff;
}

.chat-message {
  max-width: 80%;
  padding: 10px 14px;
  border-radius: 10px;
  margin: 8px 0;
  font-size: 14px;
}

.owner-msg {
  background: #dce7ff;
  align-self: flex-start;
}

.student-msg {
  background: #0d6efd;
  color: white;
  align-self: flex-end;
}

#chat-input-area {
  display: flex;
  border-top: 1px solid #ddd;
}

#chat-input {
  flex: 1;
  padding: 10px;
  border: none;
  outline: none;
}

#send-chat {
  background: #0d6efd;
  border: none;
  color: white;
  width: 50px;
}



</style>

<script>
function changeMainImage(src) {
    document.getElementById("MainImage").src = src;
}
</script>
</head>

<body>

<!-- NAVBAR STAYS SAME (you keep yours) -->

<div class="container property-container">
    <div class="property-card">
        <div class="row">
            
            <!-- MAIN IMAGE -->
            <div class="col-md-6">
                <img id="MainImage" class="main-img" src="../images/<?php echo $row['Image1']; ?>">
                
                <!-- GALLERY -->
                <div class="row mt-3 g-2">
                    <?php if($row["Image1"]): ?>
                      <div class="col-4">
                        <img onclick="changeMainImage('../images/<?php echo $row['Image1']; ?>')" class="gallery-img" src="../images/<?php echo $row['Image1']; ?>">
                      </div>
                    <?php endif; ?>

                    <?php if($row["Image2"]): ?>
                      <div class="col-4">
                        <img onclick="changeMainImage('../images/<?php echo $row['Image2']; ?>')" class="gallery-img" src="../images/<?php echo $row['Image2']; ?>">
                      </div>
                    <?php endif; ?>

                    <?php if($row["Image3"]): ?>
                      <div class="col-4">
                        <img onclick="changeMainImage('../images/<?php echo $row['Image3']; ?>')" class="gallery-img" src="../images/<?php echo $row['Image3']; ?>">
                      </div>
                    <?php endif; ?>

                    <?php if($row["Image4"]): ?>
                      <div class="col-4">
                        <img onclick="changeMainImage('../images/<?php echo $row['Image4']; ?>')" class="gallery-img" src="../images/<?php echo $row['Image4']; ?>">
                      </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- DETAILS -->
            <div class="col-md-6">

                <h2 class="fw-bold text-primary"><?php echo $row["Names"]; ?></h2>
                <h5 class="text-secondary">
                    <i class="bi bi-geo-alt-fill text-primary"></i>
                    <?php echo $row["Locations"]; ?>
                </h5>
                
            
                <hr>

                <div class="price-box">Tsh <?php echo number_format($row["Price"]); ?> / Month</div>

                <h5 class="fw-bold mt-3">Amenities</h5>

                <?php
                foreach(explode(',', $row["Amenities"]) as $a){
                    $icon = "bi bi-house";
                    if($a=="Wi-Fi") $icon="bi bi-wifi";
                    if($a=="Water") $icon="bi bi-droplet";
                    if($a=="Electricity") $icon="bi bi-lightbulb";
                    if($a=="Security") $icon="bi bi-shield-check";
                    if($a=="Parking") $icon="bi bi-p-circle";
                    if($a=="Furnished") $icon="bi bi-house-check";

                    echo "<div class='amenities-box'><i class='$icon'></i> $a</div>";
                }
                ?>

                <h5 class="fw-bold mt-4">Description</h5>
                <p class="text-muted"><?php echo $row["Descriptions"]; ?></p>

                <h5 class="fw-bold mt-4">Contact Owner</h5>
                <p>
                    <i class="bi bi-telephone-fill text-primary"></i>
                    <span class="fw-bold"><?php echo $row["num"]; ?></span>
                </p>

                

                <!-- REVIEWS SECTION -->
                <div class="mt-5">
                    
                    <!-- New Interactive Rating Section -->
                    <div class="card p-3 mb-4 border-0 shadow-sm bg-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h5 class="fw-bold mb-0">Rate this Property</h5>
                            <div class="d-flex align-items-center gap-2">
                                <div id="user-rating-stars">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="bi bi-star fs-3 text-warning" 
                                           style="cursor: pointer;" 
                                           data-value="<?php echo $i; ?>" 
                                           onmouseover="highlightStars(<?php echo $i; ?>)" 
                                           onmouseout="resetStars()" 
                                           onclick="submitRating(<?php echo $row['Id']; ?>, <?php echo $i; ?>)"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-muted small" id="rating-status">Tap stars to rate</span>
                            </div>
                        </div>
                    </div>

                    <h4 class="fw-bold">Reviews</h4>
                    
                    <?php if(isset($_SESSION["reviwed"])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION["reviwed"]; ?></div>
                    <?php endif; 
                    unset($_SESSION["reviwed"]);
                    ?>
                    <?php if(isset($_SESSION["reviwederror"])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION["reviwederror"]; ?></div>
                    <?php endif; 
                    unset($_SESSION["reviwederror"]);?>
                        <div class="reviews-list" style="max-height: 400px; overflow-y: auto;">
                        <?php if(count($reviews) > 0): ?>
                            <?php foreach($reviews as $review): ?>
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($review['Firstname'] . ' ' . $review['Lastname']); ?></h6>
                                                
                                            </div>
                                            <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-0 text-muted mt-2"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No reviews yet. Be the first to review!</p>
                        <?php endif; ?>
                    </div>
                    <div class="card p-3 mb-4 bg-light">
                        <h6 class="fw-bold">Write a Review</h6>
                        <form action="submitReview.php" method="POST">
                            <input type="hidden" name="property_id" value="<?php echo $id; ?>">
                            <div class="mb-2">
                                <textarea name="review_text" class="form-control" rows="2" placeholder="Share your experience..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Submit Review</button>
                        </form>
                    </div>

                    
                </div>
                <div class="mt-4 d-flex gap-3">
                    <a href="bookProperty.php?id=<?php echo $row['Id'] ?>" class="btn btn-primary">
                        <i class="bi bi-calendar-check"></i> Book Now
                    </a>
                    <a href="rental.php" class="btn btn-dark px-4 d-flex align-items-center">Back</a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Chat Icon -->
<div id="chat-icon">
  <i class="bi bi-chat-dots-fill"></i>
</div>

<!-- Chat Window -->
<div id="chat-window">
  <div id="chat-header">
    <strong>Chat with Owner</strong>
    <span id="close-chat">&times;</span>
  </div>

  <div id="chat-body">
    <div class="chat-message owner-msg">
      Hello! How can I help you today?
    </div>
    <div id="chatMessages" class="chat-messages"></div>
  </div>

  <div id="chat-input-area">
    <input type="text" id="chat-input" placeholder="Type your message...">
    <button id="send-chat"><i class="bi bi-send-fill"></i></button>
  </div>
</div>


<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3 footer">
  <p class="mb-0">&copy; <?php echo date("Y"); ?> U-Rental | Student Housing</p>
</footer>
<script>
document.getElementById("chat-icon").onclick = () => {
    document.getElementById("chat-window").style.display = "flex";
};

document.getElementById("close-chat").onclick = () => {
    document.getElementById("chat-window").style.display = "none";
};

document.getElementById("send-chat").onclick = sendMessage;

// Send message via AJAX
function sendMessage() {
    let msg = document.getElementById("chat-input").value.trim();
    if(msg === "") return;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "sendMessage.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("message=" + encodeURIComponent(msg) + "&property_id=<?= $row['Id'] ?>");

    document.getElementById("chat-input").value = "";
}

// Fetch messages every 1 sec
setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "getMessages.php?property_id=<?= $row['Id'] ?>", true);
    xhr.onload = () => {
        document.getElementById("chatMessages").innerHTML = xhr.responseText;
    }
    xhr.send();
}, 1000);

// Rating Functions
let currentRating = <?php echo $my_rating; ?>; // Initialize with user's rating

// Initialize stars on load
document.addEventListener("DOMContentLoaded", function() {
    highlightStars(currentRating);
});

function highlightStars(rating) {
    const stars = document.querySelectorAll('#user-rating-stars i');
    stars.forEach(star => {
        const starValue = parseInt(star.getAttribute('data-value'));
        if (starValue <= rating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill');
        } else {
            star.classList.remove('bi-star-fill');
            star.classList.add('bi-star');
        }
    });
}

function resetStars() {
    highlightStars(currentRating);
}

function submitRating(propertyId, rating) {
    let formData = new FormData();
    formData.append('property_id', propertyId);
    formData.append('rating', rating);

    fetch('submit_rating_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            currentRating = rating; // Update local state to keep stars filled
            resetStars();
            document.getElementById('rating-status').innerText = 'Thanks for rating!';
            document.getElementById('rating-status').classList.add('text-success');
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred.");
    });
}
</script>





</body>
</html>
