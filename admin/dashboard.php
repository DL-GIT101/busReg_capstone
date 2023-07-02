<?php 
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../index.php");
    exit;
}

$sql_owner = "SELECT COUNT(*) FROM Owner";
    if($stmt_owner = $mysqli->prepare($sql_owner)){
        if($stmt_owner->execute()){
            $stmt_owner->bind_result($ownerCount);
            $stmt_owner->fetch();
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
    $stmt_owner->close();
    }

$sql_business = "SELECT COUNT(*) FROM Owner";
    if($stmt_business = $mysqli->prepare($sql_business)){
        if($stmt_business->execute()){
            $stmt_business->bind_result($businessCount);
            $stmt_business->fetch();
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
    $stmt_business->close();
    }
    
$sql_requirement = "SELECT BusinessID, COUNT(*) AS TotalCount FROM Requirement GROUP BY BusinessID";
if ($stmt_requirement = $mysqli->prepare($sql_requirement)) {
    if ($stmt_requirement->execute()) {
        $result = $stmt_requirement->get_result();

        $noneReqCount = 0;
        $incompleteCount = 0;
        $completeCount = 0;

        while($row = $result->fetch_assoc()) {
            $totalCount = $row['TotalCount'];

            if($totalCount === 11){
                $completeCount++;
            }else if($totalCount === 0){
                $noneReqCount++;
            }else{
                $incompleteCount++;
            }
        }
    } else {
        echo "Error retrieving data";
    }
    $stmt_requirement->close();
}    

$sql_permit = "SELECT COUNT(*) FROM Permit";
if($stmt_permit = $mysqli->prepare($sql_permit)){
    if($stmt_permit->execute()){
        $stmt_permit->bind_result($permitApproved);
        $stmt_permit->fetch();
    }else{
        echo "Oops! Something went wrong. Please try again later";
    }$stmt_permit->close();
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
                <li class="current"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="management/users.php">Management</a></li>
                <li><a href="permit/msme.php">Permit</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="admins.php">Admin List</a></li>
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
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="admins.php">Admin List</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <section>
                 <subsection>
                    <p class="sentence">Owners</p>     
                    <div class="info title data-display">
                        <p>Owner Profile</p>
                        <p><?= $ownerCount ?></p>
                    </div>
                    <div class="info title data-display">
                        <p>Business Profile</p>
                        <p><?= $businessCount ?></p>
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