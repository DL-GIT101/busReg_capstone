<?php 
    session_start();

    if($_SESSION["loggedin"] === true){
        $logged = '<a href="user/dashboard.php">Hi, '.$_SESSION['email'].'</a> 
              <a href="php/logout.php">Logout</a>';
    } elseif($_SESSION["loggedin"] === "admin"){
        $logged = '<a href="admin/dashboard.php">Hi, '.$_SESSION['email'].'</a> 
              <a href="php/logout.php">Logout</a>';
    }
    else {
        $logged = ' <a href="user/register.php">Register</a>
                    <a href="login.php">Login</a>';
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
    <title>BPLO</title>
</head>
<body>

    <nav>
        <div id="nav_logo">
                <img src="img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <div id="account">
            <?= $logged ?>
        </div>
    </nav>

    <main>
        <map id="map"></map>
    </main>
</body>
</html>