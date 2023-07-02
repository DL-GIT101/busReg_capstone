<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "Owner"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['message'])){
    $modal_get = urldecode($_GET['message']);
    echo $modal_get;
}
    
    $modal_display = "hidden";
    $sql = "SELECT * FROM Requirement WHERE BusinessID = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);
        
        $param_id = validate($_SESSION['BusinessID']);
       
        if($stmt->execute()){
            $result = $stmt->get_result();
            $requirements = array();
            $uploadRequirementsName = array();

            while ($row = $result->fetch_assoc()) {

            array_push($uploadRequirementsName,$row['Name']);
                
               $requirement = array(
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
            $modal_button = '<a href="../dashboard.php">OK</a>';
        }
    $stmt->close();
    }

    $documents = array(
        'Barangay Clearance for business',
        'DTI Certificate of Registration',
        'On the Place of Business <img class="info" src="../../img/info.svg" alt="Info">',
        'Community Tax Certificate',
        'Certificate of Zoning Compliance',
        'Business Inspection Clearance',
        'Fire Safety Inspection Certificate',
        'Sanitary Permit',
        'Environmental Compliance Clearance',
        'Latest 2x2 picture',
        'Tax Order of Payment'
    );
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(hasBusinessProfile($_SESSION['OwnerID']) === true){
    if(checkPermit($_SESSION['BusinessID']) === "None"){

    $errors = [];

    $uploadReqName = validate($_POST["uploadReqName"]);

    $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');
    $targetDir = "upload/".$_SESSION['BusinessID']."/";
    $fileName = basename($_FILES['uploadReq']['name']);
    $targetFilePath = $targetDir. $fileName;
    $fileSize = $_FILES['uploadReq']['size'];
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $new_fileName = 'REQUIREMENT-' . uniqid().'.'.$fileType;
    $targetFilePath = $targetDir . $new_fileName;
        
if(!empty($_FILES['uploadReq']['name'])){
    if(!empty($uploadReqName)){
        if(!in_array($uploadReqName, $uploadRequirementsName)){
            if (in_array($fileType, $allowTypes)) {
                if ($fileSize <= 2097152) {
                    if(move_uploaded_file($_FILES['uploadReq']['tmp_name'], $targetFilePath)){

            $sql_id = "SELECT RequirementID as maxID FROM Requirement ORDER BY RequirementID DESC LIMIT 1";

            if($stmt_id = $mysqli->prepare($sql_id)) {

                if($stmt_id->execute()){  

                    $stmt_id->bind_result($maxID);

                    if($stmt_id->fetch()) {
                        $lastID = $maxID;
                    }
                }
            $stmt_id->close();
            }
            $currentYear = date('Y');
            if($lastID !== null) {
                $year = substr($lastID, 2, 4);
                $countDash = substr($lastID, 7);
                $count = str_replace("-","",$countDash);

                if($year === $currentYear) {
                    $count += 1;
                }else {
                    $count = 0;
                }
            }else {
                $count = 0;
            }

            $count = str_pad($count, 6, '0', STR_PAD_LEFT);
            $countDash = substr_replace($count, "-", 3, 0);
            $requirementID = "R-" . $currentYear . "-" . $countDash;

            $sql = "INSERT INTO Requirement (RequirementID, BusinessID, Name, FileName, Status) VALUES (?, ?, ?, ?, ?)";
            if($stmt = $mysqli->prepare($sql)){

                $stmt->bind_param("sssss", $param_RequirementID,$param_BusinessID, $param_Name, $param_FileName, $param_Status);

                $param_RequirementID = $requirementID;
                $param_BusinessID = $_SESSION['BusinessID'];
                $param_Name = $uploadReqName;
                $param_FileName = $new_fileName;
                $param_Status = "Uploaded";

                if($stmt->execute()){
                    $modal_display = "";
                    $modal_status = "success";
                    $modal_title = "Requirement Upload Successfully";
                    $modal_message = "The file has been uploaded";
                    $modal_button = '<a href="upload_requirement.php">OK</a>';
                }else{
                    $modal_display = "";
                    $modal_status = "error";
                    $modal_title = "Something went wrong";
                    $modal_message = "Try again later";
                    $modal_button = '<a href="../dashboard.php">OK</a>';
                }
                $stmt->close();
            }else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Something went wrong";
                $modal_message = "Try again later";
                $modal_button = '<a href="../dashboard.php">OK</a>';
            }
                    }else{
                        $errors['uploadReq']  = 'Error uploading file';
                    }
                } else {
                    $errors['uploadReq'] = 'File size should be 2MB or less.';
                }
            } else {
                $errors['uploadReq'] = 'Only JPG, JPEG, PNG and PDF files are allowed.';;
            } 
        } else {
            $errors['uploadReq'] = 'Delete first the uploaded file';
        }
    }else{
        $errors['uploadReqName'] = 'Select Requirement to Upload ';
    }
}else{
    $errors['uploadReq'] = "File upload is empty";
}

    }else if(checkPermit($_SESSION['BusinessID']) === "Issued"){
        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "Requirements cannot be updated";
        $modal_message = "The permit has already been issued";
        $modal_button = '<button class="close">OK</button>';
    }else {
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Something went wrong";
        $modal_message = "Try again later";
        $modal_button = '<a href="../dashboard.php">OK</a>';
    }     
    }else if(hasBusinessProfile($_SESSION['OwnerID']) === false){
        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "No Business Profile";
        $modal_message = "No Business profile found<br>
                        Cannot upload requirements without a profile";
        $modal_button = ' <a href="edit_profile.php">Create</a>';
    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Something went wrong";
        $modal_message = "Try again later";
        $modal_button = '<a href="../dashboard.php">OK</a>';
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
    <link rel="shortcut icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/script.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <script src="../../js/table.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <title>Upload Documents</title>
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
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

<main>
    <div class="column-container">
        <div class="text-center">
            <p class="title">New Business</p>
            <p class="sentence">Please upload the file of the following requirements</p>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <table>
                <tr>
                <td colspan="1">
                    <select name="uploadReqName" id="uploadReqName">
                    <option value="" disabled selected>Select Requirement...</option>
        <?php 
            foreach($documents as $documentName){
                echo "<option value='$documentName' " . ($uploadReqName === $documentName ? "selected" : "") . ">$documentName</option>";
            }   
        ?>
                    </select>
                    <div class="error-msg"><?= $errors["uploadReqName"]; ?></div>
                </td>
                <td colspan="2">
                    <input type="file" name="uploadReq" id="uploadReq">
                    <div class="error-msg"><?= $errors["uploadReq"]; ?></div>
                </td>
                </tr>
                <tr>
                    <td class="uploadBtn" colspan="3">
                        <input type="submit" value="Upload">
                        
                    </td>
                </tr>
                <tr>
                    <th>Requirement</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php 
        
        foreach($documents as $documentName){
            echo '  <tr>
                        <td>'.$documentName.'</td>';
            $found = false;
            foreach($requirements as $requirement){
                if($requirement['Name'] === $documentName){
                    echo '  <td>
                                <div class="status">'.$requirement['Status'].'</div>
                                <div class="message">'.$requirement['Review'].'</div>
                            </td>
                            <td class="table-actions">
                                <a class="view" target="_blank" href="upload/'.$_SESSION['BusinessID'].'/'.$requirement['FileName'].'"><img src="../../img/view.svg" alt="View"></a>
            
                                <img class="delete" src="../../img/delete.svg" alt="Delete">
                            </td>
                        ';
                $found = true;
                }
            }
            if(!$found){
                echo '  <td></td>
                        <td></td>
                ';
            }
            echo '</tr>';
        }
    ?>
        </table>  
        </form>
    </div>    
</main>
</body>
</html>