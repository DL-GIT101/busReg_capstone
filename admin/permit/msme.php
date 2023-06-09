<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: ../../login.php");
    exit;
}

require_once "../../php/connection.php";

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
        $permit_sql = "SELECT * FROM user_profile WHERE user_id = ? ORDER BY user_id DESC";
                if ($permit_stmt = $mysqli->prepare($permit_sql)) {
                    $permit_stmt->bind_param("s", $current_id);
                    $current_id = $business['id'];
                    if ($permit_stmt->execute()) {
                        $result = $permit_stmt->get_result();
                        if ($result->num_rows === 1) {
                            $row = $result->fetch_array(MYSQLI_ASSOC);

                            $name = $row["business_name"];
                            $business['name'] = $name;
                            $activity = $row["activity"];
                            $business['activity'] = $activity;
                            $permit = $row["permit_status"];
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
                            $business['documents'] = "None";
                        }
                    } else {
                        echo "Error retrieving data";
                    }
                    $document_stmt->close();
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
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/script.js" defer></script>
    <script src="../../js/admin_td_click.js" defer></script>
    <title>MSME List</title>
</head>
<body>
<modal id="user_del" class="hidden">
        <div class="content fail">
            <p class="title">Delete File</p>
            <p class="sentence">Are you sure you want to delete this user? This action cannot be undone</p>
            <div id="btn_grp" class="flex align-self-center">
                <a href="" id="user_link">Delete</a>
                <button>Cancel</button>
            </div>
        </div>
</modal>
<nav>
        <div id="nav_logo">
                <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../../php/logout.php">Logout</a>
        </div>
</nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li ><img src="../../img/dashboard.png" alt=""><a href="../dashboard.php">Dashboard</a></li>
            <li ><img src="../../img/register.png" alt=""><a href="../management/users.php">MSME Management</a></li>
            <li class="current"><img src="../../img/list.png" alt=""><a href="">MSME Permit</a></li>
        </ul>
    </nav>

    <main class="flex-grow-1">
        <content class="justify-center">
            <div class="column_container"> 
                    <p id="page" class="title text-center">MSME List</p>
                <table>   
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Activity</th>
                        <th>Documents</th>
                        <th>Permit</th>
                    </tr>
                    <?php 
                    foreach ($all_business as &$business) {
                        echo '  <tr class="user_info">  
                                    <td>'.$business['id'].'</td>
                                    <td>'.$business['name'].'</td>
                                    <td>'.$business['activity'].'</td>
                                    <td><div class="info '.strtolower($business['documents']) .'">'.$business['documents'].'</div></td>
                                    <td><div class="info '.strtolower($business['permit']) .'">'.$business['permit'].'</div></td>
                                </tr>';
                }
                    ?>
                </table>
            </div>
        </content>
    </main>
</div>

</body>
</html>