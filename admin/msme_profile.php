<?php
session_start();
require_once "../php/config.php";

if (isset($_GET['id'])) {

$user_id = urldecode($_GET['id']);

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "../user/upload/".$user_id."/".$row["logo"];
                } else {
                    $logo_path = "../userupload/No_image_available.svg";
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"];
                $business_activity = $row["activity"];
                $permit_status = $row["permit_status"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];

            }else{
                $hidden = "hidden";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }

    $sql2 = "SELECT * FROM new_documents WHERE user_id = ?";
   
    if($stmt2 = $mysqli->prepare($sql2)){
        
        $stmt2->bind_param("s",$param_id);
        
        $param_id = $user_id;
       
        if($stmt2->execute()){
            $result = $stmt2->get_result();
            if($result->num_rows == 1){
           
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_requirements_fetch = $row["requirements"];
                $serialized_status_fetch = $row["status"];
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);                
            }
        }else {
            echo "error retrieving data";
        }
    }
    $stmt2->close();

    $mysqli->close();
} 
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../js/map.js" defer></script>
    <script src="../js/displayMap.js" defer></script>
    <script src="../js/profile.js" defer></script>
    <title>User Profile</title>
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
            <li ><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li class="current"><img src="../img/register.png" alt=""><a href="user_management.php">MSME Management</a></li>
            <li><img src="../img/list.png" alt=""><a href="">MSME Permit</a></li>
            
        </ul>
    </nav>

    <div id="content flex-column">
        <div class="container">

            <p class="title"><?= $user_id ?></p>
            <div class="actions">
                <button class="delete_btn">Delete</button>
                <button class="edit_btn">Edit</button>
            </div>

        </div>

        <div class="container flex-row <?= $hidden ?>"> 
            
            <div id="profile">
                 <div class="frame" >
                    <p class="sentence">Business Profile</p>
                    <div class="logo_container"> 
                        <img src="<?= $logo_path ?>" alt="Logo">
                    </div>
                    <div>
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $name ?></p> 
                    </div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Activity</p> 
                    <div class="item title"><?= $business_activity ?></div>
                </div>
                <div class="frame">
                    <p class="sentence">Business Permit Status</p> 
                    <div class="item title " id="permit_status"><?= $permit_status ?></div>
                </div>
            </div>
            
                <div class="frame wide">
                    <p class="title">Location</p>
                    <div id="map">
                        <p id="latitude" class="hidden"><?= $latitude ?></p>
                        <p id="longitude" class="hidden"><?= $longitude ?></p>
                    </div>
                </div>
        </div>
        <div class="container <?= $hidden ?>"> 
        
    <div class="intro">
        <p class="title">Uploaded Documents</p>
    </div>
        <table>
            <tr>
                <th>Requirement</th>
                <th>View</th>
                <th>Status</th>
            </tr>
            <?php 
                $requirements_names = array(
                    'Barangay Clearance for business',
                    'DTI Certificate of Registration',
                    'On the Place of Business',
                    'Community Tax Certificate',
                    'Certificate of Zoning Compliance',
                    'Business Inspection Clearance',
                    'Valid Fire Safety Inspection Certificate/Official Receipt',
                    'Sanitary Permit',
                    'Environmental Compliance Clearance',
                    'Latest 2x2 picture',
                    'Tax Order of Payment',
                    'Tax Order of Payment Official Receipt'
                );
                $count = 1;
                foreach($requirements_names as $fileName){
                        $errorMsg = 'errorMsg_' . $count;
                    echo '  <tr>
                                <td>'.$fileName.'</td>';

                    if(empty($requirements_fetch[$count-1])){
                      echo '<td></td>
                            <td></td>
                            ';
                    }else{
                        echo    '<td><a class="view_file" target="_blank" href="../user/upload/'.$user_id.'/'.$requirements_fetch[$count-1].'">View</a></td>
                        <td>'.$status_fetch[$count-1].'</td>';
                    }
                        echo '</tr>';
                $count++;
                }
            ?>
            
        </table>     
    </div>
    </div>

</div>
    </div>
</div>
</body>
</html>