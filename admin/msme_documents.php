<?php
session_start();
require_once "../php/connection.php";

if (isset($_GET['id'])) {

$user_id = urldecode($_GET['id']);
$document = "";
$none = "hidden";
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
                $requirements_fetch = unserialize($serialized_requirements_fetch);
                $status_fetch = unserialize($serialized_status_fetch);                
            }else{
                $document = "hidden";
                $none = "";
            }
        }else {
            echo "error retrieving data";
        }
    }
    $stmt2->close();

    $mysqli->close();
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
    <title>MSME Documents</title>
</head>
<body>
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

    <main class="flex-grow-1">
    <p class="title <?= $none ?>">The user has not uploaded any documents yet.</p>
        <div class="column_container <?= $document ?>">
            <div class="text-center">
                <p class="title">New Business Permit</p>
                <p class="sentence">Please check the uploaded photo/file of the following requirements</p>
            </div>
            <table>
                <tr>
                    <th>Requirement</th>
                    <th>View</th>
                    <th>Delete</th>
                    <th>Status</th>
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
                            echo '  <td></td>
                                    <td></td>
                                    <td></td>
                                ';
                        }else{
                            echo    '<td><a class="view" target="_blank" href="upload/'.$_SESSION['id'].'/'.$requirements_fetch[$count-1].'">View</a></td>
                                    <td><button value="'.$requirements_fetch[$count-1].'" type="button" class="delete">Delete</td>
                                    <td><div class="info '.strtolower($status_fetch[$count-1]) .'">'.$status_fetch[$count-1].'</div></td>';
                        }
                                echo '</tr>';
                    $count++;
                    }
                ?>
                
            </table>
        </div>     
    </main>
</div>

</body>
</html>