<?php 
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";


if($_SESSION["role"] !== "Owner"){
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

$logo_displayed = "../img/No_image_available.svg";

$sql = "SELECT * FROM Business WHERE BusinessID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = validate($_SESSION['BusinessID']);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $logo = $row["Logo"];
                if($row["IssuedPermit"] == null){
                    $permit_status = "None";
                }else{
                    $permit_status = "Issued";
                }
                $bus_name = $row["Name"];
                $logo = $row["Logo"];
                if($row["Logo"] == null){
                    $logo = null;
                }else{
                    $logo_displayed = $logo = "Business/upload/".$_SESSION['BusinessID']."/".$row["Logo"];
                }
                $activity = $row["Activity"];
                $contact_b = substr($row["ContactNumber"],3);
                $address_b = $row["Address"];
                $barangay_b = $row["Barangay"];
                $latitude = $row["Latitude"];
                $longitude = $row["Longitude"]; 

            }else {
                $bus_name = "Not yet created";
            }
        }else{
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Business Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="../index.php">OK</a>';
        }
        $stmt->close();
    }   
    
    $mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="../img/tarlac-seal.ico" type="image/x-icon">
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
    <title>Dashboard</title>
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
                    <p class="title">Business Profile</p>
                    <div class="logo"> 
                        <img src="<?= $logo_displayed ?>" alt="Business Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $bus_name ?></p>
                        <p class="sentence"><?= $activity ?></p> 
                    </div>
                </subsection>
                <subsection>
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title" id="permit-status"><?= $permit_status ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Edit Business Profile</p> 
                    <a class="action edit" href="Business/edit_profile.php"><img src="../img/edit.svg" alt="Edit"></a>
                </subsection>
            </section>
            <section class="map-container">
                <subsection>
                        <p class="title">Location </p>
                        <p class="sentence"><?= $address_b.', '. $barangay_b ?></p> 
                        <map id="map"></map>
                        <p id="latitude" class="hidden"><?= $latitude ?></p>
                        <p id="longitude" class="hidden"><?= $longitude ?></p>
                </subsection>
            </section>
            <section>
                <subsection>
                        <p class="title">Services</p>
                        <a href="Business/upload_requirement.php" class="service">Upload Requirements</a>
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
                    <p class="sentence">Edit Owner Profile</p>
                    <a class="action edit" href="Owner/edit_profile.php"><img src="../img/edit.svg" alt="Edit"></a>
                </subsection>
            </section>
        </div>
    </main>
</body>
</html>