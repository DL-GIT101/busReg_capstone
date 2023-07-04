<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['message'])){
    $modal_get = urldecode($_GET['message']);
    echo $modal_get;
}

$modal_display = "hidden";


$owners = array(); 

$sql_user = "SELECT Business.BusinessID, Business.Name, Business.Activity, 
COUNT(CASE WHEN Requirement.Status = 'Approved' THEN 1 ELSE NULL END) AS Approved, 
Permit.PermitID
FROM Business
LEFT JOIN Requirement ON Business.BusinessID = Requirement.BusinessID
LEFT JOIN Permit ON Business.BusinessID = Permit.BusinessID
GROUP BY Business.BusinessID
HAVING COUNT(Requirement.BusinessID) = 11
ORDER BY Business.BusinessID DESC;";

if ($result = $mysqli->query($sql_user)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if($row['Approved'] == 11){
                $approved = "Complete";
            }else if($row['Uploaded'] > 0){
                $approved = "Incomplete";
            }else{
                $approved = "None";
            }
            if($row['PermitID'] !== null){
                $permit = "Issued";
            }else{
                $permit = "None";
            }
            $owner = array(
                'BusinessID' => $row['BusinessID'],
                'Name' => $row['Name'],
                'Activity' => $row['Activity'],
                'Approved' => $approved,
                'Permit' => $permit
            );
        $owners[] = $owner;
        }
    } else {
        echo "No users found with the role of Owner.";
    }
    $result->free();
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
    <script src="../../js/script.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/table.js" defer></script>
    <title>MSME List</title>
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
                <li class="current"><a href="msme.php">List</a></li>
                <li><a href="review.php">Review</a></li>
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
                <li class="current"><a href="msme.php">List</a></li>
                <li><a href="review.php">Review</a></li>
                <li><a href="approve.php">Approve</a></li>
            </ul>
        </div>
    </nav>

    <main class="flex-grow-1">
        <div class="column-container height-auto"> 
            <p id="page" class="title text-center">MSME List</p>
                <table id="msme">   
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Activity</th>
                        <th>Approved</th>
                        <th>Permit</th>
                    </tr>
                    <?php 
                    foreach ($owners as &$owner) {
                        echo '  <tr class="msme_details">  
                                    <td>'.$owner['BusinessID'].'</td>
                                    <td>'.$owner['Name'].'</td>
                                    <td>'.$owner['Activity'].'</td>
                                    <td><div class="data">'.$owner['Approved'].'</div></td>
                                    <td><div class="data">'.$owner['Permit'].'</div></td>
                                </tr>';
                }
                    ?>
                </table>
        </div>
    </main>

</body>
</html>