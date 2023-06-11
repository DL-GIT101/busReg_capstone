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

    $sql3 = "SELECT * FROM permit WHERE user_id = ?";

    if($stmt3 = $mysqli->prepare($sql3)){
        $stmt3->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt3->execute()){
            $result = $stmt3->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $permit_status = $row['status'];
                
            }else {
                $permit_status = "None";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt3->close();
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
            $stmt->close();ini_set('display_errors', 1);
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
    <script src="../../js/contentSwitch.js" defer></script>
    <title>Review Information</title>
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

<modal id="info_modal" class="hidden">
        <div class="content">
            <p class="title">On the Place of Business</p>
            <p class="sentence">
                - Building/Occupancy Certificate, if owned	<br>
                - Lease of Contract, if rented	 <br>
                - Notice of Award/Award Sheet, if inside a Mall<br>
                - Homeowners/Neighborhood Certification of No Objection, if inside a subdivision or housing facility</p>
                <button>OK</button>                
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
                <a href="approve.php?id=<?= $user_id ?>" class="success">Approve</a>
                <a href="msme.php" class="back">List</a>

                <a id="content_1_edit" href="../management/edit_profile.php">Edit</a>
                <a id="content_1_document" class="back">Document</a>
                
                <a id="content_2_upload" class="hidden" href="../management/documents.php">Upload</a>
                <a id="content_2_profile" class="back hidden">Profile</a>
            </div>
        </div>

        <content id="content_1">
            <section class="flex-grow-2">
                <subsection class="space-around">
                    <p class="sentence">Business Profile</p>
                    <div id="logo"> 
                        <img src="<?= $logo_path ?>" alt="Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $business_activity ?></p> 
                    </div>
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title <?= strtolower($permit_status)?>" id="permit_status"><?= $permit_status ?></div>
                </subsection>
                <subsection class="space-around">
                    <p class="sentence">Business Owner</p> 
                    <div class="text-center">
                        <p class="title"><?= $name ?></p> 
                        <p class="sentence"><?= $gender ?></p> 
                        <p class="sentence"><?= $contact ?></p>
                    </div>
                </subsection>
            </section>
            <section class="flex-grow-15">
                <subsection>
                        <p class="title">Location</p>
                        <p class="sentence"><?= $address ?></p>
                        <map id="map">
                            <p id="latitude" class="hidden"><?= $latitude ?></p>
                            <p id="longitude" class="hidden"><?= $longitude ?></p>
                        </map>
                </subsection>
            </section>
        </content>

    <form id="content_2" class="hidden" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="text-center">
                <p class="sentence">Review the following documents carefully</p>
            </div>
            <table>
                <tr>
                    <th>Requirement</th>
                    <th>View</th>
                    <th>Status</th>
                    <th>Review</th>
                    <th>Message</th>
                </tr>one
                <?php 
                    $requirements_names = array(
                        'Barangay Clearance for business',
                        'DTI Certificate of Registration',
                        'On the Place of Business <img id="info" src="../../img/info.png" alt="">',
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
                    $message_array = array(
                        'Incorrect/Outdated Information',
                        'Insufficient Detail',
                        'Incorrect Document',
                        'Low Image Resolution',
                        'Overexposure/Underexposure',
                        'Misleading/Manipulated Visuals',
                        'File Corruption',
                        'Invalid Foneile Extension'
                    );
                    $count = 1;
                    foreach($requirements_names as $fileName){
                            $errorMsg = 'errorMsg_' . $count;
                        echo '  <tr>
                                    <td>'.$fileName.'</td>';

                        if(empty($requirements_fetch[$count-1])){
                            echo '  <td></td>
                                    <td></td>
                                    <td></td>
                                ';
                        }else{
                            echo    '<td><a class="view" target="_blank" href="../user/upload/'.$user_id.'/'.$requirements_fetch[$count-1].'">View</a></td>
                                    <td><div class="info '.strtolower($status_fetch[$count-1]) .'">'.$status_fetch[$count-1].'</div></td>
                                    <td>
                                    <select class="select_review" name="status_'.$count.'" id="status_'.$count.'">
                                        <option class="uploaded" value="Uploaded"'.(($status_fetch[$count-1] === "Uploaded") ? "selected" : "" ).'>Uploaded</option>
                                        <option class="pending" value="Pending"'.(($status_fetch[$count-1] === "Pending") ? "selected" : "" ).'>Pending</option>
                                        <option class="denied" value="Denied"'.(($status_fetch[$count-1] === "Denied") ? "selected" : "" ).'>Denied</option>
                                        <option class="approved" value="Approved"'.(($status_fetch[$count-1] === "Approved") ? "selected" : "" ).'>Approved</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select class="denied_message hidden" id="denied_message_'.$count.'" name="denied_message_'.$count.'">
                                    <option value="" disabled selected>Select Message...</option>';
                                    foreach($message_array as $denied){
                                        echo "<option value='$denied' " . ($message_fetch[$count-1] === $denied ? "selected" : "") . ">$denied</option>";
                                    }
                            echo    '</select>
                                    <div class="error_msg">'.${$errorMsg}.'</div>
                                    </td>';
                               
                            
                        }
                        echo '</tr>';
                    $count++;
                    }
                ?>  
            </table>
            <input type="submit" value="Review">
        </form>   
    </main>
</div>
</body>
</html>