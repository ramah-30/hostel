<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);


if($_SERVER["REQUEST_METHOD"] === "POST"){
    
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    $errors = [];
  session_start();
    if(!empty($email) && !empty($pwd)){

        include_once 'db.php';

        $sql = "SELECT * FROM users WHERE Email = '$email'";
        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result)>0){

            $row = mysqli_fetch_assoc($result);

                if(password_verify($pwd,$row['Pwd'])){

                    $_SESSION["id"] = $row["Userid"];
                    $_SESSION["data"] = $row;
                    
                    if($row["Roles"]==="House Owner"){
                        header("location:House-Owner/owner.php");
                    }elseif($row["Roles"]==="Admin"){
                        header("location:Admin/admin.php");
                    }else{
                        header("location:student/Home.php");
                    }
                    
                }
                else
                    {
                        $errors["wrongPassword"] = "Wrong Password,Please try Again " ;
                        header("location:SignIn.php");
                    }
        }
        else
            {
                $errors["userNotFound"] = "User Not Found Please try again!";
                header("location:SignIn.php");
            }
    }
    else
        {
            $errors["emptyFields"] = "Please Fill in all the Fields!";
            header("location:SignIn.php");
        }
        
    $_SESSION["Errors"] = $errors;
    die();
}
else
    {
        header("location:SignIn.php");
    }