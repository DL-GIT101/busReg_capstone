<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: msme.php");
    exit;
}

$modal_display = "hidden";

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"]." ".$row["suffix"];
                $business_activity = $row["activity"];
                $gender = $row['gender'];
                $contact = $row['contact_number'];
                $address = $row["address_1"]." ".$row["address_2"];

            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }

    $sql2 = "SELECT * FROM new_documents WHERE user_id = ?";
   
    if($stmt2 = $mysqli->prepare($sql2)){
        
        $stmt2->bind_param("s",$param_id);
        
        $param_id = $user_id;
       
        if($stmt2->execute()){
            $result = $stmt2->get_result();
            if($result->num_rows === 1){
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_status_fetch = $row["status"];
                $status_fetch = unserialize($serialized_status_fetch);     
                $approvedReq = 0;
                foreach($status_fetch as $status){
                    if($status === "Approved"){
                        $approvedReq++;
                    }
                }
            }
        }else {
            echo "error retrieving data";
        }
    $stmt2->close();
    }
    
    $sql3 = "SELECT * FROM permit WHERE user_id = ?";

    if($stmt3 = $mysqli->prepare($sql3)){
        $stmt3->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt3->execute()){
            $result = $stmt3->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $permit_status = $row['status'];
                
            }else {
                $permit_status = "None";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt3->close();
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if($approvedReq === 11){

        $sql4 = "INSERT INTO permit (user_id, status) VALUES (?,?)";

        if($stmt4 = $mysqli->prepare($sql4)){
            $stmt->bind_param("ss", $param_id, $param_Status);

            $param_id = $user_id;
            $param_Status = "Approved";

            if($stmt4->execute()){
                $modal_display = "";
                $modal_status = "success";
                $modal_title = "Permit has been Approved";
                $modal_message = "Status can now be seen";
                $modal_button = '<a href="review.php">OK</a>';
            }else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Approving Permit Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="review.php">OK</a>';
            }
        }

        $stmt4->close();
    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Incomplete Documents";
        $modal_message = "All documents must be approved";
        $modal_button = '<button class="close">OK</button>';
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
    <script src="../../js/form.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/profile.js" defer></script>
    <title>Approve</title>
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
                <li><a href="msme.php">List</a></li>
                <li><a href="review.php">Review</a></li>
                <li class="current"><a href="approve.php">Approve</a></li>
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
                <li><a href="msme.php">List</a></li>
                <li><a href="review.php">Review</a></li>
                <li class="current"><a href="approve.php">Approve</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            <section>
                <subsection class="space-between">
                    <p class="sentence">Business Profile</p>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $business_activity ?></p> 
                    </div>
                    <p class="sentence text-center"><?= $address ?></p>
                </subsection>
                <subsection class="space-between">
                <p class="sentence">Business Owner</p> 
                <p class="title text-center"><?= $name ?></p> 
                    <div class="text-center">
                        <p class="sentence"><?= $gender ?></p> 
                        <p class="sentence"><?= $contact ?></p>
                    </div>
                </subsection>
            </section>
            <section class="map-container">
                <subsection class="space-around">
                    <p class="sentence-title">Approved Documents</p> 
                    <div class="info title approved"><?= $approvedReq ?>/12</div>

                    <p class="sentence-title">Business Permit Status</p> 
                    <div class="info title" id="permit-status"><?= $permit_status ?></div>
                </subsection>
                <subsection>
                        <p class="title">Business Permit</p>
                        <p class="whole-paragraph">As the admin, it is crucial to carefully consider the reviewed documents and their compliance with the necessary requirements. Once approved, the business permit will be granted, and it will signify that the reviewed documents have met the necessary criteria for permit issuance. Please ensure you have thoroughly assessed the documents and are confident in your decision to proceed with the approval.</p>
                        form
                        <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="submit" class="approve" value="Approve">
                        </form>
                </subsection>
            </section>
        </div>
    </main>
</body>
</html>