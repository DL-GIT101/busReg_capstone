<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: ../login.php");
    exit;
}
require_once "../php/config.php";

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
    <title>Dashboard</title>
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
            <li class="current"><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li><img src="../img/register.png" alt=""><a href="">MSME Management</a></li>
            <li><img src="../img/list.png" alt=""><a href="">MSME Permit</a></li>
            
        </ul>
    </nav>

    <div id="content">

      
        <div id="profile">
                 <div class="frame" >
                    <p class="sentence">Users</p>   
                    <div class="item title row space-between">
                        <p>Total</p>
                        <p>0</p>
                    </div>  
                    <div class="item approved title row space-between">
                        <p>Profile</p>
                        <p>0</p>
                    </div>     
                </div>
                <div class="frame">
                    <p class="sentence">Permit</p> 
                    <div class="item pending title row space-between">
                        <p>Pending</p>
                        <p>0</p>
                    </div>  
                    <div class="item approved title row space-between">
                        <p>Approved</p>
                        <p>0</p>
                    </div>
                    <div class="item denied title row space-between">
                        <p>Denied</p>
                        <p>0</p>
                    </div> 
                    <div class="item none title row space-between">
                        <p>None</p>
                        <p>0</p>
                    </div>      
                </div>
                <div class="frame">
                    <p class="sentence">Documents</p> 
                    <div class="item pending title row space-between">
                        <p>Incomplete</p>
                        <p>0</p>
                    </div>  
                    <div class="item approved title row space-between">
                        <p>Complete</p>
                        <p>0</p>
                    </div>
                    <div class="item none title row space-between">
                        <p>None</p>
                        <p>0</p>
                    </div>        
                </div>
        </div>
        <div class="frame wide">
                    <p class="title">Business Location</p>
                    <div id="map"></div>
                </div>
        </div>

   
</div>

</body>
</html>