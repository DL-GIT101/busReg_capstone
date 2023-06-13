<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: ../login.php");
    exit;
}

require_once "../php/connection.php";
require_once "../php/validate.php";

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
    <script src="../js/display_MSME_loc.js" defer></script>
    <title>Dashboard</title>
</head>
<body>
    <nav>
        <div id="nav_logo">
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../php/logout.php">Logout</a>
        </div>
    </nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li class="current"><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li><img src="../img/register.png" alt=""><a href="management/users.php">MSME Management</a></li>
            <li><img src="../img/list.png" alt=""><a href="permit/msme.php">MSME Permit</a></li>
        </ul>
    </nav>

    <main class="flex-grow-1">
        <content>
            <section>
                 <subsection class="space-around">
                    <p class="sentence">Users</p>   
                    <div class="info none title flex space-between">
                        <p>Total</p>
                        <p><?= $userCount ?></p>
                    </div>  
                    <div class="info none title flex space-between">
                        <p>Profile</p>
                        <p><?= $profileCount ?></p>
                    </div>     
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Documents</p> 
                    <div class="info none title flex space-between">
                        <p>None</p>
                        <p><?= $noneReqCount ?></p>
                    </div>
                    <div class="info none title flex space-between">
                        <p>Incomplete</p>
                        <p><?= $incompleteCount ?></p>
                    </div>  
                    <div class="info none title flex space-between">
                        <p>Complete</p>
                        <p><?= $completeCount ?></p>
                    </div>        
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Permit</p> 
                    <div class="info none title flex space-between">
                        <p>None</p>
                        <p><?= $userCount-$permitApproved ?></p>
                    </div>
                    <div class="info none title flex space-between">
                        <p>Approved</p>
                        <p><?= $permitApproved ?></p>
                    </div>      
                </subsection>
            </section>
            <section class="flex-grow-15">
                <subsection>
                    <p class="title">Business Location</p>
                    <map id="map"></map>
                </subsection>
            </section>
        </content>
    </main>
</div>

</body>
</html>