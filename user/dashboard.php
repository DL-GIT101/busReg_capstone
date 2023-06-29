<?php 
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";


if($_SESSION["role"] !== "user"){
    header("location: ../index.php");
    exit;
}

$sql3 = "SELECT * FROM Owner WHERE OwnerID = ?";

    if($stmt3 = $mysqli->prepare($sql3)){
        $stmt3->bind_param("s",$param_id);

        $param_id = validate($_SESSION['OwnerID']);

        if($stmt3->execute()){
            $result = $stmt3->get_result();

            if($result->num_rows == 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fname = $row["FirstName"];
                $mname = $row["MiddleName"];
                $lname = $row["LastName"];
                $suffix = $row["Suffix"];
                $gender = $row["Gender"];
                $contact = $row["ContactNumber"];
                $address = $row["Address"];
                $barangay = $row["Barangay"];
                $_SESSION["OwnerID"] = $row['OwnerID'];

            }else {
                header("location: Owner/edit_profile.php");
                exit();
            }
        }else{

            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Owner Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="../index.php">OK</a>';
        }
        $stmt3->close();
    }

$logo_path = "../img/No_image_available.svg";
/*
$sql = "SELECT * FROM Business WHERE OwnerID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = validate($_SESSION['id']);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "upload/".$_SESSION['id']."/".$row["logo"];
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"];
                $business_activity = $row["activity"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else {
                header("location: profile.php");
                exit();
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }

   

    $sql2 = "SELECT * FROM permit WHERE user_id = ?";

    if($stmt2 = $mysqli->prepare($sql2)){
        $stmt2->bind_param("s",$param_id);

        $param_id = validate($_SESSION['id']);

        if($stmt2->execute()){
            $result = $stmt2->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $permit_status = $row['status'];
                
            }else {
                $permit_status = "None";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt2->close();
    }
 */
   
    
    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../js/script.js" defer></script>
    <script src="../js/map.js" defer></script>
    <script src="../js/showLocation.js" defer></script>
    <script src="../js/profile.js" defer></script>
    <title>Welcome</title>
</head>
<body>
    <nav>
        <div class="logo">
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main>    
        <div class="container">
            <section>
                <subsection class="space-between">
                    <p class="sentence">Business Profile</p>
                    <div class="logo"> 
                        <img src="<?= $logo_path ?>" alt="Business Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $name ?></p> 
                    </div>
                </subsection>
                <subsection>
                    <p class="sentence">Business Activity</p> 
                    <div class="info title"><?= $business_activity ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title" id="permit-status"><?= $permit_status ?></div>
                </subsection>
            </section>
            <section class="map-container">
                <subsection>
                        <p class="title">Location</p>
                        <map id="map"></map>
                        <p id="latitude" class="hidden"><?= $latitude ?></p>
                        <p id="longitude" class="hidden"><?= $longitude ?></p>
                </subsection>
            </section>
            <section>
                <subsection>
                        <p class="title">Services</p>
                        <a href="documents.php" class="service">Upload Documents</a>
               </subsection>        
            </section>
            <section>
                <subsection>
                    <p class="title text-center">Owner Profile</p>
                    <p class="sentence">Name</p>
                    <div class="info title"><?= $fname.' '. $mname.' '.$lname.' '. $suffix ?></div>
                    <p class="sentence">Gender</p>
                    <div class="info title"><?= $gender ?></div>
                    <p class="sentence">Contact Number</p>
                    <div class="info title"><?= $contact ?></div>
                    <p class="sentence">Address</p>
                    <div class="info title"><?= $address.', '. $barangay ?></div>
                    <p class="sentence">Edit Profile</p>
                    <a class="action edit" href="Owner/edit_profile.php"><img src="../img/edit.svg" alt="Edit"></a>
                </subsection>
            </section>
        </div>
    </main>
</body>
</html>