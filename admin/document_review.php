<?php
session_start();
require_once "../php/connection.php";

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: msme_management.php");
}
$modal = "hidden";


    $sql = "SELECT * FROM new_documents WHERE user_id = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        
        $stmt->bind_param("s",$param_id);
        
        $param_id = $user_id;
       
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
            }else{
                
            }
        }else {
            echo "error retrieving data";
        }
    $stmt->close();
    }
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $length = 12;

        $status = array();
        $denied_msg = array();

        for($i = 1; $i <= $length; $i++){
            $errorMsg = 'errorMsg_' . $i;

           $status_review = validate($_POST['status_'.$i]);
           if(!empty($status_review)){
            array_push($status, $status_review);
           }else{
            array_push($status, null);
           }

           array_push($denied_msg, null);

        }

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
                $button = '<a href="document_review.php">OK</a>';
            }else{
                $modal = "";
                $status_modal = "fail";
                $title = "Updating Error";
                $message = "Try again later";
                $button = '<a href="msme_manangement.php">OK</a>';
            }
            $stmt->close();
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
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js" defer></script>
    <script src="../js/form.js" defer></script>
    <script src="../js/modal.js" defer></script>
    <script src="../js/file_modal.js" defer></script>
    <script src="../js/admin_td_click.js" defer></script>
    <title>Denied Documents</title>
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
                <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../php/logout.php">Logout</a>
        </div>
    </nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li ><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li class="current"><img src="../img/register.png" alt=""><a href="msme_management.php">MSME Management</a></li>
            <li><img src="../img/list.png" alt=""><a href="">MSME Permit</a></li>
            
        </ul>
    </nav>

    <main class="flex-grow-1 flex-wrap content-center">
    <div class="actions space-between">
            <p id="page" class="title">Review Documents</p>
            <p class="sentence"> User ID : <?= $user_id ?></p>
            <div class="buttons">
                <a href="msme_documents.php" class="back">Back</a>
            </div>
        </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
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
                                    <div class="error_msg">'.$denied_err.'</div>
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