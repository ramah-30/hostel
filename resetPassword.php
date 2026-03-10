<?php
session_start();
include_once 'db.php';


if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid or missing reset token.";
    header("Location: ForgotPassword.php");
    exit();
}

$token = $_GET['token']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <section class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="col-md-6 col-lg-5 col-xl-4">
      <div class="bg-white border rounded shadow p-4">
        <h2 class="text-center text-primary fw-bold mb-3">Reset Password</h2>
        <p class="text-center text-secondary">Enter your new password below</p>

        <form action="resetHandle.php" method="post">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

          <div class="mb-3">
            <label for="newPassword" class="form-label fw-bold">New Password</label>
            <input type="password" name="newPassword" id="newPassword" class="form-control">
          </div>

          <div class="mb-3">
            <label for="confirmPassword" class="form-label fw-bold">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
          </div>

          <?php
          if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger text-center">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
          }

          ?>

          <div class="d-grid">
            <button class="btn btn-primary fw-bold" type="submit">Update Password</button>
          </div>
        </form>

        <div class="text-center mt-3">
          <a href="SignIn.php" class="text-decoration-none">Back to Login</a>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
