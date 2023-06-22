<?php 
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";
require_once "../php/checkPermit.php";

if(checkRole($_SESSION["role"]) !== "user"){
    header("location: ../index.php");
    exit;
}

if(hasProfile($mysqli,$_SESSION['id']) === 0){
    header("location: profile.php");
    exit;
}

if(isset($_GET['message'])){
    $modal_get = urldecode($_GET['message']);
    echo $modal_get;
}
    
    $modal_display = "hidden";
    $sql = "SELECT * FROM new_documents WHERE user_id = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);
        
        $param_id = validate($_SESSION['id']);
       
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_requirements_fetch = $row["requirements"];
                $serialized_status_fetch = $row["status"];
                $serialized_message_fetch = $row["message"];
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);
                $message_fetch = unserialize($serialized_message_fetch);
                $update = 1;
                
            }else {
                $update = 0;
            }
        }else {
            echo "error retrieving data";
        }
    $stmt->close();
    }
    
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(checkPermit($_SESSION['id']) !== "Approved"){
    $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');

    $file_inputs_count = count($_FILES);

    $requirements = array();
    $status = array();
    $denied_msg = array();

    $error_count = 0;

    for ($i = 1; $i <= $file_inputs_count; $i++) {
        $errorMsg = 'errorMsg_' . $i;

        $targetDir = "upload/".$_SESSION['id']."/";
        $fileName = basename($_FILES['requirement_' . $i]['name']);
        $targetFilePath = $targetDir. $fileName;
        $fileSize = $_FILES['requirement_' . $i]['size'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $new_fileName = 'requirement_' . $i.'.'.$fileType;
        $targetFilePath = $targetDir . $new_fileName;
        
        if(!empty($_FILES['requirement_' . $i]['name'])){
            if(empty($requirements_fetch[$i-1])){
                if ($_FILES['requirement_' . $i]['error'] === UPLOAD_ERR_OK){
                    if (in_array($fileType, $allowTypes)) {
                        if ($fileSize <= 2097152) {
                            if(move_uploaded_file($_FILES['requirement_' . $i]['tmp_name'], $targetFilePath)){
                                array_push($requirements,$new_fileName);
                                array_push($status,'Uploaded');  
                                array_push($denied_msg,null);  
                            }else{
                                $$errorMsg  = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                                $error_count++;
                                pushNullValues($requirements, $status, $denied_msg);
                            }
                        } else {
                            $$errorMsg = 'File size should be 2MB or less.';
                            $error_count++;
                            pushNullValues($requirements, $status, $denied_msg);
                        }
                    } else {
                        $$errorMsg = 'Only JPG, JPEG, PNG and PDF files are allowed.';
                        $error_count++;
                        pushNullValues($requirements, $status, $denied_msg);
                    } 
                } else {
                    $$errorMsg = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                    $error_count++;
                    pushNullValues($requirements, $status, $denied_msg);
                }
            }else{
                $$errorMsg = 'Delete first the uploaded file';
                $error_count++;
                array_push($requirements,$requirements_fetch[$i-1]);
                array_push($status,$status_fetch[$i-1]);
                array_push($denied_msg,$message_fetch[$i-1]);
            }
        } else {
            if(!empty($requirements_fetch[$i-1])){
                array_push($requirements,$requirements_fetch[$i-1]);
                array_push($status,$status_fetch[$i-1]);
                array_push($denied_msg,$message_fetch[$i-1]);
            }else{
                pushNullValues($requirements, $status, $denied_msg);
            }
        }
    }
      
        $serialized_requirements = serialize($requirements);
        $serialized_status = serialize($status);

        if($update === 1){
            $sql = "UPDATE new_documents SET requirements = ?, status = ? WHERE user_id = ?";
        }else{
            $sql = "INSERT INTO new_documents (user_id, requirements, status) VALUES (?, ?, ?)";
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

                if ($error_count > 0) {
                    $modal_display = "";
                    $modal_status = "warning";
                    $modal_title = "Refresh to see Uploaded Files";
                    $modal_message = "Some Uploaded files are not uploaded";
                    $modal_button = '<button class="close">OK</button>';
                } else {
                    $modal_display = "";
                    $modal_status = "success";
                    $modal_title = "Upload Successful";
                    $modal_message = "All Uploaded files will be reviewed";
                    $modal_button = '<a href="documents.php">OK</a>';
                }
            }else{
                $modal_display = "";
                $modal_status = "fail";
                $modal_title = "Upload Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="dashboard.php">OK</a>';
            }
        $stmt->close();
        }
    }else{
        $modal_display = "";
        $modal_status = "success";
        $modal_title = "Documents cannot be updated";
        $modal_message = "The permit has already been approved";
        $modal_button = '<a href="documents.php">OK</a>';
    }     
}
$mysqli->close();

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
    $stmt->close();
    }
}

function pushNullValues(&$array1, &$array2, &$array3) {
    array_push($array1, null);
    array_push($array2, null);
    array_push($array3, null);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <script src="../js/form.js" defer></script>
    <script src="../js/modal.js" defer></script>
    <script src="../js/table.js" defer></script>
    <title>New Permit</title>
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
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

<main>
    <div class="column-container height-auto">
        <div class="text-center">
            <p class="title">New Business</p>
            <p class="sentence">Please upload the file of the following requirements</p>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <table>
                <tr>
                    <th>Requirement</th>
                    <th>Actions</th>
                    <th>Status</th>
                    <th>File Upload</th>
                </tr>
                <?php 
                    $requirements_names = array(
                        'Barangay Clearance for business',
                        'DTI Certificate of Registration',
                        'On the Place of Business <img class="info" src="../img/info.svg" alt="Info">',
                        'Community Tax Certificate',
                        'Certificate of Zoning Compliance',
                        'Business Inspection Clearance',
                        'Fire Safety Inspection Certificate',
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
                            echo '  <td></td>
                                    <td></td>
                                   ';
                        }else{
                            echo    '<td class="table-actions">

                                    <a class="view" target="_blank" href="upload/'.$_SESSION['id'].'/'.$requirements_fetch[$count-1].'"><img src="../img/view.svg" alt="View"></a>

                                    <img class="delete" src="../img/delete.svg" alt="Delete">
                            </td>

                                    <td>
                                        <div class="status">'.$status_fetch[$count-1].'</div>
                                        <div class="message">'.$message_fetch[$count-1].'</div>
                                    </td>';
                        }
                                echo '<td>
                                        <input type="file" id="requirement_'.$count.'" name="requirement_'.$count.'">
                                        <div class="error_msg">'.${$errorMsg}.'</div>
                                    </td>
                                </tr>';
                    $count++;
                    }
                ?>
                
            </table>
            <input type="submit" value="Upload">
        </form>   
    </div>    
</main>
</body>
</html>