<?php 
session_start();
require_once "../php/connection.php";

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: ../login.php");
        exit;
    }

    if(hasProfile($mysqli,$_SESSION['id']) === 0){
        header("location: profile.php");
        exit;
    }

    $modal = "hidden";
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
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);
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
    
    $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');

    $file_inputs_count = count($_FILES);

    $requirements = array();
    $status = array();
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
                            }else{
                                $$errorMsg  = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                                $error_count++;
                                array_push($requirements,null);
                                array_push($status,null);
                            }
                        } else {
                            $$errorMsg = 'File size should be 2MB or less.';
                            $error_count++;
                            array_push($requirements,null);
                            array_push($status,null);
                        }
                    } else {
                        $$errorMsg = 'Only JPG, JPEG, PNG and PDF files are allowed.';
                        $error_count++;
                        array_push($requirements,null);
                        array_push($status,null);
                    } 
                } else {
                    $$errorMsg = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                    $error_count++;
                    array_push($requirements,null);
                    array_push($status,null);
                }
            }else{
                $$errorMsg = 'Delete first the uploaded file';
                $error_count++;
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
                    $modal = "";
                    $status_modal = "warning";
                    $title = "Refresh to see Uploaded Files";
                    $message = "Some Uploaded files are not uploaded";
                    $button = '<button class="close">OK</button>';
                } else {
                    $modal = "";
                    $status_modal = "success";
                    $title = "Upload Successful";
                    $message = "All Uploaded files will be reviewed";
                    $button = '<a href="permit.php">OK</a>';
                }
            }else{
                $modal = "";
                $status_modal = "fail";
                $title = "Upload Error";
                $message = "Try again later";
                $button = '<a href="dashboard.php">OK</a>';
            }
        $stmt->close();
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
    <script src="../js/script.js" defer></script>
    <script src="../js/form.js" defer></script>
    <script src="../js/modal.js" defer></script>
    <script src="../js/file_modal.js" defer></script>
    <title>New Permit</title>
</head>
<body>

<modal class="<?= $modal ?>">
        <div class="content <?= $status_modal ?>">
            <p class="title"><?= $title ?></p>
            <p class="sentence"><?= $message ?></p>
            <?= $button ?>
        </div>
</modal>

<modal id="file_del" class="hidden">
        <div class="content fail">
            <p class="title">Delete File</p>
            <p class="sentence">Are you sure you want to delete this file? This action cannot be undone</p>
            <div id="btn_grp" class="flex align-self-center">
                <a href="" id="file_link">Delete</a>
                <button>Cancel</button>
            </div>
                
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
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <div id="account">
             <a href="dashboard.php">Dashboard</a>
             <a href="../php/logout.php">Logout</a>
        </div>
</nav>

<main>
        
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <div class="text-center">
        <p class="title">New Business</p>
        <p class="sentence">Please upload the file of the following requirements</p>
    </div>
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
                    'On the Place of Business <img id="info" src="../img/info.png" alt="">',
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
                        echo '  <td></td>
                                <td></td>
                                <td></td>
                            ';
                    }else{
                        echo    '<td><a class="view" target="_blank" href="upload/'.$_SESSION['id'].'/'.$requirements_fetch[$count-1].'">View</a></td>
                                <td><button value="'.$requirements_fetch[$count-1].'" type="button" class="delete">Delete</button></td>
                                <td><div class="info '.strtolower($status_fetch[$count-1]) .'">'.$status_fetch[$count-1].'</div></td>';
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
</main>
</body>
</html>