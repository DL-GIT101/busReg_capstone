<?php 
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $log = true;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="js/script.js" defer></script>
    <script src="js/map.js" defer></script>
    <title>Business Registration</title>
</head>
<body>
    <nav id="navbar">
       <div id="logo">
        <a href="index.php">
            <img src="img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p>  
        </a>
       </div>

       <div id="user">
    <?php 
        if(isset($log)){
            echo '<a href="user/welcome.php">Hi, '.$_SESSION['email'].'</a> 
                  <a href="php/logout.php">Logout</a>';
        } else {
            echo '<a href="user/register.php">Register</a>
                  <a href="login.php">Login</a>';
        }
    ?>
            
       </div>
    </nav>

    <div id="content">
        <!-- temp map  -->
        <div id="map">
            
        </div>
    </div>
</body>
</html>