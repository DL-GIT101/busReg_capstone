<?php 
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "user"){
    header("location: ../index.php");
    exit;
}

$modal_display = "hidden";

$sql = "SELECT * FROM Owner WHERE UserID = ?";

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
                $gender = $row["Gender"];
                $contact = substr($row["ContactNumber"],3);
                $address = $row["Address"];
                $barangay = $row["Barangay"];

            }else {
                $submit_btn = "Submit";
                $modal_display = "";
                $modal_status = "gray";
                $modal_title = "Create Owner Profile";
                $modal_message = "Please create your profile before accessing our services";
                $modal_button = '<button class="close">Close</button>';
            }

        }else {
            $modal_display = "";
            $modal_status = "error";
            $modal_title = "Owner Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="../dashboard.php">OK</a>';
        }

    $stmt->close();
}

if(isset($_SESSION['BusinessID'])){
    $businessID = validate($_SESSION['BusinessID']);
}else{
    $businessID = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(checkPermit($businessID) === "None"){

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
        //gender
        $gender = validate($_POST["gender"]);
        if(empty($gender)){
            $errors["gender"] = "Select Gender";
        }
        // contact number
        $contact = validate($_POST['contact']);
        if(empty($contact)){
            $errors["contact"] = "Enter Contact Number";
        } elseif(!preg_match('/^[0-9]{10}$/',$contact)){
            $errors["contact"] = "Only Numbers are allowed";
        }
        // address
        $address = $_POST['address'];
        if(empty($address)){
            $errors["address_1"] = "Enter Address";
        }elseif(!preg_match("/^[a-zA-Z 0-9&*@#().\/~-]*$/", $address)){
            $errors["address"] = "Invalid Address";
        } else{
            $address = ucwords(strtolower($address_1));
        }
        //barangay
        $barangay = validate($_POST["barangay"]);
        if(empty($barangay)){
            $errors["barangay"]  = "Select Barangay";
        }

    //insert to database
    if(empty($errors)){ 

        if($submit_btn === "Update"){
            $sql = "UPDATE Owner SET FirstName = ?, MiddleName = ?, LastName = ?, Suffix = ?, Gender = ?, ContactNumber = ?, Address = ?, Barangay = ? WHERE OwnerID = ?";
        }else {

            $sql_owner = "SELECT OwnerID as maxID FROM Owner ORDER BY OwnerID DESC LIMIT 1";

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
            if($lastID) {
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
            $ownerID = "O-" . $currentYear . "-" . $countDash; 

            $sql = "INSERT INTO Owner (UserID, OwnerID, FirstName, MiddleName, LastName, Suffix, Gender, ContactNumber, Address, Barangay) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

       if($stmt = $mysqli->prepare($sql)){
 
            if($submit_btn === "Update"){
                $stmt->bind_param("sssssssss",$param_fname, $param_mname, $param_lname, $param_suffix, $param_gender, $param_contact, $param_address, $param_barangay, $param_OwnerID);

                $param_OwnerID = validate($_SESSION['OwnerID']);
            }else {
                $stmt->bind_param("ssssssssss",$param_UserID, $param_OwnerID, $param_fname, $param_mname, $param_lname, $param_suffix, $param_gender, $param_contact, $param_address, $param_barangay);

                $param_OwnerID = $ownerID;
                $param_UserID = validate($_SESSION['UserID']);
            }
            
            $param_fname = $fname;
            $param_mname = $mname;
            $param_lname = $lname;
            $param_suffix = $suffix;
            $param_gender = $gender;
            $param_contact = "+63".$contact;
            $param_address = $address;
            $param_barangay = $barangay;
                        
            if($stmt->execute()){
                $modal_display = "";
                $modal_status = "success";

                if($submit_btn === "Update"){
                    $modal_title = "Owner Profile Information Updated";
                    $message = "Your Owner Profile has been updated";
                }else{
                    $modal_title = "Owner Profile Creation Success";
                    $modal_message = "You can now view your profile and use our services ";
                }
                $modal_button = '<a href="../dashboard.php">View</a>';
            } else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Owner Profile Information Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="../../index.php">OK</a>';
            }
          }
           
        }

        $stmt->close();

    }else if(checkPermit($businessID) === "Issued"){

        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "Owner Profile cannot be updated";
        $modal_message = "The permit has already been issued";
        $modal_button = '<a href="dashboard.php">OK</a>';

    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Business Permit Error";
        $modal_message = "Try again Later";
        $modal_button = '<a href="../../index.php">OK</a>';
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
    <link rel="shortcut icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="../../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
    <!-- Javascript -->
    <script src="../../js/script.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <script src="../../js/modal.js" defer></script>
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
                <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../../php/logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container">   
            <div class="text-center">
                <p class="title">Owner Profile</p>
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
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender">
                            <option value="" disabled selected>Select Gender..</option>
                            <option value="Male" <?= $gender === "Male" ? "selected" : "" ?>>Male</option>
                            <option value="Female" <?= $gender === "Female" ? "selected" : "" ?>>Female</option>
                        </select>
                        <div class="error-msg"><?= $errors["gender"]; ?></div>
                    </div>

                    <div class="input-group">
                        <label for="contact">Contact Number</label>
                        <div class="flex">
                            <div class="pre-input">+63</div>
                            <input class="flex-grow-1" type="text" id="contact" name="contact" placeholder="Contact Number" maxlength="10" value="<?= $contact; ?>">
                        </div>
                        <div class="error-msg"><?= $errors["contact"]; ?></div>
                    </div>
                </div>

            <div class="input-row">

                <div class="input-group">
                    <label for="address">House/Unit No./Building/Street</label>
                    <input type="text" id="address" name="address" placeholder="House No./Unit No./Building/Street" value="<?= $address; ?>">
                    <div class="error-msg"><?= $errors["address"]; ?></div>
                </div>

                <div class="input-group">
                    <label for="barangay">Barangay</label>
                    <select id="barangay" name="barangay">
                    <option value="" disabled selected>Select Barangay...</option>
                        <?php
                        $barangays = array(
                            'Aguso','Alvindia','Amucao','Armenia','Asturias','Atioc',
                            'Balanti','Balete','Balibago I','Balibago II','Balingcanaway','Banaba','Bantog','Baras-baras','Batang-batang','Binauganan','Bora','Buenavista','Buhilit','Burot',
                            'Calingcuan','Capehan','Carangian','Care','Central','Culipat','Cut-cut I','Cut-cut II',
                            'Dalayap','Dela Paz','Dolores',
                            'Laoang','Ligtasan','Lourdes',
                            'Mabini','Maligaya','Maliwalo','Mapalacsiao','Mapalad','Matatalaib',
                            'Paraiso','Poblacion',
                            'Salapungan','San Carlos','San Francisco','San Isidro','San Jose','San Jose de Urquico','San Juan Bautista','San Juan de Mata','San Luis','San Manuel','San Miguel','San Nicolas','San Pablo','San Pascual','San Rafael','San Roque','San Sebastian','San Vicente','Santa Cruz','Santa Maria','Santo Cristo','Santo Domingo','Santo Niño','Sapang Maragul','Sapang Tagalog','Sepung Calzada','Sinait','Suizo',
                            'Tariji','Tibag','Tibagan','Trinidad',
                            'Ungot',
                            'Villa Bacolor'
                                );
                                foreach ($barangays as $barangay_name) {
                                    echo "<option value='$barangay_name' " . ($barangay === $barangay_name ? "selected" : "") . ">$barangay_name</option>";

                                }
                            ?>
                        </select>
                        <div class="error-msg"><?= $errors["barangay"]; ?></div>
                </div>
            </div>
            <input type="submit" value="<?= $submit_btn ?>">
        </form>
        </div>
    </main>
</body>
</html>