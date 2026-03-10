<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);


if($_SERVER["REQUEST_METHOD"] === "POST"){
    
    $fName = trim($_POST["firstName"]);
    $lName = trim($_POST["lastName"]);
    $email = trim($_POST["email"]);
    $number = trim($_POST["number"]);
    $role = trim($_POST["role"]);
    $id = $_POST["id"];
    $pwd = $_POST["pwd"];
    $confirmPwd = $_POST["cpwd"];
    
    include_once 'db.php';
    session_start();

    $errors = [];

   
        if (empty($fName)){
             $errors["firstName"] = "* First name is required";
        }
        if (empty($lName)){
             $errors["lastName"] = "* Last name is required";
        }
        if($role === "House Owner" && !preg_match("/^[0-9]{20}$/", $id)){
             $errors["InvalidNida"] = "NIDA format is invalid!";
        }

        if(empty($fName) || empty($lName) || empty($email) || empty($number) || empty($role) || empty($pwd) || empty($confirmPwd)){
            $errors["EmptyFiled"] = "Please Fill in all the Fields!";
        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors["WrongEmail"] = "* Please Enter a Valid Email Address!";
        }

        if(strlen($pwd) < 8){
            $errors["WeakPassword"] = "* Passwords must contain atleast 8 characters!";
        }


        if (!preg_match('/^[0-9]{10}$/', $number)) {
             $errors["InvalidPhone"] = "* Phone number must contain only digits (10).";
        }

        if($pwd !== $confirmPwd){
            $errors["UnmatchPassword"] = "* Passwords DON'T MATCH please try again!";
        }
                        
            $sql = "SELECT * FROM Users WHERE Email = '$email'";
            $result = mysqli_query($conn,$sql);

            if(mysqli_num_rows($result)>0){
                $errors["emailExist"] = "* This email address is already registered.";
            }
                

            if(empty($errors)){

                $hashedPwd= password_hash($pwd,PASSWORD_BCRYPT);
                $email = strtolower($email);
                if($id){
                    $sql= "INSERT INTO Users(Firstname,Lastname,Email,PhoneNumber,Roles,Pwd,Nation_Id) VALUES('$fName','$lName','$email','$number','$role','$hashedPwd','$id')";
                }else{
                    $sql= "INSERT INTO Users(Firstname,Lastname,Email,PhoneNumber,Roles,Pwd) VALUES('$fName','$lName','$email','$number','$role','$hashedPwd')";
                }

                

                if(mysqli_query($conn,$sql)){
                    header("Location: SignIn.php?success=1");
                    exit();
                }else {
                    $errors["DBError"] = "Something went wrong while saving user.";
                 }                
            }
           

    $_SESSION["Errors"] = $errors;
    header("location:SignUp.php");
    die();   
}
else{
    header("location:SignUp.php");
}