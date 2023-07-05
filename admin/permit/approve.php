<?php 
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../../index.php");
    exit;
}

if(isset($_GET['id'])){
    $businessID = $_SESSION['businessID'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['businessID'])){
    $businessID = $_SESSION['businessID'];
}else{
    header("location: msme.php");
    exit;
}
$businessID = validate($businessID);

$modal_display = "hidden";

$sql_business = "SELECT * FROM Business WHERE BusinessID = ?";

    if($stmt_business = $mysqli->prepare($sql_business)){
        $stmt_business->bind_param("s",$param_id);

        $param_id = $businessID;

        if($stmt_business->execute()){
            $result = $stmt_business->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $logo = $row["Logo"];
                $bus_name = $row["Name"];
                $logo = $row["Logo"];
                if($row["Logo"] == null){
                    $logo = null;
                }else{
                    $logo = "../../user/Business/upload/".$businessID."/".$row["Logo"];
                }
                $barangay_b = $row["Barangay"];
                $ownerID = $row['OwnerID'];

            }else {
                $bus_name = "Not yet created";
            }
        }else{
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Business Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_business->close();
    }

    $sql_owner = "SELECT * FROM Owner WHERE OwnerID = ?";

    if($stmt_owner = $mysqli->prepare($sql_owner)){
        $stmt_owner->bind_param("s",$param_id);

        $param_id = $ownerID;

        if($stmt_owner->execute()){
            $result = $stmt_owner->get_result();

            if($result->num_rows == 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fname = $row["FirstName"];
                $lname = $row["LastName"];
                $suffix = $row["Suffix"];
                $name = $fname." ".$lname." ".$suffix;
            }
        }else{

            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Owner Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_owner->close();
    }

    $sql_requirements = "SELECT COUNT(CASE WHEN Status = 'Approved' THEN 1 ELSE NULL END) AS Approved FROM `Requirement` WHERE BusinessID = ?;";

    if($stmt_requirements = $mysqli->prepare($sql_requirements)){
        $stmt_requirements->bind_param("s",$param_id);

        $param_id = $businessID;

        if($stmt_requirements->execute()){
            $result = $stmt_requirements->get_result();

            if($result->num_rows == 1){

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $approved = $row['Approved'];

                if($approved !== 11){
                    $modal_display = "";
                    $modal_status = "error";
                    $modal_title = "Requirement Approved";
                    $modal_message = "Not All Requirements are approved";
                    $modal_button = '<a href="review.php">OK</a>';
                }
            }
        }else{

            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Requirement Info Error";
            $modal_message = "Requirements cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_requirements->close();
    }

    $sql_permit = "SELECT * FROM Permit WHERE BusinessID = ?";

    if($stmt_permit = $mysqli->prepare($sql_permit)){
        $stmt_permit->bind_param("s",$param_id);

        $param_id = $businessID;

        if($stmt_permit->execute()){
            $result = $stmt_permit->get_result();

            if($result->num_rows === 1){
                $permit = "Issued";
            }else{
                $permit = "None";
            }
        }else{
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Permit Error";
            $modal_message = "Permit cannot be retrieve";
            $modal_button = '<a href="msme.php">OK</a>';
        }
        $stmt_permit->close();
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if($approved === 11){

        $sql_id = "SELECT PermitID as maxID FROM Permit ORDER BY PermitID DESC LIMIT 1";

            if($stmt_id = $mysqli->prepare($sql_id)) {

                if($stmt_id->execute()){  

                    $stmt_id->bind_result($maxID);

                    if($stmt_id->fetch()) {
                        $lastID = $maxID;
                    }
                }
            $stmt_id->close();
            }
            $currentYear = date('Y');
            if(!empty($lastID)) {
                $year = substr($lastID, 2, 4);
                $countDash = substr($lastID, 7);
                $count = str_replace("-","",$countDash);

                if($year === $currentYear) {
                    $count += 1;
                }else {
                    $count = 0;
                }
            }else {
                $count = 0;
            }

            $count = str_pad($count, 6, '0', STR_PAD_LEFT);
            $countDash = substr_replace($count, "-", 3, 0);
            $permitID = "P-" . $currentYear . "-" . $countDash;

        $sql_permit = "INSERT INTO Permit (PermitID, BusinessID, Type, IssuedBy) VALUES (?, ?, ?, ?)";

        if($stmt_permit = $mysqli->prepare($sql_permit)){
            $stmt_permit->bind_param("ssss", $param_PermitID, $param_BusinessID, $param_Type, $param_IssuedBy);

            $param_PermitID = $permitID;
            $param_BusinessID = $businessID;
            $param_Type = "New";
            $param_IssuedBy = $_SESSION['AdminID'];

            if($stmt_permit->execute()){
                $modal_display = "";
                $modal_status = "success";
                $modal_title = "Permit has been Issued";
                $modal_button = '<a href="approve.php">OK</a>';
            }else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Issuing Permit Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="review.php">OK</a>';
            }
        }

        $stmt_permit->close();
    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Incomplete Approved Requirements";
        $modal_message = "All documents must be approved";
        $modal_button = '<a href="review.php">OK</a>';
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
                    <p class="sentence">Business</p>
                    <div class="logo"> 
                        <img src="<?= $logo ?>" alt="Business Logo">
                    </div>
                    <p class="title text-center"><?= $bus_name ?></p>
                    <p class="sentence text-center"><?= $barangay_b ?></p>
                </subsection>
                <subsection>
                <p class="sentence">Owner</p> 
                <p class="title text-center"><?= $name ?></p> 
                </subsection>
                <subsection>
                <p class="sentence">Delete Permit</p> 
                    <a class="action delete" href="../management/edit_business.php?id=<?= $userID ?>"><img src="../../img/delete.svg" alt="Delete"></a>
                </subsection>
            </section>
            <section class="map-container">
                <subsection class="space-between">
                        <p class="title">Issuing Business Permit</p>
                        <p class="whole-paragraph">As the admin, it is crucial to carefully consider the reviewed documents and their compliance with the necessary requirements. Once approved, the business permit will be granted, and it will signify that the reviewed documents have met the necessary criteria for permit issuance. Please ensure you have thoroughly assessed the documents and are confident in your decision to proceed with the approval.</p>
                        <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <input type="submit" class="approve" value="Approve">
                        </form>
                </subsection>
                <subsection>
                <p class="sentence">Permit</p> 
                    <div class="info title" id="permit-status"><?= $permit ?></div>
                </subsection>
            </section>
        </div>
    </main>
</body>
</html>