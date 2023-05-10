<?php 

require_once "../php/config.php";

$fname = $fname_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
        }elseif(!preg_match("/^[a-zA-Z&*@\\-!#()%+?\"\/~\s]*$/", $bus_name)){
            $bus_name_err = "Only letters, numbers, and special characters are allowed";
        }else{
            $bus_name = ucwords(strtolower($bus_name));
        }
        //logo
        $logo = $_FILES['logo'];
        if(!empty($logo)){
            $name = $logo['name'];
            $temp_name = $logo['tmp_name'];
            $size = $logo['size'];
            $type = $logo['type'];
            $valid_extensions = array("jpg","peg", "png", "svg");
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if(in_array($extension, $valid_extensions)){

                if($size > 2097152){
                    $logo_err = "File size must be less than 2MB";
                }else{
                    $new_name = $_SESSION["id"].".".$extension;
                    $path = "upload/". $new_name;

                    if(move_uploaded_file($temp_name,$path)){

                    }else {
                        $logo_err = "Error uploading";
                    }
                }
            } else{
                $logo_err = "Only JPG, JPEG, PNG, SVG file types are allowed";
            }
        }
        //activity
        $activity = validate($_POST["activity"]);
        if(empty($activity)){
            $activity_err = "Enter Business Activity";
        } elseif(!preg_match("/^[a-zA-Z]*$/", $activity)){
            $activity_err = "Only Letters are allowed";
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
        }elseif(!preg_match("/^[a-zA-Z0-9&*@#().\/~-]*$/", $address_1)){
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
        if(empty($latitude) && empty($longitude)){
            $latlang_err = "Pin the business location";
        }

    }
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
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>
    <script src="../js/map.js" defer></script>
    <script src="../js/pinLocation.js" defer></script>
    <script src="../js/form.js" defer></script>
