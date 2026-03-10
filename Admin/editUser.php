<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once '../db.php';

if (!isset($_SESSION["id"]) || $_SESSION["data"]["Roles"] !== "Admin") {
    header("location:../SignIn.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("location:manageUsers.php?error=missing_id");
    exit();
}

$userId = $_GET['id'];

$sql = "SELECT * FROM Users WHERE Userid = '$userId'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) !== 1) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: Poppins, sans-serif; }
    .container { margin-top: 80px; max-width: 700px; }
  </style>
</head>
<body>

<div class="container bg-white p-4 rounded shadow">
  <h3 class="fw-bold text-primary mb-3"><i class="bi bi-pencil-square"></i> Edit User</h3>
  <form action="editUserHandle.php" method="POST">
    <div class="row g-3">
      <div class="col-md-6">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
        <label class="form-label fw-bold">First Name</label>
        <input type="text" name="firstName" value="<?= htmlspecialchars($user['Firstname']) ?>" class="form-control">
           <?php 
      if(isset($_SESSION['editUserError']["firstName"])){
         echo '<small class="text-danger">'. $_SESSION['editUserError']["firstName"].'</small>';
      }
      ?>
      </div>
      <div class="col-md-6">
        <label class="form-label fw-bold">Last Name</label>
        <input type="text" name="lastName" value="<?= htmlspecialchars($user['Lastname']) ?>" class="form-control">
            <?php 
      if(isset($_SESSION['editUserError']["lastName"])){
         echo '<small class="text-danger">'. $_SESSION['editUserError']["lastName"].'</small>';
      }
      ?>
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" class="form-control">
            <?php 
      if(isset($_SESSION['editUserError']["WrongEmail"])){
         echo '<small class="text-danger">'. $_SESSION['editUserError']["WrongEmail"].'</small>';
      }
      ?>
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['PhoneNumber']) ?>" class="form-control">
            <?php 
      if(isset($_SESSION['editUserError']["InvalidPhone"])){
         echo '<small class="text-danger">'. $_SESSION['editUserError']["InvalidPhone"].'</small>';
      }
      unset($_SESSION['editUserError']);
      ?>
      </div>

      <div class="col-12">
        <label class="form-label fw-bold">Role</label>
        <select name="role" class="form-select">
          <option value="Student" <?= $user['Roles'] === 'Student' ? 'selected' : '' ?>>Student</option>
          <option value="House Owner" <?= $user['Roles'] === 'House Owner' ? 'selected' : '' ?>>House Owner</option>
          <option value="Admin" <?= $user['Roles'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary px-4 fw-bold">Update User</button>
        <a href="manageUsers.php" class="btn btn-secondary px-4">Cancel</a>
      </div>
    </div>
  </form>
</div>

</body>
</html>
