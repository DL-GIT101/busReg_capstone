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

if($_SESSION["AdminRole"] !== "Superadmin"){
    header("location: ../dashboard.php");
    exit;
}

$modal_display = "hidden";

$admins = array(); 

$sql_user = "SELECT User.UserID, Admin.AdminID, Admin.Role FROM User 
LEFT JOIN Admin ON User.UserID = Admin.UserID
WHERE User.Role = 'Admin'
ORDER BY User.UserID ASC";

if ($result = $mysqli->query($sql_user)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if($row['AdminID'] !== null){
                $admin = "Created";
            }else{
                $admin = "None";
            }
            if($row['AdminID'] !== null){
                $role = $row['Role'];
            }else{
                $role = "None";
            }
            $admin = array(
                'UserID' => $row['UserID'],
                'Profile' => $admin,
                'Role' => $role
            );
        $admins[] = $admin;
        }
    } else {
        echo "No users found with the role of Admin.";
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
    <title>Admin Management</title>
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
                <li class="current"><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../management/users.php">Management</a></li>
                <li><a href="../permit/msme.php">Permit</a></li>
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li class="current"><a href="admins.php">Admin List</a></li>
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
                <li class="current"><a href="admins.php">Admin List</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container height-auto">
            <p id="page" class="title text-center">Admin Management</p>
                <table id="admin"> 
                    <tr>
                        <td colspan="5"> 
                            <div class="data" id="addUser">
                                <img src="../../img/add-user.svg" alt=""> Add Admin
                            </div>
                        </td>
                    </tr>  
                    <tr>
                        <th>UserID</th>
                        <th>Profile</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    <?php 
                    foreach ($admins as &$admin) {
            echo '  <tr class="user_info">  

                        <td id="user_id">'.$admin['UserID'].'</td>

                        <td><div  class="data">'.$admin['Profile'].'</div></td>
                        <td><div class="data">'.$admin['Role'].'</div></td>

                        <td class="actions">
                            <div class="action delete">
                                <img class="deleteUser" src="../../img/delete.svg" alt="Delete">
                            </div>
                            <div class="action edit">
                                <img class="editUser" src="../../img/edit.svg" alt="Delete">
                            </div>
                        </td>

                    </tr>';
                }
                    ?>
                </table>
        </div>
    </main>
</body>
</html>