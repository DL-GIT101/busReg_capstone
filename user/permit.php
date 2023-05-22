<?php 
    session_start();
    require_once "../php/config.php";
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../login.php");
        exit;
    }
    if(hasProfile($mysqli,$_SESSION['id']) === 0){
        header("location: profile.php");
        exit;
    }
    $success = $failed = "hidden";
    $sql = "SELECT * FROM new_permit WHERE user_id = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        
        $stmt->bind_param("s",$param_id);
        
        $param_id = validate($_SESSION['id']);
       
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
           
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_requirements_fetch = $row["requirements"];
                $serialized_status_fetch = $row["status"];
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);
                $update = 1;
                
            }else {
                $update = 0;
            }
        }else {
            echo "error retrieving data";
        }
    }
    $stmt->close();
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');

    $file_inputs = count($_FILES);

    $requirements = array();
    $status = array();
    
    for ($i = 1; $i <= $file_inputs; $i++) {

        $errorMsg = 'errorMsg_' . $i;
        $targetDir = "upload/".$_SESSION['id']."/";

        $fileName = basename($_FILES['requirement_' . $i]['name']);
        $targetFilePath = $targetDir. $fileName;
        $fileSize = $_FILES['requirement_' . $i]['size'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $new_fileName = 'requirement_' . $i.'_'. uniqid() ."_". $fileName;
        $targetFilePath = $targetDir . $new_fileName;
        $uploaded;
        if(!empty($_FILES['requirement_' . $i]['name'])){
            if(empty($requirements_fetch[$i-1])){
                if ($_FILES['requirement_' . $i]['error'] === UPLOAD_ERR_OK){
                    if (in_array($fileType, $allowTypes)) {
                        if ($fileSize <= 2097152) {
                        if(move_uploaded_file($_FILES['requirement_' . $i]['tmp_name'], $targetFilePath)){
                                array_push($requirements,$new_fileName);
                                array_push($status,'Uploaded');
                                $uploaded = 1;
                            }else{
                                $$errorMsg  = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                                array_push($requirements,null);
                                array_push($status,null);
                            }
                        } else {
                            $$errorMsg = 'File size should be 2MB or less.';
                            array_push($requirements,null);
                            array_push($status,null);
                        }
                    } else {
                        $$errorMsg = 'Only JPG, JPEG, PNG and PDF files are allowed.';
                        array_push($requirements,null);
                        array_push($status,null);
                    } 
                } else {
                    $$errorMsg = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                    array_push($requirements,null);
                    array_push($status,null);
                }
            }else{
                $$errorMsg = 'Delete first the uploaded file';
                array_push($requirements,$requirements_fetch[$i-1]);
                array_push($status,$status_fetch[$i-1]);
            }
        } else {
            if(!empty($requirements_fetch[$i-1])){
                array_push($requirements,$requirements_fetch[$i-1]);
                array_push($status,$status_fetch[$i-1]);
            }else{
                array_push($requirements,null);
                array_push($status,null);
            }
        }
    }
      
        $serialized_requirements = serialize($requirements);
        $serialized_status = serialize($status);

        if($update === 1){
            $sql = "UPDATE new_permit SET requirements = ?, status = ? WHERE user_id = ?";
        }else{
            $sql = "INSERT INTO new_permit (user_id, requirements, status) VALUES (?, ?, ?)";
        }
        
        if($stmt = $mysqli->prepare($sql)){

            if($update === 1){
                $stmt->bind_param('sss',$param_req, $param_Stat, $param_id);
            }else{
                $stmt->bind_param('sss', $param_id, $param_req, $param_Stat);
            }

            $param_id = validate($_SESSION['id']);
            $param_req = $serialized_requirements;
            $param_Stat = $serialized_status;

            if($stmt->execute()){
               if($uploaded === 1){
                $success = "";
               }else{
                $failed = "";
               }
            }else{
                $failed = "";
            }
            $stmt->close();
        }

   
    $mysqli->close();
}

function hasProfile($mysqli,$param_id){
    $sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = validate($_SESSION['id']);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                return 1;
            }else {
                return 0;
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }

    }

    $stmt->close();
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
    <script src="../js/form.js" defer></script>
    <script src="../js/modal.js" defer></script>
    <title>New Permit</title>
</head>
<body>
    <!-- upload success -->
<div class="modal <?= $success ?>">
    <div class="modal-content success">
            <p class="title">Upload Successful</p>
            <p class="sentence">All Uploaded files will be reviewed</p>  
            <a href="permit.php">OK</a>
    </div>
</div> 
<!-- upload failed -->
<div class="modal <?= $failed ?>">
    <div class="modal-content error">
        <p class="title">Upload Error</p>
        <p class="sentence">Check the error or Try again later.</p> 
        <button class="modal_close_btn">CLOSE</button>
    </div> 
</div>
<!-- file delete -->
<div id="notif_modal" class="modal hidden">
        <div class="modal-content error">
            <div class="row space-between"><p class="title">Delete File</p>
            <span class="modal_close_btn">&times;</span></div>
            <p class="sentence">Are you sure you want to delete this file? This action cannot be undone.</p> 
            <a id="file_link" href="">Delete</a>
        </div>
</div>

<nav id="navbar">
       <div id="logo">
        <a href="../index.php">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p>  
        </a>
       </div>

       <div id="user">
            <a href="welcome.php">Dashboard</a>
            <a href="../php/logout.php">Logout</a>
       </div>
</nav>

<div id="content">
    <div class="container"> 
        
    <div class="intro">
        <p class="title">New Business</p>
        <p class="sentence">Please upload the photos of the following requirements</p>
    </div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <table>
            <tr>
                <th>Requirement</th>
                <th>View</th>
                <th>Delete</th>
                <th>Status</th>
                <th>File Upload</th>
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
                            <td></td>
                            ';
                    }else{
                        echo    '<td><a class="view_file" target="_blank" href="upload/'.$_SESSION['id'].'/'.$requirements_fetch[$count-1].'">View</a></td>
                        <td><button value="'.$requirements_fetch[$count-1].'" type="button" class="delete_file">Delete</td>
                        <td>'.$status_fetch[$count-1].'</td>';
                    }
                        echo '<td>
                                <input type="file" id="requirement_'.$count.'" name="requirement_'.$count.'">
                                <div class="error">'.${$errorMsg}.'</div>
                            </td>
                        </tr>';
                $count++;
                }
            ?>
            
        </table>
        <input type="submit" value="Upload">
    </form>       
    </div>
</div>

<!--
    Create a  new style for images upload as form will be in table
    1. Barangay Clearance for business
    2. DTI Certificate of Registration
    3. On the Place of Business 	 
        - Building/Occupancy Certificate, if owned	
        - Lease of Contract, if rented	 
        - Notice of Award/Award Sheet, if inside a Mall
        - Homeowner’s/Neighborhood Certification of No Objection, if inside a subdivision or housing facility
    4. Community Tax Certificate
    5. Certificate of Zoning Compliance
    6. Business Inspection Clearance
    7. Valid Fire Safety Inspection Certificate/Official Receipt
    8. Sanitary Permit
    9. Environmental Compliance Clearance
    10. Latest 2×2 picture
    11. Tax Order of Payment
    12. Tax Order of Payment Official Receipt
 -->
</body>
</html>