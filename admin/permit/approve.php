<?php
session_start();
require_once "../../php/connection.php";

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: ../management.php");
}

$modal = "hidden";

$sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $user_id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);

                $business_name = $row["business_name"];
                $name = $row["first_name"]." ".$row["middle_name"]." ".$row["last_name"]." ".$row["suffix"];
                $business_activity = $row["activity"];
                $gender = $row['gender'];
                $contact = $row['contact_number'];
                $address = $row["address_1"]." ".$row["address_2"];

            }else{
                $profile = "hidden";
                $none = "";
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

    if($approvedReq === 12){

        $sql = "INSERT INTO permit (user_id, status) VALUES (?,?)";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ss", $param_id, $param_Status);

            $param_id = $user_id;
            $param_Status = "Approved";

            if($stmt->execute()){
                $modal = "";
                $status_modal = "success";
                $title = "Permit has been Approved";
                $message = "Status can now be seen";
                $button = '<a href="review.php">OK</a>';
            }else{
                $modal = "";
                $status_modal = "fail";
                $title = "Approving Permit Error";
                $message = "Try again later";
                $button = '<a href="review.php">OK</a>';
            }
        }

    }else{
        $modal = "";
        $status_modal = "fail";
        $title = "Incomplete Documents";
        $message = "All documents must be approved";
        $button = '<button class="close">OK</button>';
    }
    $stmt->close();
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
    <script src="../../js/file_modal.js" defer></script>
    <title>Approve</title>
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

<modal id="approve_modal" class="hidden">
        <div class="content success">
            <p class="title">Approving Permit</p>
            <p class="sentence">Once approved, the business permit will be granted based on the reviewed documents. Are you certain about this decision?</p>
            <p class="sentence"></p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <div id="btn_grp" class="flex align-self-center success">
                    <input type="submit" value="Approve">
                    <button>Cancel</button>
                </div> 
            </form>
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
            <li class="current"><img src="../../img/list.png" alt=""><a href="msme.php">MSME Permit</a></li>
            
        </ul>
    </nav>

    <main class="flex-grow-1 flex-wrap content-center">

    <div class="actions space-between">
            <p id="page" class="title">Approve</p>
            <p class="sentence"> User ID : <?= $user_id ?></p>
            <div class="buttons">
                <a href="review.php" class="back">Review</a>
                
            </div>
        </div>

        <content>
            <section>
                <subsection class="space-around">
                    <p class="sentence">Business Profile</p>
                    <div class="text-center">
                        <p class="title"><?= $business_name ?></p>
                        <p class="sentence"><?= $business_activity ?></p> 
                        <p class="sentence"><?= $address ?></p>
                    </div>
                </subsection>
                <subsection class="space-around">
                <p class="sentence">Business Owner</p> 
                    <div class="text-center">
                        <p class="title"><?= $name ?></p> 
                        <p class="sentence"><?= $gender ?></p> 
                        <p class="sentence"><?= $contact ?></p>
                    </div>
                </subsection>
            </section>
            <section class="content_50">
                <subsection>
                        <p class="title">Business Permit</p>
                        <p class="p_container">As the admin, it is crucial to carefully consider the reviewed documents and their compliance with the necessary requirements. Once approved, the business permit will be granted, and it will signify that the reviewed documents have met the necessary criteria for permit issuance. Please ensure you have thoroughly assessed the documents and are confident in your decision to proceed with the approval.</p>
                        <button id="approve_btn" class="approve">Approve</button>
                </subsection>
                <subsection class="space-around">
                    <p class="title">Approved Documents</p> 
                    <div class="info title approved"><?= $approvedReq ?>/12</div>

                    <p class="title">Business Permit Status</p> 
                    <div class="info title <?= strtolower($permit_status)?>" id="permit_status"><?= $permit_status ?></div>
                </subsection>
            </section>
        </content>
    </main>
</div>
</body>
</html>