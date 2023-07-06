<?php 
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['id'])){
    $businessID = $_SESSION['businessID'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['businessID'])){
    $businessID = $_SESSION['businessID'];
}else{
    header("location: msme.php");
    exit;
}
$businessID = validate($businessID);
$modal_display = "hidden";

$logo = "../img/No_image_available.svg";

$sql_business = "SELECT * FROM Business WHERE BusinessID = ?";

    if($stmt_business = $mysqli->prepare($sql_business)){
        $stmt_business->bind_param("s",$param_id);

        $param_id = $businessID;

        if($stmt_business->execute()){
            $result = $stmt_business->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $logo = $row["Logo"];
                $bus_name = $row["Name"];
                $logo = $row["Logo"];
                if($row["Logo"] == null){
                    $logo_displayed = "../../img/No_image_available.svg";
                    $logo = null;
                }else{
                    $logo_displayed = $logo = "../../user/Business/upload/".$businessID."/".$row["Logo"];
                }
                $activity = $row["Activity"];
                $contact_b = substr($row["ContactNumber"],3);
                $address_b = $row["Address"];
                $barangay_b = $row["Barangay"];
                $latitude = $row["Latitude"];
                $longitude = $row["Longitude"]; 
                $ownerID = $row['OwnerID'];

            }else {
                $bus_name = "Not yet created";
            }
        }else{
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Business Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_business->close();
    }

