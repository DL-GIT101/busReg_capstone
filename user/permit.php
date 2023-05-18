<?php 
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
   
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');

    $file_inputs = count($_FILES);

    $requirements = array();
    $status = array();
    $error_count = 0;
    
    for ($i = 1; $i <= $file_inputs; $i++) {

        $errorMsg = 'errorMsg_' . $i;
        $targetDir = "upload/".$_SESSION['id']."/";

        $fileName = basename($_FILES['requirement_' . $i]['name']);
        $targetFilePath = $targetDir. $fileName;
        $fileSize = $_FILES['requirement_' . $i]['size'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $new_fileName = 'requirement_' . $i.'_'. uniqid() ."_". $fileName;
        $targetFilePath = $targetDir . $new_fileName;

        if(!empty($_FILES['requirement_' . $i]['name'])){
            if ($_FILES['requirement_' . $i]['error'] === UPLOAD_ERR_OK){
                if (in_array($fileType, $allowTypes)) {
                    if ($fileSize <= 2097152) {
                       if(move_uploaded_file($_FILES['requirement_' . $i]['tmp_name'], $targetFilePath)){
                            array_push($requirements,$new_fileName);
                            array_push($status,'Uploaded');
                        }else{
                            $$errorMsg  = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                            $error_count++;
                        }
                    } else {
                        $$errorMsg = 'File size should be 2MB or less.';
                        $error_count++;
                    }
                } else {
                    $$errorMsg = 'Only JPG, JPEG, PNG and PDF files are allowed.';
                    $error_count++;
                } 
            } else {
                $$errorMsg = 'Error uploading file: ' . $_FILES['requirement_' . $i]['error'];
                $error_count++;
            }
        } else {
            array_push($requirements,null);
            array_push($status,'-');
        }
    }

    if($error_count === 0){
        //insert data  dont forget to serialized the array and bind with s
    }else{
        echo 'has error';
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>New Permit</title>
</head>
<body>

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
                <th>Uploaded File</th>
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
                                <td>'.$fileName.'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
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