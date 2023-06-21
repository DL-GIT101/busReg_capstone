<?php
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../index.php");
    exit;
}

$sql1 = "SELECT COUNT(*) FROM users WHERE id <> ?";
    if($stmt1 = $mysqli->prepare($sql1)){
        $stmt1->bind_param("s",$adminID);
        $adminID = validate($_SESSION['id']);
        if($stmt1->execute()){
            $stmt1->bind_result($userCount);
            $stmt1->fetch();
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }$stmt1->close();
    }

$sql2 = "SELECT COUNT(*) FROM user_profile";
    if($stmt2 = $mysqli->prepare($sql2)){
        if($stmt2->execute()){
            $stmt2->bind_result($profileCount);
            $stmt2->fetch();
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }$stmt2->close();
    }
    
$sql3 = "SELECT * FROM new_documents";
if ($stmt3 = $mysqli->prepare($sql3)) {
    if ($stmt3->execute()) {
        $result = $stmt3->get_result();
        $incompleteCount = 0;
        $completeCount = 0;
        
        while ($row = $result->fetch_assoc()) {
            $serializedRequirements = $row["requirements"];
            $requirements = unserialize($serializedRequirements);
            
            if (in_array(null, $requirements)) {  
                $incompleteCount++;
            } else {
                $completeCount++;
            }
        }
        $noneReqCount =  $userCount - ($incompleteCount + $completeCount);
    } else {
        echo "Error retrieving data";
    }
    $stmt3->close();
}    

$sql4 = "SELECT COUNT(*) FROM permit";
if($stmt4 = $mysqli->prepare($sql4)){
    if($stmt4->execute()){
        $stmt4->bind_result($permitApproved);
        $stmt4->fetch();
    }else{
        echo "Oops! Something went wrong. Please try again later";
    }$stmt4->close();
}   
    $mysqli->close();

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
    <script src="../js/showMSMELocation.js" defer></script>
    <title>Dashboard</title>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a class="current" href="dashboard.php">Dashboard</a></li>
                <li><a href="management/users.php">Management</a></li>
                <li><a href="permit/msme.php">Permit</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">

            </ul>
        </div>
    </nav>

    <nav id="subnav">
        <div class="logo">
            <img src="../img/admin.svg" alt="Tarlac City Seal">
            <p>Admin</p>  
        </div>
        <div class="button-group">
            <ul>
                
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <section>
                 <subsection>
                    <p class="sentence">Users</p>   
                    <div class="info title data-display">
                        <p>Total</p>
                        <p><?= $userCount ?></p>
                    </div>  
                    <div class="info title data-display">
                        <p>Profile</p>
                        <p><?= $profileCount ?></p>
                    </div>     
                </subsection>
                <subsection>
                    <p class="sentence">Documents</p> 
                    <div class="info title data-display">
                        <p>None</p>
                        <p><?= $noneReqCount ?></p>
                    </div>
                    <div class="info title data-display">
                        <p>Incomplete</p>
                        <p><?= $incompleteCount ?></p>
                    </div>  
                    <div class="info title data-display">
                        <p>Complete</p>
                        <p><?= $completeCount ?></p>
                    </div>        
                </subsection>
                <subsection>
                    <p class="sentence">Permit</p> 
                    <div class="info title data-display">
                        <p>None</p>
                        <p><?= $userCount-$permitApproved ?></p>
                    </div>
                    <div class="info title data-display">
                        <p>Approved</p>
                        <p><?= $permitApproved ?></p>
                    </div>      
                </subsection>
            </section>
            <section class="map-container">
                <subsection>
                    <p class="title">Business Location</p>
                    <map id="map"></map>
                </subsection>
            </section>
        </div>
    </main>
</body>
</html>