$sql_owner = "SELECT * FROM Owner WHERE OwnerID = ?";

    if($stmt_owner = $mysqli->prepare($sql_owner)){
        $stmt_owner->bind_param("s",$param_id);

        $param_id = $ownerID;

        if($stmt_owner->execute()){
            $result = $stmt_owner->get_result();

            if($result->num_rows == 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fname = $row["FirstName"];
                $mname = $row["MiddleName"];
                $lname = $row["LastName"];
                $suffix = $row["Suffix"];
                $gender = $row["Gender"];
                $contact = $row["ContactNumber"];
                $address = $row["Address"];
                $barangay = $row["Barangay"];
                $userID = $row['UserID'];
            }
        }else{

            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Owner Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_owner->close();
    }

    $sql_req = "SELECT * FROM Requirement WHERE BusinessID = ?";
   
    if($stmt_req = $mysqli->prepare($sql_req)){
        
        $stmt_req->bind_param("s",$param_id);
        
        $param_id = $businessID;
       
        if($stmt_req->execute()){
            $result = $stmt_req->get_result();
            $requirements = array();
            $uploadRequirementsName = array();

            while ($row = $result->fetch_assoc()) {

            array_push($uploadRequirementsName,$row['Name']);
                
               $requirement = array(
                'RequirementID' => $row['RequirementID'],
                'Name' => $row['Name'],
                'FileName' => $row['FileName'],
                'Status' => $row['Status'],
                'Review' => $row['Review']
               );

               $requirements[] = $requirement;
            }
        }else {
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Requirement Error";
            $modal_message = "Requirements cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_req->close();
    }

    $permit_status = checkPermit($businessID);

    $documents = array(
        'Barangay Clearance for business',
        'DTI Certificate of Registration',
        'On the Place of Business',
        'Community Tax Certificate',
        'Certificate of Zoning Compliance',
        'Business Inspection Clearance',
        'Fire Safety Inspection Certificate',
        'Sanitary Permit',
        'Environmental Compliance Clearance',
        'Latest 2x2 picture',
        'Tax Order of Payment'
    );

    $review = array(
        'Incorrect/Outdated Information',
        'Insufficient Detail',
        'Incorrect Document',
        'Low Image Resolution',
        'Overexposure/Underexposure',
        'Misleading/Manipulated Visuals',
        'File Corruption',
        'Invalid File Extension'
    );

    $fileStatus = array(
        'Uploaded',
        'Pending',
        'Approved',
        'Denied'
    );

    if(count($requirements) !== 11){
        header("location: msme.php");
        exit;
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(checkPermit($businessID) === "None"){
    $updated = 0;
    $error = false;
    foreach ($requirements as $i => $requirement) {
        $select_status = validate($_POST['fileStatus_'.$i]);
        $select_review = validate(($_POST['review_'.$i]));

        if($select_status === "Denied"){
          if(empty($select_review)){
            $error = true;
          }
        }else{
            $select_review = null;
        }

        if($error === false){
            $sql = "UPDATE Requirement SET Status = ?, Review = ?, ReviewedBy = ? WHERE RequirementID = ?";

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("ssss", $param_status, $param_review, $param_ReviewdBy, $param_RequirementID);

                $param_status = $select_status;
                $param_review = $select_review;
                if($select_status === "Uploaded"){
                    $param_ReviewdBy = null;
                }else{
                    $param_ReviewdBy = $_SESSION['AdminID'];
                }
                $param_RequirementID = $requirement['RequirementID'];

                if($stmt->execute()){
                    $updated++;
                }else{
                    $updated--;
                }
                $stmt->close();
            }else{
                $updated--;
            }
        }
    }

    if($updated === 11){
        $modal_display = "";
        $modal_status = "success";
        $modal_title = "Requirements Review Updated";
        $modal_message = "All updated";
        $modal_button = '<a href="review.php">OK</a>';
    }else if($updated > 0 ){
        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "Requirements Review Updated";
        $modal_message = "Some review did not update <br>";
        $modal_message .= "Select Review message for denied requirements";
        $modal_button = '<a href="review.php">OK</a>';
    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Requirements Review Updated";
        $modal_message = "No review has been updated";
        $modal_button = '<a href="review.php">OK</a>';
    }

}else if(checkPermit($businessID) === "Issued"){

    $modal_display = "";
    $modal_status = "warning";
    $modal_title = "Owner Profile cannot be updated";
    $modal_message = "The permit has already been issued";
    $modal_button = '<a href="users.php">OK</a>';

}else{
    $modal_display = "";
    $modal_status = "error";
    $modal_title = "Business Permit Error";
    $modal_message = "Try again Later";
    $modal_button = '<a href="users.php">OK</a>';
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
    <script src="../../js/table.js" defer></script>
    <script src="../../js/modal.js" defer></script>
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
                    <p class="title">Business Profile</p>
                    <div class="logo"> 
                        <img src="<?= $logo_displayed ?>" alt="Business Logo">
                    </div>
                    <div class="text-center">
                        <p class="title"><?= $bus_name ?></p>
                        <p class="sentence"><?= $activity ?></p> 
                    </div>
                </subsection>
                <subsection>
                    <p class="sentence">Business Permit Status</p> 
                    <div class="info title" id="permit-status"><?= $permit_status ?></div>
                </subsection>
                <subsection>
                    <p class="sentence">Edit Business Profile</p> 
                    <a class="action edit" href="../management/edit_business.php?id=<?= $userID ?>"><img src="../../img/edit.svg" alt="Edit"></a>
                </subsection>
            </section>
            <section class="map-container">
                <subsection>
                        <p class="title">Location </p>
                        <p class="sentence"><?= $address_b.', '. $barangay_b ?></p> 
                        <map id="map"></map>
                        <p id="latitude" class="hidden"><?= $latitude ?></p>
                        <p id="longitude" class="hidden"><?= $longitude ?></p>
                </subsection>
            </section>
            <section>
                <subsection>
                    <p class="title text-center">Owner Profile</p>
                    <p class="sentence">Name</p>
                    <div class="info title"><?= $fname.' '. $mname.' '.$lname.' '. $suffix ?></div>
                    <p class="sentence">Gender</p>
                    <div class="info title"><?= $gender ?></div>
                    <p class="sentence">Contact Number</p>
                    <div class="info title"><?= $contact ?></div>
                    <p class="sentence">Address</p>
                    <div class="info title"><?= $address.', '. $barangay ?></div>
                    <p class="sentence">Edit Owner Profile</p>
                    <a class="action edit" href="../management/edit_owner.php?id=<?= $userID ?>"><img src="../../img/edit.svg" alt="Edit"></a>
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
                    <th>Status</th>
                    <th>Review</th>
                    <th>Actions</th>
                </tr>
                <?php 
        
        foreach($documents as $i => $documentName){
            if($documentName === "On the Place of Business"){
                echo '  <tr>
                <td>'.$documentName.'<img class="info" src="../../img/info.svg" alt="Info"></td>';
            }else{
                echo '  <tr>
                <td>'.$documentName.'</td>';
            }
            $found = false;
            foreach($requirements as $requirement){
                if($requirement['Name'] === $documentName){
            echo '  <td>
                        <select id="fileStatus_'.$i.'" name="fileStatus_'.$i.'" class="fileStatus">';
                        foreach($fileStatus as $status){
            echo "          <option value='$status' " . ($requirement['Status'] === $status ? "selected" : "") . ">$status</option>";
                        }
            echo '      </select>         
                    </td>
                    <td>
                        <select id="review_'.$i.'" name="review_'.$i.'" class="review">
                        <option value="" disabled selected>Select Review...</option>
                        ';
                        foreach($review as $message){
            echo "          <option value='$message' " . ($requirement['Review'] === $message ? "selected" : "") . ">$message</option>";
                        }
            echo '      </select>
                        <div class="error-msg">'.$errors["uploadReq_'.$i.'"].'</div>
                    </td>
                    <td class="table-actions">
                        <a class="view" target="_blank" href="../../user/Business/upload/'.$businessID.'/'.$requirement['FileName'].'"><img src="../../img/view.svg" alt="View"></a>
                    </td>
                ';
                $found = true;
                }
            }
            if(!$found){
                echo '  <td></td>
                        <td></td>
                        <td></td>
                ';
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