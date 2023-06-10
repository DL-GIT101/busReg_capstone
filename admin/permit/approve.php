<?php
session_start();
require_once "../../php/connection.php";

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: ../management.php");
}
$modal = "hidden";

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                if (!empty($row["logo"])) {
                    $logo_path = "../../user/upload/".$user_id."/".$row["logo"];
                } else {
                    $logo_path = "../../img/No_image_available.svg";
                }
                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"]." ".$row["suffix"];
                $business_activity = $row["activity"];
                $permit_status = $row["permit_status"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"];
                $gender = $row['gender'];
                $contact = $row['contact_number'];
                $address = $row["address_1"]." ".$row["address_2"];

            }else{
                $profile = "hidden";
                $none = "";
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
                $serialized_message_fetch = $row["message"];
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);
                $message_fetch = unserialize($serialized_message_fetch);              
            }else{
                
            }
        }else {
            echo "error retrieving data";
        }
    $stmt2->close();
    }
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errorMsg = 'errorMsg_' . $i;
        $errorCount = 0;
        $length = 12;

        $status = array();
        $denied_msg = array();

        for($i = 1; $i <= $length; $i++){
            $errorMsg = 'errorMsg_' . $i;

           $status_review = validate($_POST['status_'.$i]);
           if(!empty($status_review)){

                if($status_review === "Denied"){

                    $message_review = validate($_POST['denied_message_'.$i]);
                    if(!empty($message_review)){
                        array_push($status, $status_review);
                        array_push($denied_msg, $message_review);
                    }else{
                        $$errorMsg = "Denied Document must have a message";
                        $errorCount++;
                    }

                }else{
                    array_push($status, $status_review);
                    array_push($denied_msg, null);
                }
            
           }else{
                array_push($status, null);
                array_push($denied_msg, null);
           }
        }

    if($errorCount === 0){
        $serialized_status = serialize($status);
        $serialized_denied = serialize($denied_msg);

        $sql = "UPDATE new_documents SET status = ? , message = ? WHERE user_id = ?";
       
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('sss',$param_Stat,$param_msg, $param_id);

            $param_id = $user_id;
            $param_Stat = $serialized_status;
            $param_msg = $serialized_denied;

            if($stmt->execute()){
                $modal = "";
                $status_modal = "success";
                $title = "Successful";
                $message = "All changes has been updated";
                $button = '<a href="review.php">OK</a>';
            }else{
                $modal = "";
                $status_modal = "fail";
                $title = "Updating Error";
                $message = "Try again later";
                $button = '<a href="../manangement.php">OK</a>';
            }
            $stmt->close();
        }
    }
}

    $mysqli->close();

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
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../../js/script.js" defer></script>
    <script src="../../js/map.js" defer></script>
    <script src="../../js/displayMap.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/file_modal.js" defer></script>
    <script src="../../js/contentSwitch.js" defer></script>
    <title>Approve</title>
</head>
<body>
<p id="user_id" class="hidden"><?= $user_id ?></p>
<modal class="<?= $modal ?>">
        <div class="content <?= $status_modal ?>">
            <p class="title"><?= $title ?></p>
            <p class="sentence"><?= $message ?></p>
            <?= $button ?>
        </div>
</modal>


    <nav>
        <div id="nav_logo">
                <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../../php/logout.php">Logout</a>
        </div>
    </nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li ><img src="../../img/dashboard.png" alt=""><a href="../dashboard.php">Dashboard</a></li>
            <li ><img src="../../img/register.png" alt=""><a href="../management/users.php">MSME Management</a></li>
            <li class="current"><img src="../../img/list.png" alt=""><a href="msme.php">MSME Permit</a></li>
            
        </ul>
    </nav>

    <main class="flex-grow-1 flex-wrap content-center">

    <div class="actions space-between">
            <p id="page" class="title">Review</p>
            <p class="sentence"> User ID : <?= $user_id ?></p>
            <div class="buttons">
                <a href="msme.php" class="back">List</a>

                <a id="content_1_edit" href="../management/edit_profile.php">Edit</a>
                <a id="content_1_document" class="back">Document</a>
                
                <a id="content_2_upload" class="hidden" href="../management/documents.php">Upload</a>
                <a id="content_2_profile" class="back hidden">Profile</a>
            </div>
        </div>

        
    </main>
</div>
</body>
</html>