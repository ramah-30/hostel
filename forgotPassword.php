<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <section class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
      <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="bg-white border rounded shadow p-4">
          <h2 class="text-center text-primary fw-bold mb-3">Forgot Password</h2>
          <p class="text-center text-secondary">Enter your registered email to reset your password</p>

          <form action="forgotHandle.php" method="post">
            <div class="mb-3">
              <label for="email" class="form-label fw-bold">Email Address</label>
              <input type="email" name="email" id="email" class="form-control"  placeholder="e.g. name@example.com">
            </div>

            <?php
            if (isset($_SESSION['error'])) {
              echo '<div class="alert alert-danger text-center">'.$_SESSION['error'].'</div>';
              unset($_SESSION['error']);
            }

            if (isset($_SESSION['success'])) {
              echo '<div class="alert alert-success text-center">'.$_SESSION['success'].'</div>';
              unset($_SESSION['success']);
            }
            ?>

            <div class="d-grid">
              <button class="btn btn-primary fw-bold" type="submit">Send Verication Code</button>
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
