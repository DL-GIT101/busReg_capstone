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
    <script src="../js/displayMap.js" defer></script>
    <script src="../js/profile.js" defer></script>
    <title>Welcome</title>
</head>
<body>
    <nav id="navbar">
       <div id="logo">
        <a href="../index.php">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p>  
        </a>
       </div>

       <div id="user">
            <a href="profile.php">Profile</a>
            <a href="../php/logout.php">Logout</a>
       </div>
    </nav>

    <div id="content">
        <div class="container flex-row"> 
            <div id="profile">
                 <div class="frame" >
                    <p class="sentence">Business Profile</p>
                    <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
                    <div>
                        <p class="title">Store Name</p>
                        <p class="sentence">Name of Owner</p> 
                    </div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Activity</p> 
                    <div class="item title">
                        Money Laundering
                    </div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Permit Status</p> 
                    <div class="item title approved">
                        Approved
                    </div>
                </div>
            </div>
            
            <div class="service">
                <div class="frame">
                    <p class="title">Services</p>
                    <a href="" class="item title services">
                      <span class="emphasize">New</span> Business Permit
                    </a>
                    <a href="" class="item title services">
                      <span class="emphasize">Renew</span> Business Permit
                    </a>
                </div>
                <div class="frame location">
                    <p class="title">Location</p>
                    <div id="map">
                        <!--temp- map -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>