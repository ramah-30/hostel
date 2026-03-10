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

$query = "SELECT * FROM Users WHERE Userid = '$ownerId'";
$result = mysqli_query($conn, $query);
$owner = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $signature = $_POST["signature"] ?? ""; // Base64 signature

    $errors = [];

        if (empty($firstName)){
             $errors["firstName"] = "* First name is required";
        }

        if (empty($lastName)){
             $errors["lastName"] = "* Last name is required";
        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors["WrongEmail"] = "* Please Enter a Valid Email Address!";
        }

        if (!preg_match('/^[0-9]{10}$/', $phone)) {
             $errors["InvalidPhone"] = "* Phone number must contain only digits (10).";
        }
        $_SESSION["ownerErrors"] = $errors;
        if(empty($errors)){
            $updateQuery = "UPDATE Users SET 
                    Firstname = '$firstName', 
                    Lastname = '$lastName', 
                    Email = '$email', 
                    PhoneNumber = '$phone',
                    Signature = '$signature'
                    WHERE Userid = '$ownerId'";

                if (mysqli_query($conn, $updateQuery)) {
                    $_SESSION["success"] = "Profile updated successfully!";
                    header("Location: ownerProfile.php");
                    die();
                } else {
                    $_SESSION["error"] = "Error updating profile: " . mysqli_error($conn);
                }
        }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Profile - U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: Poppins, sans-serif; }
    .profile-pic { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #0d6efd; }
    .container { max-width: 700px; margin-top: 80px; }
  </style>
</head>
<body>

<div class="container bg-white p-4 rounded shadow">
  <h2 class="fw-bold text-primary text-center mb-4"><i class="bi bi-person-circle"></i> My Profile</h2>

  <?php if (isset($_SESSION["success"])): ?>
    <div class="alert alert-success"><?= $_SESSION["success"] ?></div>
    <?php unset($_SESSION["success"]); ?>
  <?php elseif (isset($_SESSION["error"])): ?>
    <div class="alert alert-danger"><?= $_SESSION["error"] ?></div>
    <?php unset($_SESSION["error"]); ?>
  <?php endif; ?>

  <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-bold">First Name</label>
        <input type="text" name="firstName" value="<?= htmlspecialchars($owner['Firstname']) ?>" class="form-control">
        <?php if (isset($_SESSION["ownerErrors"]["firstName"])){
                                               echo '<small class="text-danger">'. $_SESSION["ownerErrors"]["firstName"].'</small>';
                                            }?>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Last Name</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($owner['Lastname']) ?>" class="form-control">
         <?php if (isset($_SESSION["ownerErrors"]["lastName"])){
                                               echo '<small class="text-danger">'. $_SESSION["ownerErrors"]["lastName"].'</small>';
                                            }?>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-bold">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($owner['Email']) ?>" class="form-control">
          <?php if (isset($_SESSION["ownerErrors"]["WrongEmail"] )){
                                               echo '<small class="text-danger">'. $_SESSION["ownerErrors"]["WrongEmail"].'</small>';
                                            }?>
      </div>
      <div class="col-md-12">
        <label class="form-label fw-bold">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($owner['PhoneNumber']) ?>" class="form-control">
        <?php if (isset($_SESSION["ownerErrors"]["InvalidPhone"])){
                                               echo '<small class="text-danger">'. $_SESSION["ownerErrors"]["InvalidPhone"].'</small>';
                                            }
        ?>
      </div>
    </div>

    <!-- Signature Section -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <label class="form-label fw-bold">My Digital Signature</label>
            <div class="border rounded p-3 text-center bg-light">
                <canvas id="signature-pad" width="500" height="150" class="border bg-white" style="cursor: crosshair; touch-action: none;"></canvas>
                <input type="hidden" name="signature" id="signature-input">
                <div class="mt-2">
                     <button type="button" class="btn btn-sm btn-danger" onclick="clearSignature()">Clear</button>
                     <label class="btn btn-sm btn-primary m-0">
                        Upload <input type="file" id="upload-signature" accept="image/*" hidden>
                     </label>
                </div>
                <small class="text-muted d-block mt-2">Sign above to have it appear on your rental contracts.</small>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save"></i> Update</button>
      <a href="owner.php" class="btn btn-secondary px-4">Back</a>
    </div>
  </form>
</div>

<script>
    // Signature Pad Logic
    const canvas = document.getElementById('signature-pad');
    const ctx = canvas.getContext('2d');
    const signatureInput = document.getElementById('signature-input');
    let isDrawing = false;
    let hasSigned = false;

    // Load existing signature if available
    const existingSignature = "<?= $owner['Signature'] ?? '' ?>";
    if (existingSignature) {
        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0);
            hasSigned = true;
            signatureInput.value = existingSignature;
        };
        img.src = existingSignature;
    }

    function getMousePos(evt) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: (evt.clientX - rect.left) * (canvas.width / rect.width),
            y: (evt.clientY - rect.top) * (canvas.height / rect.height)
        };
    }

    // Drawing events
    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        ctx.beginPath();
        const pos = getMousePos(e);
        ctx.moveTo(pos.x, pos.y);
    });
    canvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            const pos = getMousePos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            hasSigned = true;
        }
    });
    canvas.addEventListener('mouseup', () => updateInput());
    canvas.addEventListener('mouseout', () => isDrawing = false);

    // Touch events
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent("mousedown", { clientX: touch.clientX, clientY: touch.clientY });
        canvas.dispatchEvent(mouseEvent);
    }, { passive: false });
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const mouseEvent = new MouseEvent("mousemove", { clientX: touch.clientX, clientY: touch.clientY });
        canvas.dispatchEvent(mouseEvent);
    }, { passive: false });
    canvas.addEventListener('touchend', () => {
        const mouseEvent = new MouseEvent("mouseup", {});
        canvas.dispatchEvent(mouseEvent);
    });

    function updateInput() {
        if(hasSigned) {
            isDrawing = false;
            signatureInput.value = canvas.toDataURL();
        }
    }

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSigned = false;
        signatureInput.value = "";
    }

    // Upload Handler
    document.getElementById('upload-signature').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                clearSignature();
                const hRatio = canvas.width / img.width;
                const vRatio = canvas.height / img.height;
                const ratio  = Math.min(hRatio, vRatio);
                const centerShift_x = (canvas.width - img.width * ratio) / 2;
                const centerShift_y = (canvas.height - img.height * ratio) / 2;
                ctx.drawImage(img, 0, 0, img.width, img.height, centerShift_x, centerShift_y, img.width*ratio, img.height*ratio);
                hasSigned = true;
                updateInput();
            }
            img.src = event.target.result;
        }
        reader.readAsDataURL(file);
    });

    // Ensure input is updated on submit
    document.querySelector('form').addEventListener('submit', function() {
        if(hasSigned) {
            signatureInput.value = canvas.toDataURL(); // Catch final state
        }
    });
</script>
</body>
</html>
