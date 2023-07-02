<?php
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../index.php");
    exit;
}

$modal_display = "hidden";

$sql = "SELECT * FROM Admin WHERE UserID = ?";

if($stmt = $mysqli->prepare($sql)){

    $stmt->bind_param("s",$param_id);

    $param_id = validate($_SESSION['UserID']);

    if($stmt->execute()){ 

        $result = $stmt->get_result();

            if($result->num_rows === 1){
                $submit_btn = "Update";

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fname = $row["FirstName"];
                $mname = $row["MiddleName"];
                $lname = $row["LastName"];
                $suffix = $row["Suffix"];
                $role = $row["Role"];
                $_SESSION["AdminID"] = $row['AdminID'];

            }else {
                $submit_btn = "Submit";
            }

        }else {
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Owner Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="dashboard.php">OK</a>';
        }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $errors = [];
        //FIRST NAME
        $fname = validate($_POST["fname"]);
        if(empty($fname)){
            $errors["fname"] = "Enter First Name";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $fname)){
            $errors["fname"] = "Only Letters and Spaces are allowed";
        } else{
              $fname = ucwords(strtolower($fname));
        }
        //MIDDLE NAME
        $mname = validate($_POST["mname"]);
        if(!empty($mname)){
            if(!preg_match("/^[a-zA-Z- ]*$/", $mname)){
                $errors["mname"] = "Only Letters and Spaces are allowed";
            }else{
                $mname = ucwords(strtolower($mname));
            }
        }else{
            $mname = null;
        }
        //LAST NAME
        $lname = validate($_POST["lname"]);
        if(empty($lname)){
            $errors["lname"] = "Enter Suffix";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $lname)){
            $errors["lname"] = "Only Letters and Spaces are allowed";
        } else{
              $lname = ucwords(strtolower($lname));
        }
        //SUFFIX
        $suffix = validate($_POST["suffix"]);
        if(!empty($suffix)){
            if(!preg_match("/^[a-zA-Z]*$/", $suffix)){
                $errors["suffix"] = "Only Letters are allowed";
            }else{
                $suffix = ucwords(strtolower($suffix));
            }
        }else{
            $suffix = null;
        }
        //Role
        $role = validate($_POST["role"]);
        if(empty($role)){
            $errors["role"] = "Enter Role";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $role)){
            $errors["role"] = "Only Letters and Spaces are allowed";
        } else{
              $role = ucwords(strtolower($role));
        }

    //insert to database
    if(empty($errors)){ 

        if($submit_btn === "Update"){
            $sql = "UPDATE Admin SET FirstName = ?, MiddleName = ?, LastName = ?, Suffix = ?, Role = ? WHERE AdminID = ?";
        }else {

            $sql_owner = "SELECT AdminID as maxID FROM Admin ORDER BY AdminID DESC LIMIT 1";

            if($stmt_owner = $mysqli->prepare($sql_owner)) {

                if($stmt_owner->execute()){  

                    $stmt_owner->bind_result($maxID);

                    if($stmt_owner->fetch()) {
                        $lastID = $maxID;
                    }
                }
            $stmt_owner->close();
            }
            $currentYear = date('Y');
            if($lastID !== null) {
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
            $adminID = "A-" . $currentYear . "-" . $countDash; 

            $sql = "INSERT INTO Admin (UserID, AdminID, FirstName, MiddleName, LastName, Suffix, Role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        }

       if($stmt = $mysqli->prepare($sql)){
 
            if($submit_btn === "Update"){
                $stmt->bind_param("ssssss",$param_fname, $param_mname, $param_lname, $param_suffix,$param_role, $param_AdminID);

                $param_AdminID = validate($_SESSION['AdminID']);
            }else {
                $stmt->bind_param("sssssss",$param_UserID, $param_AdminID, $param_fname, $param_mname, $param_lname, $param_suffix,$param_role);

                $param_AdminID = $adminID;
                $param_UserID = validate($_SESSION['UserID']);
            }
            
            $param_fname = $fname;
            $param_mname = $mname;
            $param_lname = $lname;
            $param_suffix = $suffix;
            $param_role = $role;
                        
            if($stmt->execute()){
                $modal_display = "";
                $modal_status = "success";

                if($submit_btn === "Update"){
                    $modal_title = "Admin Profile Information Updated";
                    $modal_message = "Your Admin Profile has been updated";
                }else{
                    $_SESSION['AdminID'] = $adminID;
                    $modal_title = "Admin Profile Creation Success";
                    $modal_message = "You can now access the data ";
                }
                $modal_button = '<a href="dashboard.php">View</a>';
            } else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Admin Profile Information Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="../index.php">OK</a>';
            }
            $stmt->close();
          }
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
    <link rel="shortcut icon" href="../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Javascript -->
    <script src="../js/script.js" defer></script>
    <script src="../js/form.js" defer></script>
    <script src="../js/modal.js" defer></script>
    <title>Edit Profile</title>
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
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li class="current"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="management/users.php">Management</a></li>
                <li><a href="permit/msme.php">Permit</a></li>
                <li><a href="../php/logout.php">Logout</a></li>
            </ul>
            <ul id="subnav-links">
                <li class="current"><a href="edit_profile.php">Edit Profile</a></li>
            </ul>
        </div>
    </nav>

    <nav id="subnav">
        <div class="logo">
            <img src="../img/admin.svg" alt="Tarlac City Seal">
            <p>Admin</p>  
        </div>
        <div class="button-group">
            <ul>
                <li class="current"><a href="edit_profile.php">Edit Profile</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container">   
            <div class="text-center">
                <p class="title">Admin Profile</p>
                <p class="sentence">Enter your informations to make a profile</p>
            </div>

            <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                <div class="input-row">

                    <div class="input-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" placeholder="First Name" value="<?= $fname; ?>">
                        <div class="error-msg"><?= $errors["fname"]; ?></div>
                    </div>

                    <div class="input-group">
                        <label for="lname">Surname</label>
                        <input type="text" id="lname" name="lname" placeholder="Surname" value="<?= $lname; ?>">
                        <div class="error-msg"><?= $errors["lname"]; ?></div>
                    </div>
                </div>

                <div class="input-row">

                    <div class="input-group">
                        <label for="mname">Middle Name<span>(Optional)</span></label>
                        <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?= $mname; ?>">
                        <div class="error-msg"><?= $errors["mname"]; ?></div>
                    </div>

                    <div class="input-group">
                        <label for="suffix">Suffix<span>(Optional)</span></label>
                        <input type="text" id="suffix" name="suffix" placeholder="Suffix" value="<?= $suffix; ?>">
                        <div class="error-msg"><?= $errors["suffix"]; ?></div>
                    </div>
                </div>

                <div class="input-row">

                    <div class="input-group">
                        <label for="mname">Role</label>
                        <input type="text" id="role" name="role" placeholder="Role" value="<?= $role; ?>">
                        <div class="error-msg"><?= $errors["role"]; ?></div>
                    </div>
                </div>

            <input type="submit" value="<?= $submit_btn ?>">
        </form>
        </div>
    </main>
</body>
</html>