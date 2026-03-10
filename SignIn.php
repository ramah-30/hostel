<?php

session_start();

?>


<!Doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
  <body>
   
        <section class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">     
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9 col-xl-8">
                     <?php
                        if(isset($_GET["SignInOrRegister"])){
                            echo '<div class="alert alert-primary lead text-center fw-bold">Please Sign In Or Registet to continue!</div>';
                        }
                        if(isset($_GET["logout"])){
                            unset($_SESSION["id"]);  
                        }
                    ?>
                    <div class="m-5 bg-light border rounded shadow p-4">
                        <h1 class="display text-center text-primary fw-bold my-3">Sign In</h1>
                        <?php if (isset($_GET["success"])){
                               echo '<div class="alert alert-success text-center">
                                    🎉 Registration successful! You can now Sign in
                                </div>';
                            }
                            if (isset($_SESSION["pwdsuccess"])){
                               echo '<div class="alert alert-success text-center">
                                    🎉 '.$_SESSION["pwdsuccess"].'! You can now Sign in
                                </div>';
                                unset($_SESSION["pwdsuccess"]);
                            }
                             ?>
                        <form class="m-3" action="signinHandle.php" method="post">
                            <div class="row justify-content-center">
                                <div class="col-md-7">
                                    <label for="email" class="form-label fw-bold"> Email</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">
                                                <i class="bi bi-envelope-at-fill"></i>
                                            </span>
                                            <input type="email" name="email" id="email"  class="form-control" placeholder="e.g. name@example.com" aria-label="Username" aria-describedby="basic-addon1">
                                        </div>
                                </div>
                                <div class="col-md-7">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-key-fill"></i></span>
                                            <input type="text" name="pwd" id="password"  class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                        </div>
                                </div>
                                <?php 
                            if(isset($_SESSION["Errors"])){
                                foreach($_SESSION["Errors"] as $error){
                                    echo'<div class="col-md-7">
                                    <p class="lead text-center text-danger m-3">'.$error.'</p>
                                    </div>';  
                                    session_unset();
                                }
                            } 
                                
                                ?>
                                <div  class="d-flex justify-content-center m-2"> 
                                    <button class="btn btn-primary btn-lg fw-bold " type="submit">Sign In</button>
                                </div>
                                <div class="d-flex justify-content-center m-2">
                                    <a href="forgotPassword.php" class="link-underline-primary justify-content-end">Forgot Password?</a>
                                </div>
                                <div class="d-flex justify-content-center m-2">You are not a Member Yet?
                                    <a href="SignUp.php" class="link-underline-primary justify-content-end">Register Now!</a>
                                </div>
                            </div>
                        </form>                 
                     </div>
                </div>
            </div>
     </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
    
    history.pushState(null, null, SignIn.php);
    window.onpopstate = function () {
        history.pushState(null, null, SignIn.php);
    };

    window.onhashchange = function () {
        history.pushState(null, null, SignIn.php);
    };
</script>

  </body>
</html>