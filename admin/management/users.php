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

$sql_user = "SELECT User.UserID, Owner.OwnerID, Business.BusinessID, COUNT(Requirement.BusinessID) AS Uploaded, Permit.PermitID
FROM User
LEFT JOIN Owner ON User.UserID = Owner.UserID
LEFT JOIN Business ON Owner.OwnerID = Business.OwnerID
LEFT JOIN Requirement ON Business.BusinessID = Requirement.BusinessID
LEFT JOIN Permit ON Business.BusinessID = Permit.BusinessID
WHERE User.Role = 'Owner'
GROUP BY User.UserID, Owner.OwnerID, Business.BusinessID
ORDER BY User.UserID DESC;";

if ($result = $mysqli->query($sql_user)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if($row['OwnerID'] !== null){
                $ownerProfile = "Created";
            }else{
                $ownerProfile = "None";
            }

            if($row['BusinessID'] !== null){
                $businessProfile = "Created";
            }else{
                $businessProfile = "None";
            }
            if($row['Uploaded'] === 12){
                $uploaded = "Complete";
            }else if($row['Uploaded'] > 0){
                $uploaded = "Incomplete";
            }else{
                $uploaded = "None";
            }
            if($row['PermitID'] !== null){
                $permit = "Issued";
            }else{
                $permit = "None";
            }
            $owner = array(
                'UserID' => $row['UserID'],
                'Owner' => $ownerProfile,
                'Business' => $businessProfile,
                'Requirements' => $uploaded,
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
    <link rel="shortcut icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/script.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/table.js" defer></script>
    <title>Management</title>
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
                <li class="current"><a href="users.php">Management</a></li>
                <li><a href="../permit/msme.php">Permit</a></li>
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li class="current"><a href="users.php">List</a></li>
                <li><a href="edit_owner.php">Profile</a></li>
                <li><a href="edit_business.php">Business</a></li>
                <li><a href="documents.php">Documents</a></li>
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
                <li class="current"><a href="users.php">List</a></li>
                <li><a href="edit_owner.php">Profile</a></li>
                <li><a href="edit_business.php">Business</a></li>
                <li><a href="documents.php">Documents</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container height-auto">
            <p id="page" class="title text-center">User Management</p>
                <table id="users"> 
                    <tr>
                        <td colspan="6"> 
                            <div class="data">
                                <img src="../../img/add-user.svg" alt=""> Add User
                            </div>
                        </td>
                    </tr>  
                    <tr>
                        <th>User ID</th>
                        <th>Profile</th>
                        <th>Business</th>
                        <th>Documents</th>
                        <th>Permit</th>
                        <th>Action</th>
                    </tr>
                    <?php 
                    foreach ($owners as &$owner) {
        echo '  <tr class="user_info">  
                    <td>'.$owner['UserID'].'</td>

                    <td><div class="data">'.$owner['Owner'].'</div></td>

                    <td><div class="data">'.$owner['Business'].'</div></td>

                    <td><div class="data">'.$owner['Requirements'].'</div></td>

                    <td><div class="data">'.$owner['Permit'].'</div></td>

                    <td><div class="action delete"><img class="deleteUser" src="../../img/delete.svg" alt="Delete"></div></td>
                </tr>';
                }
                    ?>
                </table>
        </div>
    </main>
</body>
</html>