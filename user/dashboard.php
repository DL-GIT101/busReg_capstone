<?php 
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../php/connection.php";

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = validate($_SESSION['id']);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "upload/".$_SESSION['id']."/".$row["logo"];
                } else {
                    $logo_path = "../img/No_image_available.svg";
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"];
                $business_activity = $row["activity"];
                $permit_status = $row["permit_status"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else {
                header("location: profile.php");
                exit();
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }

    }

    $stmt->close();
    
    $mysqli->close();

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
     <script src="../js/script.js" defer></script>
    <script src="../js/map.js" defer></script>
    <script src="../js/displayMap.js" defer></script>
    <title>Welcome</title>
</head>
<body>
    <nav>
        <div id="nav_logo">
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <div id="account">
             <a href="profile.php">Profile</a>
             <a href="../php/logout.php">Logout</a>
        </div>
    </nav>

    <main>    
        <content>
            <section class="flex-grow-2">
                <subsection class="space-around">
                    <p class="sentence">Business Profile</p>
                    <div id="logo"> 
                        <img src="<?= $logo_path ?>" alt="Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $name ?></p> 
                    </div>
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Business Activity</p> 
                    <div class="info title"><?= $business_activity ?></div>
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title <?= strtolower($permit_status)?>" id="permit_status"><?= $permit_status ?></div>
                </subsection>
            </section>
            <section class="flex-grow-15">
                <subsection>
                        <p class="title">Location</p>
                        <map id="map">
                            <p id="latitude" class="hidden"><?= $latitude ?></p>
                            <p id="longitude" class="hidden"><?= $longitude ?></p>
                        </map>
                </subsection>
            </section>
            <section>
                <subsection>
                        <p class="title">Services</p>
                        <a href="permit.php" class="service">New Business Permit</a>
               </subsection>        
            </section>
        </content>
    </main>
</body>
</html>