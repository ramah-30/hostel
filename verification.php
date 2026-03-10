<?php
session_start();
include_once 'db.php';
include_once 'sendverification.php';

if (isset($_GET['resend'])) {
    $newCode = rand(100000, 999999);
    $mail = $_SESSION["email"];
    $sql1 = "UPDATE Users SET Code='$newCode' WHERE Email = '$mail'";
    $result1 = mysqli_query($conn,$sql1);

        include_once 'sendverification.php';

        $message = "
         <h3>Verification Code</h3>
        <p>Your Verication Code is: <b>$newCode</b></p>
        <p>Enter The code to reset your Password .</p>
        ";

        sendBookingEmail($mail, $message);
header("Location: verification.php?var=1");
unset($_SESSION["email"]);
    $_SESSION["resend_success"] = "A new verification code has been sent.";
    
    exit();
}elseif($_SERVER["REQUEST_METHOD"] === "POST"){
$code =implode("",$_POST["code"]);


$sql = "SELECT * FROM Users WHERE Code = '$code';";
$result = mysqli_query($conn,$sql);

if(mysqli_fetch_row($result)>0){
  header("Location: resetPassword.php?token=$code");
        exit();
}else{
  $_SESSION["wrongCode"]= "Incorrect Code,Try again!";
}
}else{
  if(!isset($_GET["var"])){
header("Location: forgotPassword.php");
        exit();
}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify Code - U-Hostel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: #f1f5ff;
      font-family: Poppins, sans-serif;
    }
    .verify-box {
      max-width: 450px;
      margin: auto;
      margin-top: 70px;
      background: white;
      padding: 35px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .code-input {
      width: 55px;
      height: 55px;
      font-size: 22px;
      border-radius: 10px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>

<body>

<div class="verify-box text-center">
  <h3 class="fw-bold text-primary mb-3">Verify Your Code</h3>
  <p class="text-muted mb-4">
    We've sent a verification code to your Email Address.  
    Please enter the 6-digit code below.
  </p>

  <!-- Verification Form -->
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" class="d-flex flex-column align-items-center">

    <div class="d-flex gap-2 mb-4">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
      <input type="text" maxlength="1" name="code[]" class="form-control code-input">
    </div>
    <?php
            if (isset($_SESSION["emptyCode"])) {
              echo '<div class="alert alert-danger text-center">'.$_SESSION["emptyCode"].'</div>';
              unset($_SESSION["emptyCode"]);
            }
            if (isset($_SESSION["wrongCode"])) {
              echo '<div class="alert alert-danger text-center">'.$_SESSION["wrongCode"].'</div>';
              unset($_SESSION["wrongCode"]);
            }
            ?>
    <button type="submit" class="btn btn-primary w-100 fw-bold">Verify</button>

    <p class="text-muted mt-3">
      Didn’t receive the code?  
      <a href="verification.php?resend=1" class="text-primary">Resend Code</a>
    </p>
     <?php
            if (isset($_SESSION["resend_success"])) {
              echo '<div class="alert alert-success text-center">'.$_SESSION["resend_success"].'</div>';
              unset($_SESSION["resend_success"]);
            }?>
  </form>
</div>

<script>
  // Auto-focus to next input
  const inputs = document.querySelectorAll(".code-input");

  inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
      if (input.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    // Backspace goes to previous
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !input.value && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });
</script>

</body>
</html>
