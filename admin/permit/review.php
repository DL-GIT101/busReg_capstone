<?php 
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: msme.php");
}

$modal_display = "hidden";

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

    $sql4 = "SELECT * FROM users WHERE id = ?";

    if($stmt4 = $mysqli->prepare($sql4)){
        $stmt4->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt4->execute()){
            $result = $stmt4->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $email = $row['email'];
                
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt4->close();
    }

    
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $errorMsg = 'errorMsg_';
        $errorCount = "no";
        $length = 11;

        $status = array();
        $denied_msg = array();

        for($i = 1; $i <= $length; $i++){
            $errorMsg = 'errorMsg_' . $i;

           if(isset($_POST['status_'.$i])){

                $status_review = validate($_POST['status_'.$i]);

                if($status_review === "Denied"){

                    $message_review = validate($_POST['denied_message_'.$i]);

                    if(!empty($message_review)){

                        array_push($status, $status_review);
                        array_push($denied_msg, $message_review);

                    }else{
                        $$errorMsg = "Denied Document must have a message";
                        $errorCount = "yes";
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

    if($errorCount === "no"){

        $serialized_status = serialize($status);
        $serialized_denied = serialize($denied_msg);

        $sql = "UPDATE new_documents SET status = ? , message = ? WHERE user_id = ?";
       
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('sss',$param_Stat,$param_msg, $param_id);

            $param_id = $user_id;
            $param_Stat = $serialized_status;
            $param_msg = $serialized_denied;

            if($stmt->execute()){
                $modal_display = "";
                $modal_status = "success";
                $modal_title = "Successful";
                $modal_message = "All changes has been updated";
                $modal_button = '<a href="review.php">OK</a>';
            }else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Updating Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="msme.php">OK</a>';
            }
            $stmt->close();
        }
    }
}

$mysqli->close();

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
    <script src="../../js/profile.js" defer></script>
    <script src="../../js/map.js" defer></script>
    <script src="../../js/showLocation.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/table.js" defer></script>
    <title>Review Information</title>
</head>
<body>

    <modal class="<?= $modal_display ?>">
        <div class="content <?= $modal_status ?>">
            <p class="title"><?= $modal_title ?></p>
            <p class="sentence"><?= $modal_message ?></p>
            <div class="button-group">
                <?= $modal_button ?>
            </div>
        </div>
    </modal>

    <nav>
        <div class="logo">
            <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../management/users.php">Management</a></li>
                <li class="current"><a href="msme.php">Permit</a></li>
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li><a href="msme.php">List</a></li>
                <li  class="current"><a href="review.php">Review</a></li>
                <li><a href="approve.php">Approve</a></li>
            </ul>
        </div>
    </nav>

    <nav id="subnav">
        <div class="logo">
            <img src="../../img/admin.svg" alt="Tarlac City Seal">
            <p>Admin</p>  
        </div>
        <div class="button-group">
            <ul>
                <li><a href="msme.php">List</a></li>
                <li  class="current"><a href="review.php">Review</a></li>
                <li><a href="approve.php">Approve</a></li>
            </ul>
        </div>
    </nav>

    <main>
    <div class="column-container height-auto">
        <div class="container height-auto">
            <section>
                <subsection class="space-between">
                    <p class="sentence">Business Profile</p>
                    <div class="logo"> 
                        <img src="<?= $logo_path ?>" alt="Business Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $business_activity ?></p> 
                    </div>
                </subsection>
                <subsection class="space-between">
                    <p class="sentence">Business Owner</p> 
                    <p class="sentence-title text-center"><?= $name ?></p> 
                    <div class="text-center">
                        <p class="sentence"><?= $gender ?></p> 
                        <p class="sentence"><?= $contact ?></p>
                    </div>
                </subsection>
            </section>
            <section class="map-container">
                <subsection>
                        <p class="title">Location</p>
                        <map id="map">
                            <p id="latitude" class="hidden"><?= $latitude ?></p>
                            <p id="longitude" class="hidden"><?= $longitude ?></p>
                        </map>
                </subsection>
            </section>
            <section>
                <subsection>
                    <p class="title">Actions</p> 

                    <a class="action approve" href="approve.php"><img src="../../img/approve-doc.svg" alt="Edit"></a>
                    <p class="sentence text-center">ID: <?= $user_id ?></p>
                </subsection>
                <subsection>
                    <p class="sentence">Address</p> 
                    <div class="info sentence"><?= $address ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title" id="permit-status"><?= $permit_status ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Email</p> 
                    <div class="info sentence"><?= $email ?></div>
                </subsection>
            </section>
        </div>

        <div class="column-container height-auto">
            <div class="text-center">
                <p class="title">Documents</p>
                <p class="sentence">Review the following documents carefully</p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <table>
                    <tr>
                        <th>Requirement</th>
                        <th>View</th>
                        <th>Status</th>
                        <th>Review</th>
                        <th>Message</th>
                    </tr>
                    <?php 
                        $requirements_names = array(
                            'Barangay Clearance for business',
                            'DTI Certificate of Registration',
                            'On the Place of Business <img id="info" src="../../img/info.svg" alt="">',
                            'Community Tax Certificate',
                            'Certificate of Zoning Compliance',
                            'Business Inspection Clearance',
                            'Valid Fire Safety Inspection Certificate/Official Receipt',
                            'Sanitary Permit',
                            'Environmental Compliance Clearance',
                            'Latest 2x2 picture',
                            'Tax Order of Payment'
                        );
                        $message_array = array(
                            'Incorrect/Outdated Information',
                            'Insufficient Detail',
                            'Incorrect Document',
                            'Low Image Resolution',
                            'Overexposure/Underexposure',
                            'Misleading/Manipulated Visuals',
                            'File Corruption',
                            'Invalid File Extension'
                        );
                        foreach($requirements_names as $index => $fileName){
                                $errorMsg = 'errorMsg_' . $index+1;
                            echo '  <tr>
                                        <td>'.$fileName.'</td>';

                            if(empty($requirements_fetch[$index])){
                                echo '  <td></td>
                                        <td></td>
                                        <td></td>
                                    ';
                            }else{
                                echo    '<td>
                                            <a class="view" target="_blank" href="../../user/upload/'.$user_id.'/'.$requirements_fetch[$index].'"><img src="../../img/view.svg" alt="View"></a>
                                        </td>

                                        <td>
                                            <div class="status">'.$status_fetch[$index].'</div>
                                        </td>

                                        <td>
                                            <select class="review" name="status_'.($index+1).'" id="status_'.($index+1).'">
                                                <option class="uploaded" value="Uploaded"'.(($status_fetch[$index] === "Uploaded") ? "selected" : "" ).'>Uploaded</option>
                                                <option class="pending" value="Pending"'.(($status_fetch[$index] === "Pending") ? "selected" : "" ).'>Pending</option>
                                                <option class="denied" value="Denied"'.(($status_fetch[$index] === "Denied") ? "selected" : "" ).'>Denied</option>
                                                <option class="approved" value="Approved"'.(($status_fetch[$index] === "Approved") ? "selected" : "" ).'>Approved</option>
                                            </select>
                                        </td>

                                        <td>
                                        <select class="denied-message" id="denied_message_'.($index+1).'" name="denied_message_'.($index+1).'">
                                        <option value="" disabled selected>Select Message...</option>';
                                        foreach($message_array as $denied){
                                            echo "<option value='$denied' " . ($message_fetch[$index] === $denied ? "selected" : "") . ">$denied</option>";
                                        }
                                echo    '</select>
                                        <div class="denied-error">'.${$errorMsg}.'</div>
                                        </td>';
                                
                                
                            }
                            echo '</tr>';
                        }
                    ?>  
                </table>
                <input type="submit" value="Update">
            </form> 
        </div>  
    </div>
    </main>
</body>
</html>