<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";
require_once "../../php/checkPermit.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: users.php");
}

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

$modal_display = "hidden";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "../../user/upload/".$user_id."/".$row["logo"];
                } else {
                    $logo_path = "../../img/No_image_available.svg";
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"];
                $business_activity = $row["activity"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else{
                $modal_display = "";
                $modal_status = "warning";
                $modal_title = "User did not create a profile yet";
                $modal_message = "No profile found for the user";
                $modal_button = '<a href="users.php">Back</a>';
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }

    $sql2 = "SELECT * FROM permit WHERE user_id = ?";

    if($stmt2 = $mysqli->prepare($sql2)){
        $stmt2->bind_param("s",$param_id);

        $param_id = $user_id;

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

    $sql3 = "SELECT * FROM users WHERE id = ?";

    if($stmt3 = $mysqli->prepare($sql3)){
        $stmt3->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt3->execute()){
            $result = $stmt3->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $email = $row['email'];
                
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt3->close();
    }

$mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../../js/script.js" defer></script>
    <script src="../../js/profile.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/map.js" defer></script>
    <script src="../../js/showLocation.js" defer></script>
    <title>MSME Profile</title>
</head>
<body>
    <modal class="<?= $modal_display ?>">
        <div class="content <?= $modal_status ?>">
            <p class="title"><?= $modal_title ?></p>
            <p class="sentence"><?= $modal_message ?></p>
            <div class="button-group">
                <?= $modal_button ?>
            </div>
        </div>
    </modal>
    <nav>
        <div class="logo">
            <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li class="current"><a href="users.php">Management</a></li>
                <li><a href="../permit/msme.php">Permit</a></li>
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li><a href="users.php">List</a></li>
                <li class="current"><a href="profiles.php">Profile</a></li>
                <li><a href="documents.php">Documents</a></li>
            </ul>
        </div>
    </nav>

    <nav id="subnav">
        <div class="logo">
            <img src="../../img/admin.svg" alt="Tarlac City Seal">
            <p>Admin</p>  
        </div>
        <div class="button-group">
            <ul>
                <li><a href="users.php">List</a></li>
                <li class="current"><a href="profiles.php">Profile</a></li>
                <li><a href="documents.php">Documents</a></li>
            </ul>
        </div>
    </nav>

    <main>    
        <div class="container">
            <section>
                <subsection class="space-between">
                    <p id="page" class="sentence">Business Profile</p>
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
                    <p class="title">Actions</p> 

                    <div class="action delete"><img class="deleteUser" src="../../img/delete.svg" alt="Delete"></div>

                    <a class="action edit" href="editProfile.php"><img src="../../img/edit.svg" alt="Edit"></a>
                </subsection>
                <subsection>
                    <p class="sentence">User ID</p> 
                    <div id="user_id" class="info title"><?= $_SESSION['user_id'] ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Email</p> 
                    <div class="info title" id="permit-status"><?= $email ?></div>
                </subsection>
            </section>
        </div>
    </main>

</body>
</html>