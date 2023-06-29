<?php
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";

if($_SESSION["role"] !== "user"){
    header("location: ../../index.php");
    exit;
}

$modal_display = "hidden";

$sql = "SELECT * FROM Business WHERE BusinessID = ?";

if($stmt = $mysqli->prepare($sql)){

    $stmt->bind_param("s",$param_id);

    $param_id = validate($_SESSION['BusinessID']);

    if($stmt->execute()){ 

        $result = $stmt->get_result();

            if($result->num_rows === 1){
                $submit_btn = "Update";

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $_SESSION['BusinessID'] = $row['BusinessID'];
                $bus_name = $row["Name"];
                $logo = $row["Logo"];
                if($row["Logo"] == null){
                    $logo = null;
                }else{
                    $logo = "upload/".$_SESSION['BusinessID']."/".$row["Logo"];
                }
                $activity = $row["Activity"];
                $contact = substr($row["ContactNumber"],3);
                $address = $row["Address"];
                $barangay = $row["Barangay"];
                $latitude = $row["Latitude"];
                $longitude = $row["Longitude"]; 

            }else {
                $submit_btn = "Submit";
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
        //business name
        $bus_name = validate($_POST["bus_name"]);
        if(empty($bus_name)){
            $errors["bus_name"] = "Enter Business Name";
        }elseif(!preg_match("/^[a-zA-Z0-9&*@\\-!#()%+?\"\/~\s]*$/", $bus_name)){
            $errors["bus_name"] = "Only letters, numbers, and special characters are allowed";
        }else{
            $bus_name = ucwords(strtolower($bus_name));
        }
        //activity
        $activity = validate($_POST["activity"]);
        if(empty($activity)){
            $errors["activity"] = "Enter Business Activity";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $activity)){
            $errors["activity"] = "Only Letters and Spaces are allowed";
        }else{
            $activity = ucwords(strtolower($activity));
        }
        // contact number
        $contact = validate($_POST['contact']);
        if(empty($contact)){
            $errors["contact"] = "Enter Contact Number";
        } elseif(!preg_match('/^[0-9]{10}$/',$contact)){
            $errors["contact"] = "Only Numbers are allowed";
        }
        // address one
        $address  = $_POST['address'];
        if(empty($address)){
            $errors["address"] = "Enter Address";
        }elseif(!preg_match("/^[a-zA-Z 0-9&*@#().\/~-]*$/", $address)){
            $errors["address"] = "Invalid Address";
        } else{
            $address = ucwords(strtolower($address));
        }
        //barangay
        $barangay = validate($_POST["barangay"]);
        if(empty($barangay)){
            $errors["barangay"]  = "Select Barangay";
        }
        //location
        $latitude = validate($_POST["latitude"]);
        $longitude = validate($_POST["longitude"]);
        if(empty($latitude) || empty($longitude)){
            $errors["location"]  = "Pin the business location";
        }

    //insert to database
    if(empty($errors)){ 

        if($submit_btn === "Update"){
            $sql = "UPDATE Business SET Name = ?, Logo = ?, Activity = ?, ContactNumber = ?, Address = ?, Barangay = ?, Latitude = ?, Longitude = ? WHERE BusinessID = ?";
        }else {

            $sql_id = "SELECT BusinessID as maxID FROM Business ORDER BY BusinessID DESC LIMIT 1";

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
            $businessID = "B-" . $currentYear . "-" . $countDash;

            $directoryName = "upload/". $businessID;
            mkdir($directoryName, 0777, true);

            $sql = "INSERT INTO Business (BusinessID, OwnerID, Name, Logo, Activity, ContactNumber, Address, Barangay, Latitude, Longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

       if($stmt = $mysqli->prepare($sql)){
 
            if($submit_btn === "Update"){
                $stmt->bind_param("ssssssdds",$param_bname, $param_logo, $param_activity, $param_contact, $param_address, $param_barangay, $param_latitude, $param_longitude, $param_BusinessID);
                
            }else {
                $stmt->bind_param("ssssssssdd",$param_BusinessID, $param_OwnerID, $param_bname, $param_logo, $param_activity, $param_contact, $param_address, $param_barangay, $param_latitude, $param_longitude);

               
                $param_OwnerID = validate($_SESSION['OwnerID']);
            }

            $param_BusinessID = $businessID;
            $param_bname = $bus_name;
            $param_activity = $activity;
            $param_contact = "+63".$contact;
            $param_address = $address;
            $param_barangay = $barangay;
            $param_latitude = $latitude;
            $param_longitude = $longitude;

        //logo
        $targetDir = "upload/".$businessID."/";
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

                $errors["logo"]  = "File size should be 2MB or less";

            }else{

                if(move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFilePath)){

                        $param_logo = $new_fileName;

                        if(!empty($logo)){
                            unlink($logo);
                        }
                        
                        if($stmt->execute()){

                            $modal_display = "";
                            $modal_status = "success";

                            if($submit_btn === "Update"){
                                $modal_title = "Business Profile Information Updated";
                                $modal_message = "Your Business Profile has been updated";
                            }else{
                                $_SESSION['BusinessID'] = $businessID;
                                $modal_title = "Business Profile Creation Success";
                                $modal_message = "You can now view your business profile and use our services";
                            }
                            $modal_button = '<a href="../dashboard.php">Dashboard</a>';
                        } else{
                            $modal_display = "";
                            $modal_status = "error";
                            $modal_title = "Business Profile Information Error";
                            $modal_message = "Try again later";
                            $modal_button = '<a href="../../index.php">OK</a>';
                        }
                }else{
                    $errors["logo"] = "Error uploading " . $_FILES['logo']['error'];
                }
            }

        }else {
            $errors["logo"] = "Only jpg, jpeg, png, and svg are allowed";
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

                if($submit_btn === "Update"){
                    $modal_title = "Business Profile Information Updated";
                    $modal_message = "Your Business Profile has been updated <br>";
                }else{
                    $_SESSION['BusinessID'] = $businessID;
                    $modal_title = "Business Profile Creation Success";
                    $modal_message = "You can now view your Business profile and use our services  <br>";
                }
                $modal_button = '<a href="../dashboard.php">Dashboard</a>';

            } else{
                $modal_display = "";
                $modal_status = "error";
                $modal_title = "Business Profile Information Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="../../index.php">OK</a>';
            }
          }
           
        }
        $stmt->close();
    } 

    }else if(checkPermit($businessID) === "Issued"){

        $modal_display = "";
        $modal_status = "warning";
        $modal_title = "Business Profile cannot be updated";
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
    <script src="../../js/map.js" defer></script>
    <script src="../../js/pinLocation.js" defer></script>
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
        <div class="column-container height-auto">   
            <div class="text-center">
                <p class="title">Business Profile</p>
                <p class="sentence">Enter your informations to make a profile</p>
            </div>

            <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <section class="height-auto">

                <!--BUSINESS -->
            <p class="title text-center">Business</p>
            <div class="input-row">

                <div class="input-group">
                    <label for="bus_name">Name</label>
                    <input type="text" id="bus_name" name="bus_name" placeholder="Business Name" value="<?= $bus_name; ?>">
                    <div class="error-msg"><?= $errors["bus_name"]; ?></div>
                </div>

                <div class="input-group">
                    <label for="logo">Logo<span>(Optional)</span></label>
                    <input type="file" id="logo" name="logo">
                    <div class="error-msg"><?= $errors["logo"]; ?></div>
                </div>
            </div>

            <div class="input-row">

                <div class="input-group">
                    <label for="activity">Activity</label>
                    <input type="text" id="activity" name="activity" placeholder="Business Activity" value="<?= $activity; ?>">
                    <div class="error-msg"><?= $errors["activity"]; ?></div>
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
                        <div class="error-msg"><?= $errors["address_2"]; ?></div>
                </div>
            </div>
        
        </section>

        <section class="height-auto">
        
            <p class="title text-center">Pin Location</p>
            <map id="map"></map>
                <input type="text" id="latitude" name="latitude" value="<?= $latitude; ?>" hidden> 
                <input type="text" id="longitude" name="longitude" value="<?= $longitude; ?>" hidden>
                <div class="error-msg"><?= $errors["location"]; ?></div>
            <input type="submit" value="<?= $submit_btn; ?>">
        </section>
        
        </form>
        </div>
    </main>
</body>
</html>