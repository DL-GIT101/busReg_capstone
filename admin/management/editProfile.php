<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if(checkRole($_SESSION["role"]) !== "admin"){
    header("location: ../../index.php");
    exit;
}

$modal_display = "hidden";

$user_id = validate($_SESSION['user_id']);

$sql = "SELECT * FROM user_profile WHERE user_id = ?";
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("s",$param_id);

    $param_id = $user_id;

    if($stmt->execute()){ 
        $result = $stmt->get_result();

            if($result->num_rows === 1){
                $submit_btn = "Update Profile";

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $fname = $row["first_name"];
                $mname = $row["middle_name"];
                $lname = $row["last_name"];
                $suffix = $row["suffix"];
                $gender = $row["gender"];

                $bus_name = $row["business_name"];
                $logo = $row["logo"];
                if($row["logo"] == null){
                    $logo = null;
                }else{
                    $logo = "../../user/upload/".$user_id."/".$row["logo"];
                }
                $activity = $row["activity"];
                $contact = substr($row["contact_number"],3);
                $address_1 = $row["address_1"];
                $address_2 = $row["address_2"];
                $latitude = $row["latitude"];
                $longitude = $row["longitude"]; 

            }else {
                $submit_btn = "Create Profile";
            }
        }else{
            $modal_display = "";
            $modal_status = "fail";
            $modal_title = "Profile Information Error";
            $modal_message = "Profile cannot be retrieve";
            $modal_button = '<a href="profiles.php">OK</a>';
        }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(checkPermit($user_id) === "None"){

    $modal_display = "hidden";

        //FIRST NAME
        $fname = validate($_POST["fname"]);
        if(empty($fname)){
            $fname_err = "Enter First Name";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $fname)){
            $fname_err = "Only Letters and Spaces are allowed";
        } else{
              $fname = ucwords(strtolower($fname));
        }
        //MIDDLE NAME
        $mname = validate($_POST["mname"]);
        if(!empty($mname)){
            if(!preg_match("/^[a-zA-Z- ]*$/", $mname)){
                $mname_err = "Only Letters and Spaces are allowed";
            }else{
                $mname = ucwords(strtolower($mname));
            }
        }else{
            $mname = '';
        }
        //LAST NAME
        $lname = validate($_POST["lname"]);
        if(empty($lname)){
            $lname_err = "Enter Suffix";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $lname)){
            $lname_err = "Only Letters and Spaces are allowed";
        } else{
              $lname = ucwords(strtolower($lname));
        }
        //SUFFIX
        $suffix = validate($_POST["suffix"]);
        if(!empty($suffix)){
            if(!preg_match("/^[a-zA-Z]*$/", $suffix)){
                $suffix_err = "Only Letters are allowed";
            }else{
                $suffix = ucwords(strtolower($suffix));
            }
        }else{
            $suffix = '';
        }
        //gender
        $gender = validate($_POST["gender"]);
        if(empty($gender)){
            $gender_err = "Select Gender";
        }
        //business name
        $bus_name = validate($_POST["bus_name"]);
        if(empty($bus_name)){
            $bus_name_err = "Enter Business Name";
        }elseif(!preg_match("/^[a-zA-Z0-9&*@\\-!#()%+?\"\/~\s]*$/", $bus_name)){
            $bus_name_err = "Only letters, numbers, and special characters are allowed";
        }else{
            $bus_name = ucwords(strtolower($bus_name));
        }
        
        //activity
        $activity = validate($_POST["activity"]);
        if(empty($activity)){
            $activity_err = "Enter Business Activity";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $activity)){
            $activity_err = "Only Letters and Spaces are allowed";
        }else{
            $activity = ucwords(strtolower($activity));
        }
        // contact number
        $contact = validate($_POST['contact']);
        if(empty($contact)){
            $contact_err = "Enter Contact Number";
        } elseif(!preg_match('/^[0-9]{10}$/',$contact)){
            $contact_err = "Only Numbers are allowed";
        }
        // address one
        $address_1 = $_POST['address_1'];
        if(empty($address_1)){
            $address_1_err = "Enter Address";
        }elseif(!preg_match("/^[a-zA-Z 0-9&*@#().\/~-]*$/", $address_1)){
            $address_1_err = "Invalid Address";
        } else{
            $address_1 = ucwords(strtolower($address_1));
        }
        //barangay
        $address_2 = validate($_POST["address_2"]);
        if(empty($address_2)){
            $address_2_err = "Select Barangay";
        }
        //location
        $latitude = validate($_POST["latitude"]);
        $longitude = validate($_POST["longitude"]);
        if(empty($latitude) || empty($longitude)){
            $latlang_err = "Pin the business location";
        }

    //insert to database
    if(empty($fname_err) && empty($mname_err) && empty($lname_err) && empty($suffix_err) && empty($gender_err) && empty($bus_name_err) && empty($logo_err) && empty($activity_err) &&empty($contact_err) && empty($address_1_err) && empty($address_2_err) && empty($latlang_err)){ 

        if($submit_btn === "Update Profile"){
            $sql = "UPDATE user_profile SET first_name = ?, middle_name = ?, last_name = ?, suffix = ?, gender = ?, business_name = ?, logo = ?, activity = ?, contact_number = ?, address_1 = ?, address_2 = ?, latitude = ?, longitude = ? WHERE user_id = ?";
        }else {
            $sql = "INSERT INTO user_profile (user_id, first_name, middle_name, last_name, suffix, gender, business_name, logo, activity, contact_number, address_1, address_2, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

       if($stmt = $mysqli->prepare($sql)){
 
            if($submit_btn === "Update Profile"){
                $stmt->bind_param("sssssssssssdds",$param_fname, $param_mname, $param_lname, $param_suffix, $param_gender, $param_bname, $param_logo, $param_activity, $param_contact, $param_address1, $param_address2, $param_latitude, $param_longitude, $param_id);
            }else {
                $stmt->bind_param("ssssssssssssdd",$param_id, $param_fname, $param_mname, $param_lname, $param_suffix, $param_gender, $param_bname, $param_logo, $param_activity, $param_contact, $param_address1, $param_address2, $param_latitude, $param_longitude);

            }

            $param_id = $user_id;
            $param_fname = $fname;
            $param_mname = $mname;
            $param_lname = $lname;
            $param_suffix = $suffix;
            $param_gender = $gender;
            $param_bname = $bus_name;
            $param_activity = $activity;
            $param_contact = "+63".$contact;
            $param_address1 = $address_1;
            $param_address2 = $address_2;
            $param_latitude = $latitude;
            $param_longitude = $longitude;

        //logo
        $targetDir = "../../user/upload/".$user_id."/";
        $fileName = basename($_FILES["logo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        $fileSize = $_FILES["logo"]["size"];
        //new name
        $new_fileName = "LOGO_". uniqid() ."_". $fileName;
        $targetFilePath = $targetDir . $new_fileName;
           
        if(!empty($_FILES["logo"]["name"])){

        $allowTypes = array('jpg','png','jpeg','svg');

        if(in_array($fileType,$allowTypes)){

            if($fileSize > 2097152){

                $logo_err = "File size should be 2MB or less";

            }else{

                if(move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath)){

                    $param_logo = $new_fileName;

                    if(!empty($logo)){
                        unlink($logo);
                    }
                    
                    if($stmt->execute()){
                        $modal_display = "";
                        $modal_status = "success";
                        if($submit_btn === "Update Profile"){
                            $modal_title = "Profile Information Updated";
                            $message = "Your Profile has been updated <br>";
                        }else{
                            $modal_title = "Profile Creation Success";
                            $modal_message = "You can now view the profile<br>";
                        }
                        $modal_message .= "You can view your profile now";
                        $modal_button = '<a href="profiles.php">Profile</a>';
                    } else{
                        $modal_display = "";
                        $modal_status = "fail";
                        $modal_title = "Profile Information Error";
                        $modal_message = "Try again later";
                        $modal_button = '<a href="profiles.php">OK</a>';
                    }
                }else{
                    $logo_err = "Error uploading" . $_FILES['logo']['error'];
                }
            }

        }else {
            $logo_err = "Only jpg, jpeg, png, and svg are allowed";
        }
 
        }else{
            if($logo == null){
                $logo = null;
            }else{
                $logo = basename($logo);
            }
           $param_logo = $logo;  
            if($stmt->execute()){
                $modal_display = "";
                        $modal_status = "success";
                        if($submit_btn === "Update Profile"){
                            $modal_title = "Profile Information Updated";
                            $modal_message = "Profile has been updated <br>";
                        }else{
                            $modal_title = "Profile Creation Success";
                            $modal_message = "You can now view the profile<br>";
                        }
                        $modal_message .= "You can view the profile now";
                        $modal_button = '<a href="profiles.php">Profile</a>';
            } else{
                $modal_display = "";
                $modal_status = "fail";
                $modal_title = "Profile Information Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="users.php">Profile</a>';
            }
          }
           
        }
        $stmt->close();
    } 
   
    }else if(checkPermit($user_id) === "Approved"){
        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "Profile cannot be updated";
        $modal_message = "The permit has already been approved";
        $modal_button = '<a href="profiles.php">OK</a>';
    }else{
        $modal_display = "";
        $modal_status = "error";
        $modal_title = "Something went wrong";
        $modal_message = "Try again later";
        $modal_button = '<a href="profiles.php">OK</a>';
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
    <!-- OpenStreetMap Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <!-- Javascript -->
    <script src="../../js/script.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <script src="../../js/modal.js" defer></script>
    <script src="../../js/map.js" defer></script>
    <script src="../../js/pinLocation.js" defer></script>
    <title>Create/Edit Profile</title>
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
                <li><a href="users.php">List</a></li>
                <li class="current"><a href="profiles.php">Profile</a></li>
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
                <li><a href="users.php">List</a></li>
                <li class="current"><a href="profiles.php">Profile</a></li>
                <li><a href="documents.php">Documents</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container height-auto">   
            <div class="text-center">
                <p class="title">Profile</p>
                <p class="sentence">Enter your informations to make a profile</p>
            </div>

            <form class="long-form" autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <section class="height-auto">
                <!--Owner -->
                <p class="title text-center">Owner</p>
                <div class="input-row">

                    <div class="input-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" placeholder="First Name" value="<?= $fname; ?>">
                        <div class="error_msg"><?= $fname_err; ?></div>
                    </div>

                    <div class="input-group">
                        <label for="lname">Surname</label>
                        <input type="text" id="lname" name="lname" placeholder="Surname" value="<?= $lname; ?>">
                        <div class="error_msg"><?= $lname_err; ?></div>
                    </div>
                </div>

                <div class="input-row">

                    <div class="input-group">
                        <label for="mname">Middle Name<span>(Optional)</span></label>
                        <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?= $mname; ?>">
                        <div class="error_msg"><?= $mname_err; ?></div>
                    </div>

                    <div class="input-group">
                        <label for="suffix">Suffix<span>(Optional)</span></label>
                        <input type="text" id="suffix" name="suffix" placeholder="Suffix" value="<?= $suffix; ?>">
                        <div class="error_msg"><?= $suffix_err; ?></div>
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
                        <div class="error_msg"><?= $gender_err; ?></div>
                    </div>
                </div>

                <!--BUSINESS -->
            <p class="title text-center">Business</p>
            <div class="input-row">

                <div class="input-group">
                    <label for="bus_name">Name</label>
                    <input type="text" id="bus_name" name="bus_name" placeholder="Business Name" value="<?= $bus_name; ?>">
                    <div class="error_msg"><?= $bus_name_err; ?></div>
                </div>

                <div class="input-group">
                    <label for="logo">Logo<span>(Optional)</span></label>
                    <input type="file" id="logo" name="logo">
                    <div class="error_msg"><?= $logo_err; ?></div>
                </div>
            </div>

            <div class="input-row">

                <div class="input-group">
                    <label for="activity">Activity</label>
                    <input type="text" id="activity" name="activity" placeholder="Business Activity" value="<?= $activity; ?>">
                    <div class="error_msg"><?= $activity_err; ?></div>
                </div>

                <div class="input-group">
                    <label for="contact">Contact Number</label>
                    <div class="flex">
                        <div class="pre-input">+63</div>
                        <input class="flex-grow-1" type="text" id="contact" name="contact" placeholder="Contact Number" maxlength="10" value="<?= $contact; ?>">
                    </div>
                    <div class="error_msg"><?= $contact_err; ?></div>
                </div>
            </div>

            <div class="input-row">

                <div class="input-group">
                    <label for="address_1">House/Unit No./Building/Street</label>
                    <input type="text" id="address_1" name="address_1" placeholder="House No./Unit No./Building/Street" value="<?= $address_1; ?>">
                    <div class="error_msg"><?= $address_1_err; ?></div>
                </div>

                <div class="input-group">
                    <label for="address_2">Barangay</label>
                    <select id="address_2" name="address_2">
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
                                foreach ($barangays as $barangay) {
                                    echo "<option value='$barangay' " . ($address_2 === $barangay ? "selected" : "") . ">$barangay</option>";

                                }
                            ?>
                        </select>
                        <div class="error_msg"><?= $address_2_err; ?></div>
                </div>
            </div>
        
        </section>

        <section class="height-auto">
        
            <p class="title text-center">Pin Location</p>
            <map id="map"></map>
                <input type="text" id="latitude" name="latitude" value="<?= $latitude; ?>" hidden> 
                <input type="text" id="longitude" name="longitude" value="<?= $longitude; ?>" hidden>
                <div class="error_msg"><?= $latlang_err; ?></div>
            <input type="submit" value="<?= $submit_btn; ?>">
        </section>
        
        </form>
        </div>
    </main>

</body>
</html>