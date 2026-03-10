<?php
error_reporting(E_ALL);        // Report all types of errors
ini_set('display_errors', 1);  // Show errors on the browser
ini_set('display_startup_errors', 1);

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once '../db.php';
    
  $propertyId = $_POST['property_id'];
  $ownerId = $_SESSION["id"];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $availableRooms = $_POST['available_rooms'];
    $description = $_POST['description'];
    $amenitie = isset($_POST['amenities']) ? $_POST['amenities'] : [];
    $amenities = implode(',', $amenitie);


    $errors = [];



if(empty($name)){


$errors["emptyName"] = "* Enter Property Name";

}

if(empty($location)){

$errors["emptyLocation"] = "* Enter Location";

}

if(empty($price)){

$errors["emptyRent"] = "* Enter Monthly Rent";

}

if(empty($availableRooms)){

$errors["emptyRoom"] = "* Enter number of Available Rooms";

}

$file = $_FILES["image"];

if($errors){
    $_SESSION["errors"] = $errors;

     header("location:editProperty.php?id=$propertyId");
    die();}
    

    // Handle multiple image uploads
    
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

        $allowedExt = ["jpg","jpeg","png"];

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
    // Ensure 4 slots exist
    for ($i = count($images); $i < 4; $i++) {
        $images[$i] = '';
    }

    // Build SQL update query
    $updateSql = "UPDATE properties 
                  SET Names='$name', 
                      Locations='$location', 
                      Price='$price', 
                      Available_rooms='$availableRooms', 
                      Amenities='$amenities', 
                      Descriptions='$description'";

        if (!empty($images[0])) $updateSql .= ", Image1='{$images[0]}'";
        if (!empty($images[1])) $updateSql .= ", Image2='{$images[1]}'";
        if (!empty($images[2])) $updateSql .= ", Image3='{$images[2]}'";
        if (!empty($images[3])) $updateSql .= ", Image4='{$images[3]}'";
        
        $updateSql .= " WHERE Id='$propertyId' AND Owner_id='$ownerId'";

    // Run the query
    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['success'] = "Property updated successfully!";
        header("Location: MyProperties.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating property: " . mysqli_error($conn);
        header("location:editProperty.php?id=$propertyId");
        exit();
    }
    die();
  
}else{
     if (!isset($_GET['id'])) {
        header("location: MyProperties.php?notfound");
        exit();
    }
    $propertyId = $_GET['id'];
    $query = "SELECT * FROM properties WHERE id = '$propertyId' AND Owner_id = '$ownerId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $property = mysqli_fetch_assoc($result);
    } else {
        die("Property not found or access denied.");
    }
}