</head>
<body>
<nav id="navbar">
       <div id="logo">
        <a href="../index.php">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p>  
        </a>
       </div>

       <div id="user">
            <a href="welcome.php">Dashboard</a>
            <a href="../php/logout.php">Logout</a>
       </div>
    </nav>

    <div id="content">
        <div class="container">
                
                <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="row">
                <div class="frame wide">
                    <div class="intro">
                        <p class="title">Profile</p>
                        <p class="sentence">Enter your informations to make a profile</p>
                    </div>
                <!--Owner -->
                    <p class="title">Owner</p>
                    <div class="row">
                        <div class="group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" placeholder="First Name" value="<?= $fname; ?>">
                            <div class="error"><?= $fname_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="mname">Middle Name<span>(Optional)</span></label>
                            <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?= $mname; ?>">
                            <div class="error"><?= $mname_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="lname">Surname</label>
                            <input type="text" id="lname" name="lname" placeholder="Surname" value="<?= $lname; ?>">
                            <div class="error"><?= $lname_err; ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="group">
                            <label for="suffix">Suffix<span>(Optional)</span></label>
                            <input type="text" id="suffix" name="suffix" placeholder="Suffix" value="<?= $suffix; ?>">
                            <div class="error"><?= $suffix_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Select Gender..</option>
                                <option value="Male" <?= $gender === "Male" ? "selected" : "" ?>>Male</option>
                                <option value="Female" <?= $gender === "Female" ? "selected" : "" ?>>Female</option>
                            </select>
                            <div class="error"><?= $gender_err; ?></div>
                        </div>
                    </div>
                    
                <!--BUSINESS -->
                     <p class="title">Business</p>
                     <div class="row">
                        <div class="group">
                            <label for="bus_name">Name</label>
                            <input type="text" id="bus_name" name="bus_name" placeholder="Business Name" value="<?= $bus_name; ?>">
                            <div class="error"><?= $bus_name_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="logo">Logo<span>(Optional)</span></label>
                            <input type="file" id="logo" name="logo">
                            <div class="error"><?= $logo_err; ?></div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="group">
                            <label for="activity">Activity</label>
                            <input type="text" id="activity" name="activity" placeholder="Business Activity" value="<?= $activity; ?>">
                            <div class="error"><?= $activity_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="contact">Contact Number</label>
                            <div class="row">
                            <input type="text" disabled value="+63">
                            <input type="text" id="contact" name="contact" placeholder="Contact Number" maxlength="10" value="<?= $contact; ?>">
                            </div>
                            <div class="error"><?= $contact_err; ?></div>
                            
                        </div>
                     </div>
                     <div class="row">
                        <div class="group">
                            <label for="address_1">House No./Unit No./Building/Street</label>
                            <input type="text" id="address_1" name="address_1" placeholder="House No./Unit No./Building/Street" value="<?= $address_1; ?>">
                            <div class="error"><?= $address_1_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="address_2">Barangay</label>
                            <select id="address_2" name="address_2">
                            <option value="" disabled selected>Select Barangay...</option>
                            <?php
                                $barangays = array(
                                    'Aguso',
                                    'Alvindia',
                                    'Amucao',
                                    'Armenia',
                                    'Asturias',
                                    'Atioc',
                                    'Balanti',
                                    'Balete',
                                    'Balibago I',
                                    'Balibago II',
                                    'Balingcanaway',
                                    'Banaba',
                                    'Bantog',
                                    'Baras-baras',
                                    'Batang-batang',
                                    'Binauganan',
                                    'Bora',
                                    'Buenavista',
                                    'Buhilit',
                                    'Burot',
                                    'Calingcuan',
                                    'Capehan',
                                    'Carangian',
                                    'Care',
                                    'Central',
                                    'Culipat',
                                    'Cut-cut I',
                                    'Cut-cut II',
                                    'Dalayap',
                                    'Dela Paz',
                                    'Dolores',
                                    'Laoang',
                                    'Ligtasan',
                                    'Lourdes',
                                    'Mabini',
                                    'Maligaya',
                                    'Maliwalo',
                                    'Mapalacsiao',
                                    'Mapalad',
                                    'Matatalaib',
                                    'Paraiso',
                                    'Poblacion',
                                    'Salapungan',
                                    'San Carlos',
                                    'San Francisco',
                                    'San Isidro',
                                    'San Jose',
                                    'San Jose de Urquico',
                                    'San Juan Bautista',
                                    'San Juan de Mata',
                                    'San Luis',
                                    'San Manuel',
                                    'San Miguel',
                                    'San Nicolas',
                                    'San Pablo',
                                    'San Pascual',
                                    'San Rafael',
                                    'San Roque',
                                    'San Sebastian',
                                    'San Vicente',
                                    'Santa Cruz',
                                    'Santa Maria',
                                    'Santo Cristo',
                                    'Santo Domingo',
                                    'Santo Niño',
                                    'Sapang Maragul',
                                    'Sapang Tagalog',
                                    'Sepung Calzada',
                                    'Sinait',
                                    'Suizo',
                                    'Tariji',
                                    'Tibag',
                                    'Tibagan',
                                    'Trinidad',
                                    'Ungot',
                                    'Villa Bacolor'
                                );
                                foreach ($barangays as $barangay) {
                                    echo "<option value='$barangay' " . ($address_2 === $barangay ? "selected" : "") . ">$barangay</option>";

                                }
                                
                            ?>
                            </select>
                            <div class="error"><?= $address_2_err; ?></div>
                        </div>
                     </div>
                </div>
                <div class="frame wide">
                    <p class="title">Pin Location</p>
                    <div id="map"></div>
                    <input type="text" id="latitude" name="latitude" value="<?= $latitude; ?>" hidden> 
                    <input type="text" id="longitude" name="longitude" value="<?= $longitude; ?>" hidden>
                    <div class="error"><?= $latlang_err; ?></div>
                    <input type="submit" value="Create Profile">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!-- owner  - [name - gender ] 
    business_profile - [name - activity - logo -  contact_no - add_line_1 - add_line_2 - pin]
     ->