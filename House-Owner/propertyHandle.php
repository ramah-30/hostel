<?php

error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
if($_SERVER["REQUEST_METHOD"]==="POST"){

include_once '../db.php';

$ownerId = $_SESSION["id"];

$name = htmlspecialchars(trim($_POST["name"]));
$location = htmlspecialchars(trim($_POST["location"]));
$propertyType = htmlspecialchars($_POST["type"]);
$MonthlyRent = htmlspecialchars(trim($_POST["price"]));
$availableRooms = htmlspecialchars($_POST["rooms"]) ;
$amenitie = isset($_POST['amenities']) ? $_POST['amenities'] : [];
$description = htmlspecialchars(trim($_POST["description"]));

$errors = [];

if(empty($name)){

$errors["emptyName"] = "* Enter Property Name";

}

if(empty($name)){

$errors["emptyLocation"] = "* Enter Location";

}

if(empty($name)){

$errors["emptyRent"] = "* Enter Monthly Rent";

}

if(empty($name)){

$errors["emptyRoom"] = "* Enter number of Available Rooms";

}

$file = $_FILES["image"];

if(empty($file["name"][0])){
$errors["emptyCover"] = "* You must select a Cover Image of your property";
}

if(empty($file["name"][1]) &&empty($file["name"][2])  && empty($file["name"][3]) ){
    $errors["emptyAddtional"] = "* Upload atleast (1) additional Image";
}
if($errors){
    $_SESSION["errors"] = $errors;

     header("location:addProperty.php");
    die();
}else{
    $images = [];

    for($i=0; $i<count($file["name"]);$i++){

        if ($file["error"][$i] === 4) {
        continue;
    }

        $file_name =$file["name"][$i];
        $file_tmp = $file["tmp_name"][$i];
        $file_error = $file["error"][$i];
        $file_type = $file["type"][$i];
        $file_size = $file["size"][$i];

        $fileExt = explode('.',$file_name);
        $fileActualExt = strtolower(end($fileExt));

        $allowedExt = ["jpg","jpeg","png","avif","webp"];

        if(in_array($fileActualExt,$allowedExt)){
            if($file_error === 0){
                if($file_size < 5000000){

                    $file_new_name = uniqid('img_',true).".".$fileActualExt;
                    $destnation = '../images/'.$file_new_name;
                    move_uploaded_file($file_tmp,$destnation);
                    array_push($images,$file_new_name);                    
                }else{
                    $_SESSION["largeImages"] = "Images size must be less than 5MB";
                     header("location:addProperty.php");
                     die();
                }
            }else{
                $_SESSION["uploadError"] = "Something went wrong while Uploading images,try again!";
                 header("location:addProperty.php");
                 die();
            }
        }else{
            $_SESSION["wrongFormat"] = "Allowed Image Formats are jpg, jpeg and png";
               header("location:addProperty.php");
            die();
        }

        }

    }
    $amenities = implode(',',$amenitie);
    for ($i = count($images); $i < 4; $i++) {
    $images[$i] = '';
} 
    $sql = "INSERT INTO properties(Owner_id,Names,Locations,Types,Price,Available_rooms,Amenities,Descriptions,Image1,Image2,Image3,Image4) VALUES('$ownerId','$name','$location','$propertyType','$MonthlyRent','$availableRooms','$amenities','$description','$images[0]','$images[1]','$images[2]','$images[3]');";
    $result = mysqli_query($conn,$sql);

    $_SESSION["addSuccessful"] = "Property Added Successfully";
     header("location:addProperty.php");
    die();

}else{
    header("location:addProperty.php");
    die();

}

