<?php
session_start();

?>
<!Doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
      #id{
        display: none;
      }
    </style>
</head>
  <body>
        <section class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">     
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9 col-xl-8 ">
                    <div class="m-5 bg-light border rounded shadow p-4">
                        <h1 class="display text-center text-primary fw-bold my-3">Sign Up</h1>
                        <form class="m-3" action="signupHandle.php" method="post" >
                            <div class="row justify-content-center">
                                <div class="col-md-7">
                                    <label for="firstName" class="form-label  fw-bold">First Name</label>
                                            <input type="text" name="firstName" id="firstName"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                            <?php if (isset($_SESSION["Errors"]["firstName"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["firstName"].'</small>';
                                            }?>
                                </div>
                                <div class="col-md-7">
                                    <label for="lastName" class="form-label fw-bold">Last Name</label>
                                            <input type="text" name="lastName" id="lastName"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                            <?php if (isset($_SESSION["Errors"]["lastName"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["lastName"].'</small>';
                                            }?>
                                </div>
                                <div class="col-md-7">
                                    <label for="email" class="form-label  fw-bold">Email Address</label>
                                            <input type="email" name="email" id="email"  class="form-control" placeholder="e.g. name@example.com" aria-label="Username" aria-describedby="basic-addon1">
                                            <?php if (isset($_SESSION["Errors"]["WrongEmail"] )){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["WrongEmail"].'</small>';
                                            }
                                            if (!isset($_SESSION["Errors"]["WrongEmail"]) && isset($_SESSION["Errors"]["emailExist"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["emailExist"].'</small>';
                                            }
                                            ?>
                                </div>
                                <div class="col-md-7">
                                    <label for="number" class="form-label  fw-bold">Phone Number</label>
                                            <input type="text"  name="number" id="number"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                            <?php if (isset($_SESSION["Errors"]["InvalidPhone"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["InvalidPhone"].'</small>';
                                            }?>
                                </div>
                                <div class="col-md-7">
                                    <label for="role" class="form-label  fw-bold ">Role</label>
                                    <select class="form-select" name="role" onchange="changes()" id="role" aria-label="Default select example">
                                        <option selected  value="Student">Student</option>
                                        <option value="House Owner">House Owner</option>
                                    </select>
                                    <?php 
                                             if (isset($_SESSION["Errors"]["InvalidNida"])){
                                               echo '
                                               <small class="text-danger">'. $_SESSION["Errors"]["InvalidNida"].'</small>';}
                                            ?>
                                </div>
                                
                                <div class="col-md-7" id="id">
                                    <label for="id" class="form-label  fw-bold">Nationa ID</label>
                                            <input type="text" name="id" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                                <div class="col-md-7">
                                    <label for="password" class="form-label  fw-bold">Password</label>
                                            <input type="password" name="pwd" id="password"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                            <div class="strength-bar mt-1"><div id="strength-fill"></div></div>
                                            <div id="strength-text" class="strength-text"></div>
                                            <?php if (isset($_SESSION["Errors"]["WeakPassword"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["WeakPassword"].'</small>';
                                            }?>
                                </div>
                                <div class="col-md-7">
                                    <label for="cPassword" class="form-label  fw-bold">Confirm Password</label>
                                            <input type="password" name="cpwd" id="cPassword"  class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                                </div>

                                 <?php if (isset($_SESSION["Errors"]["UnmatchPassword"])){
                                               echo '<small class="text-danger">'. $_SESSION["Errors"]["UnmatchPassword"].'</small>';
                                            }?>
                                <?php 
                                
                                    if(isset($_SESSION["Errors"]["EmptyFiled"])){
                                    echo'<div class="col-md-7">
                                    <p class="lead text-center text-danger m-1">'.$_SESSION["Errors"]["EmptyFiled"].'</p>
                                    </div>';  
                                    session_unset();
                                     }
                                      if(isset($_SESSION["Errors"]["DBError"])){
                                    echo'<div class="col-md-7">
                                    <p class="lead text-center text-danger m-1">'.$_SESSION["Errors"]["DBError"].'</p>
                                    </div>';  
                                    
                                     }
                                     session_unset();
                                ?>
                                
                                <div  class="d-flex justify-content-center m-2"> 
                                    <button class="btn btn-primary btn-lg fw-bold " type="submit">Register</button>
                                </div>
                                <div class="d-flex justify-content-center m-1">
                                    <a href="SignIn.php" class="link-underline-primary justify-content-end">Already have an Account?Sign In</a>
                                </div>
                            </div>
                        </form>                 
                     </div>
                </div>
            </div>
     </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
const pwd = document.getElementById("password");
const fill = document.getElementById("strength-fill");
const text = document.getElementById("strength-text");


function changes(){
  
const role = document.getElementById("role");
const id = document.getElementById("id");

if(role.value === "House Owner"){
    id.style.display="block";
}else{
  id.style.display = "none";
}
}
pwd.addEventListener("input", function() {
  const value = pwd.value;
  let strength = 0;

  // Scoring rules
  if (value.length >= 8) strength++;
  if (/[0-9]/.test(value)) strength++;
  if (/[a-z]/.test(value)) strength++;
  if (/[A-Z]/.test(value)) strength++;
  if (/[@$!%*?&]/.test(value)) strength++;

  // Visual feedback
  switch (strength) {
    case 0:
      fill.style.width = "0";
      text.textContent = "";
      break;
    case 1:
      fill.style.width = "20%";
      fill.style.backgroundColor = "red";
      text.textContent = "Very Weak";
      text.style.color = "red";
      break;
    case 2:
      fill.style.width = "40%";
      fill.style.backgroundColor = "orange";
      text.textContent = "Weak";
      text.style.color = "orange";
      break;
    case 3:
      fill.style.width = "60%";
      fill.style.backgroundColor = "yellow";
      text.textContent = "Fair";
      text.style.color = "goldenrod";
      break;
    case 4:
      fill.style.width = "80%";
      fill.style.backgroundColor = "#0dcaf0";
      text.textContent = "Good";
      text.style.color = "#0dcaf0";
      break;
    case 5:
      fill.style.width = "100%";
      fill.style.backgroundColor = "green";
      text.textContent = "Strong";
      text.style.color = "green";
      break;
  }
});
</script>
  </body>
</html>