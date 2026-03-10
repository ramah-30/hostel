<?php
session_start();
include_once '../db.php';


if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Student") {
    header("location:../SignIn.php");
    exit();
}

$studentId = $_SESSION["id"];
$query = "SELECT * FROM Users WHERE Userid = '$studentId'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fName = $_POST["firstName"];
    $lName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];


    $update = "UPDATE Users 
               SET Firstname='$fName', Lastname='$lName', Email='$email', PhoneNumber='$phone' 
               WHERE Userid='$studentId'";
    
    if (mysqli_query($conn, $update)) {
        $_SESSION["success"] = "Profile updated successfully!";
        header("Location: studentProfile.php");
        exit();
    } else {
        $_SESSION["error"] = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - U-Hostel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: Poppins, sans-serif; }
    .container { max-width: 700px; margin-top: 70px; }
    .profile-img {
      width: 120px; height: 120px;
      border-radius: 50%; object-fit: cover;
      border: 3px solid #0d6efd;
    }
  </style>
</head>
<body>

<div class="container bg-white shadow rounded p-4">
  <h2 class="fw-bold text-primary mb-3"><i class="bi bi-pencil"></i> Edit Profile</h2>

  <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-bold">First Name</label>
        <input type="text" name="firstName" value="<?= htmlspecialchars($student['Firstname']); ?>" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Last Name</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($student['Lastname']); ?>" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($student['Email']); ?>" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($student['PhoneNumber']); ?>" class="form-control">
      </div>
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save"></i> Save Changes</button>
      <a href="studentProfile.php" class="btn btn-secondary px-4">Cancel</a>
    </div>
  </form>
</div>

</body>
</html>
