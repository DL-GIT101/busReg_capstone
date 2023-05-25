<?php
session_start();
require_once "../php/config.php";

if (isset($_GET['id'])) {

$user_id = urldecode($_GET['id']);

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "../user/upload/".$user_id."/".$row["logo"];
                } else {
                    $logo_path = "upload/No_image_available.svg";
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"];
                $business_activity = $row["activity"];
                $permit_status = $row["permit_status"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else {
                header("location: user_management.php");
                exit();
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }

    }

    $stmt->close();
    
    $mysqli->close();
} 
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        return $data;
}
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
    <script src="../js/map.js" defer></script>
    <script src="../js/displayMap.js" defer></script>
    <script src="../js/profile.js" defer></script>
    <title>User Profile</title>
</head>
<body>
<nav id="navbar">
<p>ADMIN</p> 
    <div id="logo">
        <a href="../index.php">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p> 
        </a>
    </div>
    
    <div id="user">
        <a href="../php/logout.php">Logout</a>
    </div>
</nav>

<div class="row">

    <nav id="sidebar">
        <ul>
            <li ><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li class="current"><img src="../img/register.png" alt=""><a href="user_management.php">MSME Management</a></li>
            <li><img src="../img/list.png" alt=""><a href="">MSME Permit</a></li>
            
        </ul>
    </nav>

    <div id="content">
        <div class="container flex-row"> 
            
            <div id="profile">
                 <div class="frame" >
                    <p class="sentence">Business Profile</p>
                    <div class="logo_container"> 
                        <img src="<?= $logo_path ?>" alt="Logo">
                    </div>
                    <div>
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $name ?></p> 
                    </div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Activity</p> 
                    <div class="item title"><?= $business_activity ?></div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Permit Status</p> 
                    <div class="item title " id="permit_status"><?= $permit_status ?></div>
                </div>
            </div>
            
                <div class="frame wide">
                    <p class="title">Location</p>
                    <div id="map">
                        <p id="latitude" class="hidden"><?= $latitude ?></p>
                        <p id="longitude" class="hidden"><?= $longitude ?></p>
                    </div>
                </div>
           

        </div>
    </div>
    
       
       
        </div>
    </div>
</div>
</body>
</html>