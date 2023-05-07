<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/form.js" defer></script>
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
            <a href="../php/logout.php">Logout</a>
       </div>
    </nav>

    <div id="content">
        <div class="container row"> 
            <div class="profile">
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
                    <div class="item title services">
                      <span class="emphasize">Renew</span> Business Permit
                    </div>
                </div>
                <div class="frame"></div>
            </div>
        </div>
    </div>
</body>
</html>