<?php 
session_start();
require_once "php/functions.php";


if(checkRole($_SESSION["role"]) === "user") {
    $links = '
                <li><a href="user/dashboard.php">Dashboard</a></li>
                <li><a href="php/logout.php">Logout</a></li>
            ';
}elseif(checkRole($_SESSION["role"]) === "admin") {
    $links = '
                <li><a href="admin/dashboard.php">Dashboard</a></li>
                <li><a href="php/logout.php">Logout</a></li>
            ';
}else{
    $links = '
                <li><a href="login.php">Login</a></li>
                <li><a href="user/register.php">Register</a></li>
            ';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- OpenStreetMap Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <!-- Javascript -->
    <script src="js/script.js" defer></script>
    <script src="js/map.js" defer></script>
    <script src="js/showMSMELocation.js" defer></script>
    <title>BPLO</title>
</head>
<body>

    <nav>
        <div class="logo">
            <img src="img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <?= $links ?>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container">
            <map id="map"></map>
        </div>
    </main>
</body>
</html>