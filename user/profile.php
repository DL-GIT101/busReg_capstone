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
        if(empty($mname)){
            $mname = " ";
        }elseif(!preg_match("/^[a-zA-Z- ]*$/", $mname)){
            $mname_err = "Only Letters and Spaces are allowed";
        }else{
            $mname = ucwords(strtolower($mname));
        }
        //LAST NAME
        $lname = validate($_POST["lname"]);
        if(empty($lname)){
            $lname_err = "Enter SUffix";
        } elseif(!preg_match("/^[a-zA-Z- ]*$/", $lname)){
            $lname = "Only Letters and Spaces are allowed";
        } else{
              $lname = ucwords(strtolower($lname));
        }
        //SUFFIX
        $suffix = validate($_POST["suffix"]);
        if(empty($suffix)){
            $suffix = " ";
        }elseif(!preg_match("/^[a-zA-Z]*$/", $suffix)){
            $suffix_err = "Only Letters are allowed";
        }else{
            $suffix = ucwords(strtolower($suffix));
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
                
                <form autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
                            <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo $fname; ?>">
                            <div class="error"><?php echo $fname_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="mname">Middle Name<span>(Optional)</span></label>
                            <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo $mname; ?>">
                            <div class="error"><?php echo $mname_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="lname">Surname</label>
                            <input type="text" id="lname" name="lname" placeholder="Surname" value="<?php echo $lname; ?>">
                            <div class="error"><?php echo $lname_err; ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="group">
                            <label for="suffix">Suffix<span>(Optional)</span></label>
                            <input type="text" id="suffix" name="suffix" placeholder="suffix" value="<?php echo $suffix; ?>">
                            <div class="error"><?php echo $suffix_err; ?></div>
                        </div>
                        <div class="group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Select Gender..</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <div class="error"></div>
                        </div>
                    </div>
                    
                <!--BUSINESS -->
                     <p class="title">Business</p>
                     <div class="row">
                        <div class="group">
                            <label for="bus_name">Name</label>
                            <input type="text" id="bus_name" name="bus_name" placeholder="Business Name" value="">
                            <div class="error"></div>
                        </div>
                        <div class="group">
                            <label for="logo">Logo</label>
                            <input type="file" id="logo" name="logo">
                            <div class="error"></div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="group">
                            <label for="activity">Activity</label>
                            <input type="text" id="activity" name="activity" placeholder="Business Activity" value="">
                            <div class="error"></div>
                        </div>
                        <div class="group">
                            <label for="contact">Contact Number</label>
                            <input type="text" id="contact" name="contact" placeholder="Contact Number" value="">
                            <div class="error"></div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="group">
                            <label for="address_1">House No./Unit No./Building/Street</label>
                            <input type="text" id="address_1" name="address_1" placeholder="House No./Unit No./Building/Street" value="">
                            <div class="error"></div>
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
                                foreach ($barangays as $barangay){
                                    echo "<option value='$barangay'>$barangay</option>";
                                }
                            ?>
                            </select>
                            <div class="error"></div>
                        </div>
                     </div>
                </div>
                <div class="frame wide">
                    <p class="title">Pin Location</p>
                    <div id="map"></div>
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