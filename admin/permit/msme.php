<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['message'])){
    $modal_get = urldecode($_GET['message']);
    echo $modal_get;
}

$modal_display = "hidden";

$all_business = array();

$user_sql = "SELECT users.id FROM users
             INNER JOIN user_profile ON users.id = user_profile.user_id
             WHERE users.id <> ?
             ORDER BY users.id DESC";
    if($user_stmt = $mysqli->prepare($user_sql)){
        $user_stmt->bind_param("s", $adminID);
        $adminID = validate($_SESSION['id']);
        if($user_stmt->execute()) {
            $user_stmt->bind_result($id);

            while($user_stmt->fetch()){
                $row = array(
                    'id' => $id,
                );             

            $all_business[] = $row;

            }
            $user_stmt->close(); 
        }
        
    }

    foreach ($all_business as &$business) {
        $profile_sql = "SELECT * FROM user_profile WHERE user_id = ? ORDER BY user_id DESC";
                if ($profile_stmt = $mysqli->prepare($profile_sql)) {
                    $profile_stmt->bind_param("s", $current_id);
                    $current_id = $business['id'];
                    if ($profile_stmt->execute()) {
                        $result = $profile_stmt->get_result();
                        if ($result->num_rows === 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);

                            $name = $row["business_name"];
                            $business['name'] = $name;
                            $activity = $row["activity"];
                            $business['activity'] = $activity;
                        }
                    } else {
                        echo "Error retrieving data";
                    }
                    $profile_stmt->close();
                }   
        
    } 

    foreach ($all_business as &$business) {
        $permit_sql = "SELECT * FROM permit WHERE user_id = ? ORDER BY user_id DESC";
                if ($permit_stmt = $mysqli->prepare($permit_sql)) {
                    $permit_stmt->bind_param("s", $current_id);
                    $current_id = $business['id'];
                    if ($permit_stmt->execute()) {
                        $result = $permit_stmt->get_result();
                        if ($result->num_rows === 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);
                            $permit = $row["status"];
                            $business['permit'] = $permit;
                        } else {
                            $business['permit'] = "None";
                        }
                    } else {
                        echo "Error retrieving data";
                    }
                    $permit_stmt->close();
                }   
        
    } 
   
   foreach ($all_business as &$business) {
        $document_sql = "SELECT * FROM new_documents WHERE user_id = ? ORDER BY user_id DESC";
                if ($document_stmt = $mysqli->prepare($document_sql)) {
                    $document_stmt->bind_param("s", $current_id);
                    $current_id = $business['id'];
                    if ($document_stmt->execute()) {
                        $result = $document_stmt->get_result();
                        if ($result->num_rows === 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);
                            
                            $serializedRequirements = $row["requirements"];
                            $requirements = unserialize($serializedRequirements);
                            
                            $totalRequirements = count($requirements);
                            $completedRequirements = count(array_filter($requirements, function ($value) {
                                return !is_null($value);
                            }));

                            $business['documents'] = $completedRequirements . '/' . $totalRequirements;
                        } else {
                            $business['documents'] = "0/11";
                        }
                    } else {
                        echo "Error retrieving data";
                    }
                    $document_stmt->close();
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
                        <th>Documents</th>
                        <th>Permit</th>
                        <th>Review</th>
                    </tr>
                    <?php 
                    foreach ($all_business as &$business) {
                        echo '  <tr class="msme_details">  
                                    <td>'.$business['id'].'</td>
                                    <td>'.$business['name'].'</td>
                                    <td>'.$business['activity'].'</td>
                                    <td><div class="data">'.$business['documents'].'</div></td>
                                    <td><div class="data">'.$business['permit'].'</div></td>
                                    <td><img src="../../img/review.svg" alt="Review"></td>
                                </tr>';
                }
                    ?>
                </table>
        </div>
    </main>

</body>
</html>