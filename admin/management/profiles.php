<?php
session_start();
require_once "../../php/connection.php";

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: users.php");
}

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

$profile = "";
$none = "hidden";

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
                $permit_status = $row["permit_status"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else{
                $profile = "hidden";
                $none = "";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }
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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../../js/script.js" defer></script>
    <script src="../../js/map.js" defer></script>
    <script src="../../js/displayMap.js" defer></script>
    <script src="../../js/admin_td_click.js" defer></script>
    <title>MSME Profile</title>
</head>
<body>
<p id="user_id" class="hidden"><?= $user_id ?></p>
<modal id="user_del" class="hidden">
        <div class="content fail">
            <p class="title">Delete File</p>
            <p class="sentence">Are you sure you want to delete this Profile? This action cannot be undone</p>
            <div id="btn_grp" class="flex align-self-center">
                <a href="" id="user_link">Delete</a>
                <button>Cancel</button>
            </div>
        </div>
</modal>

    <nav>
        <div id="nav_logo">
                <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../../php/logout.php">Logout</a>
        </div>
    </nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li ><img src="../../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li class="current"><img src="../../img/register.png" alt=""><a href="users.php">MSME Management</a></li>
            <li><img src="../../img/list.png" alt=""><a href="../permit/msme.php">MSME Permit</a></li>
            
        </ul>
    </nav>

    <main class="flex-grow-1 flex-wrap content-center">
        
        <div class="actions space-between">
            <p id="page" class="title">Profile</p>
            <p class="sentence"> User ID : <?= $user_id ?></p>
            <div class="buttons">
                <a href="users.php" class="back">Management</a>
                <a href="edit_profile.php" class="<?= $none ?>">Add</a>
                <a href="edit_profile.php" class="<?= $profile ?>">Edit</a>
                <a id="action_dlt" class="delete <?= $profile ?>">Delete</a>
                <a href="documents.php" class="back">Documents</a>
            </div>
        </div>
        <p class="title <?= $none ?>">The user has not yet created a profile.</p>
        <content class="<?= $profile ?>">
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
        </content>
    </main>

</div>

</body>
</html>