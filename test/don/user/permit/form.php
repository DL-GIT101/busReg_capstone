<?php
session_start();
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    
    $psicSQL = "SELECT DISTINCT PSIC_SEC_DESC FROM psic_descriptor ORDER BY PSIC_SEC_DESC ASC";
    $psicRESULT = $conn->query($psicSQL);

    $IDuser = $_SESSION["IDuser"];
    
    $userSQL = "SELECT * FROM user WHERE ID = '$IDuser'";
    $userRESULT = $conn->query($userSQL);
    $userROW = $userRESULT->fetch_assoc();

    $permitSQL = "  SELECT permit.status
                    FROM user
                    JOIN owner ON user.ID = owner.userID
                    JOIN business ON owner.ID = business.ownerID
                    JOIN permit ON business.ID = permit.businessID WHERE user.ID = '$IDuser';";
    $permitRESULT = $conn->query($permitSQL);
    $permitROW = $permitRESULT->fetch_assoc();

    if(!empty($permitROW["status"])){
        $permitStatus = $permitROW["status"];
    }else{
        $permitStatus = "none";
        }

    $type = $type_error = $payment = $payment_error = $business = $business_error = $dti = $dti_error = $tin = $tin1 = $tin2 = $tin3 = $tin4 = $tin_error = $business_name = $business_name_error = $franchise = $franchise_error = $houseNo = $houseNo_error = $building = $building_error =  $lotNo = $lotNo_error = $blockNo = $blockNo_error = $street = $street_error = $barangay = $barangay_error = $subdivision = $subdivision_error = $owned = $owned_error = $property = $property_error = $lessor = $lessor_error = $rent = $rent_error =  $telephone1 = $telephone2 = $telephone_error = $mobile1 = $mobile2 = $mobile3 = $mobile_error = $gender = $gender_error =  $area = $area_error = $van = $van_error = $motorcycle = $motorcycle_error = $empTarlac = $empTarlac_error = $empMale = $empMale_error = $empFemale = $empFemale_error = $disableForm =  $houseNoOwner = $houseNoOwner_error = $buildingOwner = $buildingOwner_error =  $lotNoOwner = $lotNoOwner_error = $blockNoOwner = $blockNoOwner_error = $streetOwner = $streetOwner_error = $barangayOwner = $barangayOwner_error = $subdivisionOwner = $subdivisionOwner_error = $incentive = $incentive_error = $activity = $activity_error = $specify = $specify_error = $lineCount = $lineCount_error =  "";
    $lineArray = array();
    $lineError = array();

    if($permitStatus=="SUBMITTED"){
        $application  = "Duplicate";
        $message = "You have already submitted the application form";
    }else if($permitStatus=="APPROVED"){
        $application  = "Success";
        $message = "Application Form has been approves";
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if($_POST["formSubmit"]=="Submit"){

    //Type of Registration
        if(empty($_POST["type"])){
            $type_error = "Select Type of Registration";
        }else{
            $type = validate($_POST["type"]);
        }

    //Payment
        if(empty($_POST["payment"])){
            $payment_error = "Select Payment Schedule";
        }else{
            $payment = validate($_POST["payment"]);
        }

    //Business Type
        if(empty($_POST["business"])){
            $business_error = "Select Business Type";
        }else{
            $business = validate($_POST["business"]);
            $uppbusiness = strtoupper($business);
        }

    //DTI   
        if(empty($_POST["dti"])){ 
            $dti_error = "Input Certificate No.";
        }else{
            $dti = validate($_POST["dti"]);
            if(!preg_match('/^[0-9]{8}$/', $dti)){
                $dti_error = "Invalid Certificate No.";
            }
        }
    //TIN
        if(empty($_POST["tin1"]) && empty($_POST["tin2"]) && empty($_POST["tin3"]) && empty($_POST["tin4"]) ){
            $tin_error = "Input TIN No.";
        }else{
            $tin1 = validate($_POST["tin1"]);
            if(!preg_match("/^\d{3}$/", $tin1)){
                $tin_error = "Invalid TIN No.";
            }
            $tin2 = validate($_POST["tin2"]);
            if(!preg_match("/^\d{3}$/", $tin2)){
                $tin_error = "Invalid TIN No.";
            }
            $tin3 = validate($_POST["tin3"]);
            if(!preg_match("/^\d{3}$/", $tin3)){
                $tin_error = "Invalid TIN No.";
            } 
            $tin4 = validate($_POST["tin4"]);
            if(!preg_match("/^\d{3}$/", $tin4)){
                $tin_error = "Invalid TIN No.";
            }         
            if(empty($tin_error)){
                $tin = $tin1."-".$tin2."-".$tin3."-".$tin4;
            }
        }
    //Business Names
        if(empty($_POST["business_name"])){
            $business_name_error = "Input Business Name";
        }else{
            $business_name = validate($_POST["business_name"]);
            $uppbusiness_name = strtoupper($business_name);
            if(!preg_match("/^[a-zA-Z0-9- ]*$/",$business_name)){
                $business_name_error = "Only letters, numbers, dash and space are allowed";
            }
        }
    // Trade Name/Franchise
        if(empty($_POST["franchise"])){
            $franchise = "";
            $uppfranchise = "";
        }else{
            $franchise = validate($_POST["franchise"]);
            $uppfranchise = strtoupper($franchise);
            if(!preg_match("/^[a-zA-Z0-9- ]*$/",$franchise)){
                $franchise_error = "Only letters, numbers, dash and space are allowed";
            }
        }
    // Business Address
        //House and Building No.
            if(empty($_POST["houseNo"])){
                $houseNo = "";
            }else{
                $houseNo = validate($_POST["houseNo"]);
                if(!preg_match("/^[0-9]*$/",$houseNo)){
                    $houseNo_error = "Only  numbers are allowed";
                }
            }
        //Building Name
            if(empty($_POST["building"])){
                $building = "";
                $uppbuilding = "";
            }else{
                $building = validate($_POST["building"]);
                $uppbuilding = strtoupper($building);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$building)){
                    $building_error = "Only letters, numbers, dash and space are allowed";
                }
            }
        // Lot No
            if(empty($_POST["lotNo"])){
                $lotNo = "";
            }else{
                $lotNo = validate($_POST["lotNo"]);
                if(!preg_match("/^[0-9]*$/",$lotNo)){
                    $lotNo_error = "Only  numbers are allowed";
                }
            }
        // Block No
            if(empty($_POST["blockNo"])){
                $blockNo = "";
            }else{
                $blockNo = validate($_POST["blockNo"]);
                if(!preg_match("/^[0-9]*$/",$blockNo)){
                    $blockNo_error = "Only  numbers are allowed";
                }
            }
         if(!empty($_POST["street"]) || !empty($_POST["barangay"]) ||!empty($_POST["subdivision"])){   
        //Street
            if(empty($_POST["street"])){
                $street = "";
                $uppstreet = "";
            }else{
                $street = validate($_POST["street"]);
                $uppstreet = strtoupper($street);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$street)){
                    $street_error = "Only letters, numbers, dash and space are allowed";
                }
            }
        //Barangay
            if(empty($_POST["barangay"])){
                $barangay = "";
                $uppbarangay = "";
            }else{
                $barangay = validate($_POST["barangay"]);
                $uppbarangay = strtoupper($barangay);
            }
        //Subdivision
            if(empty($_POST["subdivision"])){
                $subdivision = "";
                $uppsubdivision = "";
            }else{
                $subdivision = validate($_POST["subdivision"]);
                $uppsubdivision = strtoupper($subdivision);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$subdivision)){
                    $subdivision_error = "Only letters, numbers, dash and space are allowed";
                }
            }
            }else{
                $street_error = "Input atleast one of the following";
                $barangay_error = "Input atleast one of the following";
                $subdivision_error = "Input atleast one of the following";
            }
    
    // Was the business address oowned by the owner
        if(empty($_POST["owned"])){
            $owned_error = "Select your answer";
        }else{
            $owned = validate($_POST["owned"]);
            if($owned=="Yes"){
                // Tax Declaration No./Property Identification No.
                $upplessor = strtoupper($lessor);
                    if(empty($_POST["property"])){ 
                        $property_error = "Input Certificate No.";
                    }else{
                        $property = validate($_POST["property"]);
                        if(!preg_match('/^(?:\d+-\d+-?)+$/', $property)){
                            $property_error = "Invalid Certificate No.";
                        }
                    }
            }else if($owned=="No"){
                // Lessor Name
                    if(empty($_POST["lessor"])){ 
                        $lessor_error = "Input name";
                    }else{
                        $lessor = validate($_POST["lessor"]);
                        $upplessor = strtoupper($lessor);
                        if(!preg_match('/^[a-zA-Z\s]+$/', $lessor)){
                            $lessor_error = "Invalid Name";
                        }
                    }
                // rent
                    if(empty($_POST["rent"])){ 
                        $rent_error = "Input rent";
                    }else{
                        $rent = validate($_POST["rent"]);
                        if(!preg_match('/^\d+(?:\.\d{2})?$|^\d+$/', $rent)){
                            $rent_error = "Invalid Rent";
                        }
                    }
            }
        }
    
    // Phone No. and Mobile No.
        if((!empty($_POST["telephone1"]) && !empty($_POST["telephone2"]))||(!empty($_POST["mobile1"]) &&!empty($_POST["mobile2"]) && !empty($_POST["mobile3"]))){
        // Phone No.
            if(empty($_POST["telephone1"]) && empty($_POST["telephone2"])){
                $telephone = "";
            }else{
                $telephone1 = validate($_POST["telephone1"]);
                if(!preg_match("/^\d{3}$/", $telephone1)){
                    $telephone_error = "Invalid Telephone No.";
                }
                $telephone2 = validate($_POST["telephone2"]);
                if(!preg_match("/^\d{4}$/", $telephone2)){
                    $telephone_error = "Invalid Telephone No.";
                }
                if(empty($telephone_error)){
                        $telephone = "[045] ".$telephone1."-".$telephone2;
                }
            }
        // Mobile No.
            if(empty($_POST["mobile1"]) && empty($_POST["mobile2"]) && empty($_POST["mobile3"])){
                $mobile = "";
            }else{
                $mobile1 = validate($_POST["mobile1"]);
                if(!preg_match("/^\d{3}$/", $mobile1)){
                    $mobile_error = "Invalid Mobile No.";
                }
                $mobile2 = validate($_POST["mobile2"]);
                if(!preg_match("/^\d{3}$/", $mobile2)){
                    $mobile_error = "Invalid Mobile No.";
                }
                $mobile3 = validate($_POST["mobile3"]);
                if(!preg_match("/^\d{4}$/", $mobile3)){
                    $mobile_error = "Invalid Mobile No.";
                }     
                if(empty($mobile_error)){
                    $mobile = "+63".$mobile1."-".$mobile2."-".$mobile3;
                }
            }
            }else{
                $telephone_error = "Input Telephone or Mobile No.";
                $mobile_error = $telephone_error;
            }

    // For Sole Proprietorship
        if(empty($_POST["gender"])){
            $gender_error = "Select Gender";
        }else{
            $gender = validate($_POST["gender"]);
            $uppgender = strtoupper($gender);
        }
    // Business Operation
        // Business Area/Total Floor Area in sqm
            if(!isset($_POST["area"])){
                $area_error = "Input business area";
            }else{
                $area = validate($_POST["area"]);
                if(!preg_match("/^[0-9]+$/", $area)){
                    $area_error = "Input Numbers only";
                }
            }
        // No of delivery vehickles
            //Van/Truck
                if(!isset($_POST["van"])){
                    $van_error = "Input No. of Van/Truck";
                }else{
                    $van = validate($_POST["van"]);
                    if(!preg_match("/^[0-9]+$/", $van)){
                        $van_error = "Input Numbers only";
                    }
                }
            // motorcycle
                if(!isset($_POST["motorcycle"])){
                    $motorcycle_error = "Input No. of Motorcycle";
                }else{
                    $motorcycle = validate($_POST["motorcycle"]);
                    if(!preg_match("/^[0-9]+$/", $motorcycle)){
                        $motorcycle_error = "Input Numbers only";
                    }
                }
        // No. of Employees residing within Tarlac City
            if(!isset($_POST["empTarlac"])){
                $empTarlac_error = "Input total employees";
            }else{
                $empTarlac = validate($_POST["empTarlac"]);
                if(!preg_match("/^[0-9]+$/", $empTarlac)){
                    $empTarlac_error = "Input Numbers only";
                }
            }
        // Total No. of Employees in Establishment
            //Male
                if(!isset($_POST["empMale"])){
                    $empMale_error = "Input No. of Male Employees";
                }else{
                    $empMale = validate($_POST["empMale"]);
                    if(!preg_match("/^[0-9]+$/", $empMale)){
                        $empMale_error = "Input Numbers only";
                    }
                }
            //Female
                if(!isset($_POST["empFemale"])){
                    $empFemale_error = "Input No. of Female Employees";
                }else{
                    $empFemale = validate($_POST["empFemale"]);
                    if(!preg_match("/^[0-9]+$/", $empFemale)){
                        $empFemale_error = "Input Numbers only";
                    }
                }
    // Taxpayers Address
        if(!empty($_POST["disableForm"])){
            $disableForm = $_POST["disableForm"];
            $houseNoOwner = $houseNo;
            $buildingOwner = $building;
            $uppbuildingOwner = strtoupper($buildingOwner);
            $lotNoOwner = $lotNo;
            $blockNoOwner = $blockNo;
            $streetOwner = $street;
            $uppstreetOwner = strtoupper($streetOwner);
            $barangayOwner = $barangay;
            $uppbarangayOwner = strtoupper($barangayOwner);
            $subdivisionOwner = $subdivision;
            $uppsubdivisionOwner = strtoupper($subdivisionOwner);
        }else{
        //House and Building No.
            if(empty($_POST["houseNoOwner"])){
                $houseNoOwner = "";
            }else{
                $houseNoOwner = validate($_POST["houseNoOwner"]);
                if(!preg_match("/^[0-9]*$/",$houseNoOwner)){
                    $houseNoOwner_error = "Only  numbers are allowed";
                }
            }
        //Building Name
            if(empty($_POST["buildingOwner"])){
                $buildingOwner = "";
                $uppbuildingOwner = "";
            }else{
                $buildingOwner = validate($_POST["buildingOwner"]);
                $uppbuildingOwner = strtoupper($buildingOwner);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$buildingOwner)){
                    $buildingOwner_error = "Only letters, numbers, dash and space are allowed";
                }
            }
        // Lot No
            if(empty($_POST["lotNoOwner"])){
                $lotNoOwner = "";
            }else{
                $lotNoOwner = validate($_POST["lotNoOwner"]);
                if(!preg_match("/^[0-9]*$/",$lotNoOwner)){
                    $lotNoOwner_error = "Only  numbers are allowed";
                }
            }
        // Block No
            if(empty($_POST["blockNoOwner"])){
                $blockNoOwner = "";
            }else{
                $blockNoOwner = validate($_POST["blockNoOwner"]);
                if(!preg_match("/^[0-9]*$/",$blockNoOwner)){
                    $blockNoOwner_error = "Only  numbers are allowed";
                }
            }
            if(!empty($_POST["streetOwner"]) || !empty($_POST["barangayOwner"]) ||!empty($_POST["subdivisionOwner"])){   
        //Street
            if(empty($_POST["streetOwner"])){
                $streetOwner = "";
                $uppstreetOwner = "";
            }else{
                $streetOwner = validate($_POST["streetOwner"]);
                $uppstreetOwner = strtoupper($streetOwner);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$streetOwner)){
                    $streetOwner_error = "Only letters, numbers, dash and space are allowed";
                }
            }
        //Barangay
            if(empty($_POST["barangayOwner"])){
                $barangayOwner = "";
                $uppbarangayOwner = "";
            }else{
                $barangayOwner = validate($_POST["barangayOwner"]);
                $uppbarangayOwner = strtoupper($barangayOwner);
            }
        //Subdivision
            if(empty($_POST["subdivisionOwner"])){
                $subdivisionOwner = "";
                $uppsubdivisionOwner = "";
            }else{
                $subdivisionOwner = validate($_POST["subdivisionOwner"]);
                $uppsubdivisionOwner = strtoupper($subdivisionOwner);
                if(!preg_match("/^[a-zA-Z0-9- ]*$/",$subdivisionOwner)){
                    $subdivisionOwner_error = "Only letters, numbers, dash and space are allowed";
                }
            }
            }else{
                $streetOwner_error = "Input atleast one of the following";
                $barangayOwner_error = "Input atleast one of the following";
                $subdivisionOwner_error = "Input atleast one of the following";
            }
        }
    //Tax Incentives from any Government Entity
        if(empty($_POST["incentive"])){
            $incentive_error = "Select your answer";
        }else{
            $incentive = validate($_POST["incentive"]);
            $uppincentive = strtoupper($incentive);
        } 
    //Business Activity
        if(empty($_POST["activity"])){
            $activity_error = "Choose your business activity";
        }else{
            $activity = validate($_POST["activity"]);
            $uppactivity = strtoupper($activity);
            if($activity=="Others"){
                if(empty($_POST["specify"])){
                    $specify_error = "Please Specify";
                }else{
                    $specify = validate($_POST["specify"]);
                    if(!preg_match("/^[a-zA-Z]+$/",$specify)){
                        $specify_error = "Only  letters are allowed";
                    }else{
                        $uppactivity = strtoupper($specify);
                    }
                }
            }
        } 
    // Line of Business
        if(empty($_POST["lineCount"])){
            $lineCount_error = "Input at least one Line of Business";
        }else{
            $lineCount = validate($_POST["lineCount"]); 
            
            for($i=0;$i<$lineCount;$i++){
                $count = $i+1;
                $lineName = "line-of-business".$count;
                if(empty($_POST[$lineName])){
                    $lineError[$i][0] = "Empty Line of Business";
                    $lineArray[$i][0] = "";
                }else{
                    $lineArray[$i][0] = validate($_POST[$lineName]);
                    $lineError[$i][0] = "";
                }

                $productName = "product-services".$count;
                if(empty($_POST[$productName])){
                    $lineError[$i][1] = "Empty Product/Services";
                    $lineArray[$i][1] = "";
                }else{
                    $lineArray[$i][1] = validate($_POST[$productName]);
                    $lineError[$i][1] = "";
                }

                $unitName = "no-of-units".$count;
                if(empty($_POST[$unitName])){
                    $lineError[$i][2] = "Empty No. of Units";
                    $lineArray[$i][2] = "";
                }else{
                    $lineArray[$i][2] = validate($_POST[$unitName]);
                    $lineError[$i][2] = "";
                }

                $capitalName = "total-capitalization".$count;
                if(empty($_POST[$capitalName])){
                    $lineError[$i][3] = "Empty Total Capitalization";
                    $lineArray[$i][3] = "";
                }else{
                    $lineArray[$i][3] = validate($_POST[$capitalName]);
                    $lineError[$i][3] = "";
                }
            }
        }
            //check for errors
            $is_empty = true;
                foreach ($lineError as $row) {
                foreach ($row as $cell) {
                    if (!empty($cell)) {
                    $is_empty = false;
                    break 2; // exit both loops
                    }
                }
                }

                if ($is_empty) {
                    $lineErrorCheck = "";
                } else {
                    $lineErrorCheck = "The array is not empty";
                }
    // ID - owner
        $idSQL_owner = "SELECT * FROM owner ORDER BY ID DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $idRESULT_owner = $conn->query($idSQL_owner);
        $idROW_owner = $idRESULT_owner->fetch_assoc();

        if(isset($idROW_owner["ID"])){
            $lastUserID_owner = $idROW_owner["ID"];
        }else{
            $lastUserID_owner = 0;
        }
        $idEND_owner = substr($lastUserID_owner,10,13);
        $idENDint_owner = (int)$idEND_owner;

        if(substr($lastUserID_owner,2,8)==date("Ymd")){
            ++$idENDint_owner;
            $idEnd_owner = str_pad($idENDint_owner,3,"0",STR_PAD_LEFT);
            $id_owner = "OW" . date("Ymd") . $idEnd_owner;
        }else{
            $id_owner = "OW" . date("Ymd") . "000";
        }

    // ID - business
        $idSQL_bn = "SELECT * FROM business ORDER BY ID DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $idRESULT_bn = $conn->query($idSQL_bn);
        $idROW_bn = $idRESULT_bn->fetch_assoc();

        if(isset($idROW_bn["ID"])){
            $lastUserID_bn = $idROW_bn["ID"];
        }else{
            $lastUserID_bn = 0;
        }
        $idEND_bn = substr($lastUserID_bn,10,13);
        $idENDint_bn = (int)$idEND_bn;

        if(substr($lastUserID_bn,2,8)==date("Ymd")){
            ++$idENDint_bn;
            $idEnd_bn = str_pad($idENDint_bn,3,"0",STR_PAD_LEFT);
            $id_bn = "BN" . date("Ymd") . $idEnd_bn;
        }else{
            $id_bn = "BN" . date("Ymd") . "000";
        }

    // ID - permit
        $idSQL_permit = "SELECT * FROM permit ORDER BY ID DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $idRESULT_permit = $conn->query($idSQL_permit);
        $idROW_permit = $idRESULT_permit->fetch_assoc();

        if(isset($idROW_permit["ID"])){
            $lastUserID_permit = $idROW_permit["ID"];
        }else{
            $lastUserID_permit = 0;
        }
        $idEND_permit = substr($lastUserID_permit,10,13);
        $idENDint_permit = (int)$idEND_permit;

        if(substr($lastUserID_permit,2,8)==date("Ymd")){
            ++$idENDint_permit;
            $idEnd_permit = str_pad($idENDint_permit,3,"0",STR_PAD_LEFT);
            $id_permit = "BP" . date("Ymd") . $idEnd_permit;
        }else{
            $id_permit = "BP" . date("Ymd") . "000";
        }
    
    // ID - line of business
        $idSQL_line = "SELECT * FROM lineofbusiness ORDER BY ID DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $idRESULT_line = $conn->query($idSQL_line);
        $idROW_line = $idRESULT_line->fetch_assoc();

        if(isset($idROW_line["ID"])){
            $lastUserID_line = $idROW_line["ID"];
        }else{
            $lastUserID_line = 0;
        }
        $idEND_line = substr($lastUserID_line,10,13);
        $idENDint_line = (int)$idEND_line;

        if(substr($lastUserID_line,2,8)==date("Ymd")){
            ++$idENDint_line;
            $idEnd_line = str_pad($idENDint_line,3,"0",STR_PAD_LEFT);
            $id_line = "LB" . date("Ymd") . $idEnd_line;
        }else{
            $id_line = "LN" . date("Ymd") . "000";
        }
    
    // insert to database
        $success = 0;

        if(empty($type_error) && empty($payment_error) && empty($business_error) && empty($dti_error) && empty($tin_error) && empty($business_name_error) && empty($franchise_error) && empty($houseNo_error) && empty($building_error) && empty($lotNo_error) && empty($blockNo_error) && empty($street_error) && empty($barangay_error) && empty($subdivision_error) && empty($owned_error) && empty($property_error) && empty($lessor_error) && empty($rent_error) && empty($telephone_error) && empty($mobile_error) && empty($gender_error) && empty($area_error) && empty($van_error) && empty($motorcycle_error) && empty($empTarlac_error) && empty($empMale_error) && empty($empFemale_error) && empty($houseNoOwner_error) && empty($buildingOwner_error) && empty($lotNoOwner_error) && empty($blockNoOwner_error) && empty($streetOwner_error) && empty($barangayOwner_error) && empty($subdivisionOwner_error)&& empty($incentive_error) && empty($activity_error) && empty($specify_error) && empty($lineCount_error) && empty($lineErrorCheck)){
        if(isset($permitROW["status"])){
            if($permitROW["status"]=="SUBMITTED"){
                $application  = "Duplicate";
                $message = "You have already submitted the application form";
            }else if($permitROW["status"]=="REJECTED"){
                echo "rejected";
            }else if($permitROW["status"]=="APPROVED"){
                $application  = "Success";
                $message = "Application Form has been approves";
            }}
            else{
            
            //owner
            $insertSQL_owner = "INSERT INTO owner (ID, gender, number, building, lot, block, street, barangay, subdivision, userID) VALUES ('$id_owner', '$uppgender','$houseNoOwner','$uppbuildingOwner','$lotNoOwner','$blockNoOwner','$uppstreetOwner','$uppbarangayOwner','$uppsubdivisionOwner','$IDuser')";
            //business
            $insertSQL_business = "INSERT INTO business (ID, name, tradeName, number, building, lot, block, street,barangay, subdivision, telephone, mobile, activity, ownerID) VALUES ('$id_bn','$uppbusiness_name','$uppfranchise','$houseNo','$uppbuilding','$lotNo','$blockNo','$uppstreet','$uppbarangay','$subdivision','$telephone','$mobile','$uppactivity','$id_owner')";
            // //permit
            $insertSQL_permit = "INSERT INTO permit (ID, registration, payment, business, certificateNo, tin, property, lessor, rent, incentive, area, vanTruck, motorcycle, empTarlac, empMale, empFemale, status, businessID) VALUES ('$id_permit','$type','$payment','$uppbusiness','$dti','$tin','$property','$upplessor','$rent','$uppincentive','$area','$van','$motorcycle','$empTarlac','$empMale','$empFemale','SUBMITTED','$id_bn')";
            // // line of business
            
            $stmt = mysqli_prepare($conn, "INSERT INTO lineofbusiness (ID, subclass, productServices, unit, capital, permitID) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssss", $valueID, $value1, $value2, $value3, $value4, $id_permit);

                $lineIdCount = 0;
            
            if($conn->query($insertSQL_owner) === TRUE){
               ++$success;
            }else{
                echo "error with owner";
            }
            if($conn->query($insertSQL_business) === TRUE){
                ++$success;
            }else{
                echo "error with business";
            }
            if($conn->query($insertSQL_permit) === TRUE){
                ++$success;
            }else{
                echo "error with permit";
            }
            foreach ($lineArray as $element) {
                
                ++$lineIdCount;
                $id_lineEND = str_pad($lineIdCount,2,"0",STR_PAD_LEFT);
                $lineID = $id_line.$id_lineEND;

                    $valueID = $lineID;
                    $value1 = strtoupper($element[0]);
                    $value2 = strtoupper($element[1]);
                    $value3 = $element[2];
                    $value4 = $element[3];
                    mysqli_stmt_execute($stmt);
                ++$success;
            }
                mysqli_stmt_close($stmt);

                if($success==$lineCount+3){
                    $application  = "Success";
                    $message = "You have successfully submitted the application form";
                }else{
                    $application  = "Error";
                    $message = "Error on submitting the application form";
                }
            }
        
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>Application Form</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">
</head>
<script>
        var application = "<?php echo $application; ?>";

        $(document).ready(function(){
            if(application=="Success"){
                $('#noticeModal').modal('show')
            }else if(application=="Duplicate"){
                $('#noticeModal').modal('show')
            }else if(application=="Error"){
                $('#noticeModal').modal('show')
            }       
        }); 
    </script>

<body class="bg-light">
    <div class="modal fade" id="noticeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">NOTICE</h5>
            </div>
            <div class="modal-body">
                <?php if($application == "Success"){
                echo '<div class="alert alert-success" role="alert">'.$message.'</div>';}
                else if($application == "Duplicate"){
                    echo '<div class="alert alert-warning" role="alert">'.$message.'</div>';}
                else if($application == "Error"){
                    echo '<div class="alert alert-danger" role="alert">'.$message.'</div>';}
                ?>
            </div>
            <div class="modal-footer">
                <a href="/user/dashboard.php" class="btn btn-secondary">OK</a>
            </div>
            </div>
        </div>
    </div>
  <!--Navigation Bar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="index.php"><img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">Tarlac City BPLS</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="panel.php">Business Permit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
           <?php if(!empty($_SESSION["name"])){
                echo    '<div class="mr-2">
                        <a href="/user/dashboard.php" class="btn btn-light"> Hi, '.$_SESSION["name"]  .'</a>
                        </div>';
                }   
            ?>
                <div class="my-2 my-sm-0">
                    <a href="/logout.php" class="btn btn-danger">Logout</a>
                </div>
        </div>
  </nav>

  <div class="container-fluid">
<div class="row">

    <div class="col-2"></div>

    <div class="col-md-8">
        <div class="card mt-5 shadow <?php echo ($rowbn["bn_name_status"]=="REJECTED")? 'd-none' : '' ;?>" id="formbody">
            <div class="card-header text-center" id="formHead">
                <h5 class="card-title m-0" id="formTitle">APPLICATION FORM </h5>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<ul class="list-group list-group-flush ">
    <!--Type of Registration--> 
        <li class="list-group-item">
            <div class="form-row">
                <label for="type" class="font-weight-bold pt-2 col-xl-3">Type of Registration <small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($type_error))? 'is-invalid' : '' ;?>" type="radio" name="type" id="type1" value="NEW" <?php if($type=="NEW"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="type1">NEW</label>
                </div>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($type_error))? 'is-invalid' : '' ;?>" type="radio" name="type" id="type2" value="RENEWAL" <?php if($type=="RENEWAL"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="type2">RENEWAL</label>
                </div>
            </div>
            <p class="text-danger"><?php echo $type_error?></p>
        </li>
    <!--Payment-->
        <li class="list-group-item">
            <div class="form-row">
                <label for="payment" class="font-weight-bold col-xl-3">Payment Schedule <small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($payment_error))? 'is-invalid' : '' ;?>" type="radio" name="payment" id="payment1" value="ANNUALLY" <?php if($payment=="ANNUALLY"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="payment1">ANNUALLY</label>
                </div>
                <div class="form-check form-check-inline col-lg-3">
                    <input class="form-check-input <?php echo(!empty($payment_error))? 'is-invalid' : '' ;?>" type="radio" name="payment" id="payment2" value="BI-ANNUALLY" <?php if($payment=="BI-ANNUALLY"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="payment2">BI-ANNUALLY</label>
                </div>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($payment_error))? 'is-invalid' : '' ;?>" type="radio" name="payment" id="payment3" value="QUARTERLY" <?php if($payment=="QUARTERLY"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="payment3">QUARTERLY</label>
                </div>
            </div>
            <p class="text-danger"><?php echo $payment_error;?></p>
        </li>
    <!--A. BUSINESS INFORMATION AND REGISTRATION-->    
        <li class="list-group-item">
            <h5>A. BUSINESS INFORMATION AND REGISTRATION</h5>
        </li>
    <!--Type of Business-->    
    <li class="list-group-item">
        <div class="form-row">
            <label for="business" class="font-weight-bold col-xl-3">Business Type</label>
            <div class="form-check form-check-inline col-lg-3">
                <input class="form-check-input <?php echo(!empty($business_error))? 'is-invalid' : '' ;?>" type="radio" name="business" id="business1" value="Sole Proprietorship" checked>
                <label class="form-check-label" for="business1">SOLE PROPRIETORSHIP</label>
            </div>
        </div>
        <p class="text-danger"><?php echo $business_error?></p>
    </li>
    <!--DTI and TIN-->
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dti">DTI Registration No. <small>Ex. (01234567)</small><small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($dti_error))? 'is-invalid' : '' ;?>" id="dti" name="dti" value="<?php echo $dti?>">
                    <div class="invalid-feedback"><?php echo $dti_error ?></div>
                </div>
                <div class="form-group col-xl-6">
                    <label for="tin">Tax Identification No.(TIN) <small class="text-muted">Ex. (012-345-678-900)</small></label>
                    <div class="input-group">
                        <input type="text" class="form-control <?php echo(!empty($tin_error))? 'is-invalid' : '' ;?>" id="tin1" name="tin1" placeholder="012" value="<?php echo $tin1 ?>" maxlength="3" oninput="moveToNext(this, 'tin2')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($tin_error))? 'is-invalid' : '' ;?>" id="tin2" name="tin2" placeholder="345" value="<?php echo $tin2 ?>" maxlength="3" oninput="moveToNext(this, 'tin3')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($tin_error))? 'is-invalid' : '' ;?>" id="tin3" name="tin3" placeholder="678" value="<?php echo $tin3 ?>" maxlength="3" oninput="moveToNext(this, 'tin4')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($tin_error))? 'is-invalid' : '' ;?>" id="tin4" name="tin4" placeholder="900" value="<?php echo $tin4 ?>" maxlength="3" oninput="moveToNext(this, 'tin4')">
                        <div class="invalid-feedback"><?php echo $tin_error; ?></div>
                        </div>
                </div>
            </div>
        </li>
    <!--Business Name-->
    <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-lg-6">
                    <label for="business_name">Business Name <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($business_name_error))? 'is-invalid' : '' ;?>" id="business_name" name="business_name" value="<?php echo $business_name?>">
                    <div class="invalid-feedback"><?php echo $business_name_error ?></div>
                </div>
                <div class="form-group col-lg-6">
                    <label for="franchise">Trade Name/Franchise<small> (if applicable)</small></label>
                    <input type="text" class="form-control <?php echo(!empty($franchise_error))? 'is-invalid' : '' ;?>" id="franchise" name="franchise" value="<?php echo $franchise?>">
                    <div class="invalid-feedback"><?php echo $franchise_error ?></div>
                </div>
            </div>
        </li>
    <!--Business Address-->
        <li class="list-group-item">
            <label for="businessAddress" class="font-weight-bold form-row">Business Address <small class="text-danger font-weight-bold"> *</small></label>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="houseNo">House/Bldg.No.</label>
                    <input type="text" class="form-control <?php echo(!empty($houseNo_error))? 'is-invalid' : '' ;?>" id="houseNo" name="houseNo" value="<?php echo $houseNo ?>">
                    <div class="invalid-feedback"><?php echo $houseNo_error ?></div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="building">Name of Building</label>
                    <input type="text" class="form-control <?php echo(!empty($building_error))? 'is-invalid' : '' ;?>" id="building" name="building" value="<?php echo $building ?>">
                    <div class="invalid-feedback"><?php echo $building_error; ?></div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="lotNo">Lot No.</label>
                    <input type="text" class="form-control <?php echo(!empty($lotNo_error))? 'is-invalid' : '' ;?>" id="lotNo" name="lotNo" value="<?php echo $lotNo ?>"><div class="invalid-feedback"><?php echo $lotNo_error ?></div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="blockNo">Block No.</label>
                    <input type="text" class="form-control <?php echo(!empty($blockNo_error))? 'is-invalid' : '' ;?>" id="blockNo" name="blockNo" value="<?php echo $blockNo ?>">
                    <div class="invalid-feedback"><?php echo $blockNo_error ?></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="street">Street</label>
                    <input type="text" class="form-control <?php echo(!empty($street_error))? 'is-invalid' : '' ;?>" id="street" name="street" value="<?php echo $street ?>">
                    <div class="invalid-feedback"><?php echo $street_error ?></div>
                </div>
                <div class="form-group col-lg-3">
                        <label for="barangay">Barangay</label>
                        <select  class="form-control <?php echo(!empty($barangay_error))? 'is-invalid' : '' ;?>" name="barangay" id="barangay">
                            <option value="" selected disabled>Choose...</option>
                            <?php
                            $barangayList = array("Aguso","Alvindia","Amucao","Armenia","Asturias","Atioc","Balanti","Balete","Balibago I","Balibago II","Balingcanaway","Banaba","Bantog","Baras-baras","Batang-batang","Binauganan","Bora","Buenavista","Buhilit","Burot","Calingcuan","Capehan","Carangian","Care","Central","Culipat","Cut-cut I","Cut-cut II","Dalayap","Dela Paz","Dolores","Laoang","Ligtasan","Lourdes","Mabini","Maligaya","Maliwalo","Mapalacsiao","Mapalad","Matatalaib","Paraiso","Poblacion","Salapungan","San Carlos","San Francisco","San Isidro","San Jose","San Jose de Urquico","San Juan Bautista","San Juan de Mata","San Luis","San Manuel","San Miguel","San Nicolas","San Pablo","San Pascual","San Rafael","San Roque","San Sebastian","San Vicente","Santa Cruz","Santa Maria","Santo Cristo","Santo Domingo","Santo Niño","Santo Rosario","Sapang Maragul","Sapang Tagalog","Sepung Calzada","Sinait","Suizo","Tariji","Tibag","Tibagan","Trinidad","Ungot","Villa Bacolor");
                            foreach($barangayList as $name){
                                echo "<option value='$name'" ?>
                                <?php if($barangay=="$name"){ echo ' selected="selected"';}
                                echo ">$name</option>";
                            }?>
                        </select>
                    <div class="invalid-feedback"><?php echo $barangay_error ?></div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="subdivision">Subdivision</label>
                    <input type="text" class="form-control <?php echo(!empty($subdivision_error))? 'is-invalid' : '' ;?>" id="subdivision" name="subdivision" value="<?php echo $subdivision ?>">
                    <div class="invalid-feedback"><?php echo $subdivision_error ?></div>
                </div>
                <div class="form-group col-lg-3">
                    <label for="city">City/Municipality</label>
                    <input type="text" class="form-control" id="city" name="city" value="Tarlac City" readonly>
                </div>
            </div>
    <!--Owned -->
            <div class="form-row">             
                <label for="owned" class="col-lg-3">Owned? <small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-lg-3">
                    <input class="form-check-input  <?php echo(!empty($owned_error))? 'is-invalid' : '' ;?>" type="radio" name="owned" id="owned1" value="Yes" <?php if($owned=="Yes"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="owned1">Yes</label>
                </div>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($owned_error))? 'is-invalid' : '' ;?>" type="radio" name="owned" id="owned2" value="No" <?php if($owned=="No"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="owned2">No</label>
                </div>
                <p class="text-danger"><?php echo $owned_error?></p>
            </div>
            <div class="form-row d-none" id="ownedYES">
                <div class="form-group col-lg-6">
                    <label for="property">Tax Declaration No./Property Identification No. <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($property_error))? 'is-invalid' : '' ;?>" id="property" name="property" value="<?php echo $property ?>">
                    <div class="invalid-feedback"><?php echo $property_error ?></div>
                </div>  
            </div>
            <div class="form-row d-none" id="ownedNO">
                <div class="form-group col-lg-6">
                    <label for="lessor">Lessor Name <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($lessor_error))? 'is-invalid' : '' ;?>" id="lessor" name="lessor" value="<?php echo $lessor ?>">
                    <div class="invalid-feedback"><?php echo $lessor_error ?></div>
                </div>  
                <div class="form-group col-lg-6">
                    <label for="rent">Monthly Rental <small class="text-danger font-weight-bold">*</small></label>
                    <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">₱</span>
                            </div>
                    <input type="text" class="form-control <?php echo(!empty($rent_error))? 'is-invalid' : '' ;?>" id="rent" name="rent" value="<?php echo $rent ?>">
                    <div class="invalid-feedback"><?php echo $rent_error ?></div>
                    </div>  
                </div>  
            </div>                  
        </li>
    <!--Telephone and Mobile Number-->     
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-xl-6">
                    <label for="telephone">Telephone No. <small class="text-muted">(Ex. 123-4567)</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">[045]</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($telephone_error))? 'is-invalid' : '' ;?>" id="telephone1" name="telephone1" placeholder="123" value="<?php echo $telephone1 ?>" maxlength="3" oninput="moveToNext(this, 'telephone2')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($telephone_error))? 'is-invalid' : '' ;?>" id="telephone2" name="telephone2" placeholder="4567" value="<?php echo $telephone2 ?>" maxlength="4" oninput="moveToNext(this, 'telephone2')">
                        <div class="invalid-feedback"><?php echo $telephone_error; ?></div>
                        </div>
                </div>
                <div class="form-group col-xl-6">
                    <label for="mobile">Mobile No. <small class="text-muted">(Ex. 912-345-6789)</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">+63</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($mobile_error))? 'is-invalid' : '' ;?>" id="mobile1" name="mobile1" placeholder="912" value="<?php echo $mobile1 ?>" maxlength="3" oninput="moveToNext(this, 'mobile2')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($mobile_error))? 'is-invalid' : '' ;?>" id="mobile2" name="mobile2" placeholder="345" value="<?php echo $mobile2 ?>" maxlength="3" oninput="moveToNext(this, 'mobile3')">
                        <span class="mx-2">-</span>
                        <input type="text" class="form-control <?php echo(!empty($mobile_error))? 'is-invalid' : '' ;?>" id="mobile3" name="mobile3" placeholder="6789" value="<?php echo $mobile3 ?>" maxlength="4" oninput="moveToNext(this, 'mobile3')">
                        <div class="invalid-feedback"><?php echo $mobile_error; ?></div>
                        </div>
                </div>
            </div> 
    </li>
    <!--Name of Owner-->
        <li class="list-group-item">
            <label for="name" class="font-weight-bold form-row">For Sole Proprietorship</label>
            <label for="name" >Name of Owner</label>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="surname">Surname</label>
                    <input type="text" class="form-control" id="surname" value="<?php echo $userROW["last"]?>" disabled>
                </div>
                <div class="form-group col-lg-3">
                    <label for="given">Given Name</label>
                    <input type="text" class="form-control" id="given" value="<?php echo $userROW["first"]?>" disabled>
                </div>
                <div class="form-group col-lg-3">
                    <label for="middle">Middle Name</label>
                    <input type="text" class="form-control" id="middle" value="<?php echo $userROW["middle"]?>" disabled>
                </div>
                <div class="form-group col-lg-3">
                    <label for="suffix">Suffix</label>
                    <input type="text" class="form-control" id="suffix" value="<?php echo $userROW["suffix"] ?>" disabled>
                </div>
            </div>

            <div class="form-row">             
                <label for="gender" class="col-lg-3">Gender <small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-lg-3">
                    <input class="form-check-input  <?php echo(!empty($gender_error))? 'is-invalid' : '' ;?>" type="radio" name="gender" id="gender1" value="Male" <?php if($gender=="Male"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="gender1">Male <i class="bi bi-gender-male"></i></label>
                </div>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($gender_error))? 'is-invalid' : '' ;?>" type="radio" name="gender" id="gender2" value="Female" <?php if($gender=="Female"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="gender2">Female <i class="bi bi-gender-female"></i></label>
                </div>
                <p class="text-danger"><?php echo $gender_error?></p>
            </div>                   
    </li>
    <!--B. BUSINESS OPERATION-->    
        <li class="list-group-item">
                <h5>B. BUSINESS OPERATION</h5>
        </li>
    <!--Business Area Employees Vehicles-->
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-xl-4">
                    <label for="area">Business Area <small>(in sq.m)</small><small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($area_error))? 'is-invalid' : '' ;?>" id="area" name="area" value="<?php echo $area ?>">
                    <div class="invalid-feedback"><?php echo $area_error; ?></div>
                </div>
                <div class="form-group col-xl-4">
                    <label for="van">No. of Delivery Vehicles <small>(if applicable)</small><small class="text-danger font-weight-bold">*</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Van/Truck</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($van_error))? 'is-invalid' : '' ;?>" id="van" name="van" value="<?php echo $van ?>">
                        <div class="invalid-feedback"><?php echo $van_error; ?></div>
                        </div>
                </div>
                <div class="form-group col-xl-4">
                    <label for="motorcycle" class="invisible d-none d-xl-block">Label </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Motorcycle</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($motorcycle_error))? 'is-invalid' : '' ;?>" id="motorcycle" name="motorcycle" value="<?php echo $motorcycle ?>">
                        <div class="invalid-feedback"><?php echo $motorcycle_error; ?></div>
                        </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-xl-5">
                    <label for="empTarlac">No. of Employees residing within Tarlac City <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" class="form-control <?php echo(!empty($empTarlac_error))? 'is-invalid' : '' ;?>" id="empTarlac" name="empTarlac" value="<?php echo $empTarlac ?>">
                    <div class="invalid-feedback"><?php echo $empTarlac_error; ?></div>
                </div>
                <div class="form-group col-xl-4">
                    <label for="empMale">Total No. of Employees in Establishment <small class="text-danger font-weight-bold">*</small></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Male</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($empMale_error))? 'is-invalid' : '' ;?>" id="empMale" name="empMale" value="<?php echo $empMale ?>">
                        <div class="invalid-feedback"><?php echo $empMale_error; ?></div>
                        </div>
                </div>
                <div class="form-group col-xl-3">
                    <label for="empFemale" class="invisible d-none d-xl-block">Label </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3">Female</span>
                            </div>
                        <input type="text" class="form-control <?php echo(!empty($empFemale_error))? 'is-invalid' : '' ;?>" id="empFemale " name="empFemale" value="<?php echo $empFemale ?>">
                        <div class="invalid-feedback"><?php echo $empFemale_error; ?></div>
                        </div>
                </div>
            </div>
        </li>
    <!--Taxpayers Address--> 
        <li class="list-group-item">
            <div class="form-row">
            <label for="ownerAddress" class="font-weight-bold col-lg-3" id="labelTaxpayer">Taxpayer's Address<small class="text-danger font-weight-bold"> *</small></label>
            <div class="form-group form-check col-lg-3">
                <input type="checkbox" class="form-check-input" name="disableForm" id="disableForm" value="Yes" onclick="disable()" <?php if($disableForm=="Yes"){ echo ' checked="checked"';} ?>>
                <label class="form-check-label font-italic" for="disableForm">Same as Business Address</label>
            </div>
            </div>
            <fieldset id="addressOwner">
                <div class="form-row">
                    <div class="form-group col-lg-3">
                        <label for="houseNoOwner">House/Bldg.No.</label>
                        <input type="text" class="form-control <?php echo(!empty($houseNoOwner_error))? 'is-invalid' : '' ;?>" id="houseNoOwner" name="houseNoOwner" value="<?php echo $houseNoOwner ?>">
                        <div class="invalid-feedback"><?php echo $houseNoOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="buildingOwner">Name of Building</label>
                        <input type="text" class="form-control <?php echo(!empty($buildingOwner_error))? 'is-invalid' : '' ;?>" id="buildingOwner" name="buildingOwner" value="<?php echo $buildingOwner ?>">
                        <div class="invalid-feedback"><?php echo $buildingOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="lotNoOwner">Lot No.</label>
                        <input type="text" class="form-control <?php echo(!empty($lotNoOwner_error))? 'is-invalid' : '' ;?>" id="lotNoOwner" name="lotNoOwner" value="<?php echo $lotNoOwner ?>">
                        <div class="invalid-feedback"><?php echo $lotNoOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="blockNoOwner">Block No.</label>
                        <input type="text" class="form-control <?php echo(!empty($blockNoOwner_error))? 'is-invalid' : '' ;?>" id="blockNoOwner" name="blockNoOwner" value="<?php echo $blockNoOwner ?>">
                        <div class="invalid-feedback"><?php echo $blockNoOwner_error; ?></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-3">
                        <label for="streetOwner">Street</label>
                        <input type="text" class="form-control <?php echo(!empty($streetOwner_error))? 'is-invalid' : '' ;?>" id="streetOwner" name="streetOwner" value="<?php echo $streetOwner ?>">
                        <div class="invalid-feedback"><?php echo $streetOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                            <label for="barangayOwner">Barangay</label>
                            <select  class="form-control <?php echo(!empty($barangayOwner_error))? 'is-invalid' : '' ;?>" name="barangayOwner" id="barangayOwner">
                                <option value="" selected disabled>Choose...</option>
                                <?php
                                $barangayList = array("Aguso","Alvindia","Amucao","Armenia","Asturias","Atioc","Balanti","Balete","Balibago I","Balibago II","Balingcanaway","Banaba","Bantog","Baras-baras","Batang-batang","Binauganan","Bora","Buenavista","Buhilit","Burot","Calingcuan","Capehan","Carangian","Care","Central","Culipat","Cut-cut I","Cut-cut II","Dalayap","Dela Paz","Dolores","Laoang","Ligtasan","Lourdes","Mabini","Maligaya","Maliwalo","Mapalacsiao","Mapalad","Matatalaib","Paraiso","Poblacion","Salapungan","San Carlos","San Francisco","San Isidro","San Jose","San Jose de Urquico","San Juan Bautista","San Juan de Mata","San Luis","San Manuel","San Miguel","San Nicolas","San Pablo","San Pascual","San Rafael","San Roque","San Sebastian","San Vicente","Santa Cruz","Santa Maria","Santo Cristo","Santo Domingo","Santo Niño","Santo Rosario","Sapang Maragul","Sapang Tagalog","Sepung Calzada","Sinait","Suizo","Tariji","Tibag","Tibagan","Trinidad","Ungot","Villa Bacolor");
                                foreach($barangayList as $name){
                                    echo "<option value='$name'" ?>
                                    <?php if($barangay=="$name"){ echo ' selected="selected"';}
                                    echo ">$name</option>";
                                }?>
                            </select>
                            <div class="invalid-feedback"><?php echo $barangayOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="subdivisionOwner">Subdivision</label>
                        <input type="text" class="form-control <?php echo(!empty($subdivisionOwner_error))? 'is-invalid' : '' ;?>" id="subdivisionOwner" name="subdivisionOwner" value="<?php echo $subdivisionOwner ?>">
                        <div class="invalid-feedback"><?php echo $subdivisionOwner_error; ?></div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="cityOwner">City/Municipality</label>
                        <input type="text" class="form-control" id="cityOwner" name="cityOwner" value="Tarlac City" disabled>
                    </div>
                </div>
            </fieldset>

            <div class="form-row">             
                <label for="incentive" class="col-lg-5">Tax Incentives from any Government Entity <small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-lg-3">
                    <input class="form-check-input  <?php echo(!empty($incentive_error))? 'is-invalid' : '' ;?>" type="radio" name="incentive" id="incentive1" value="Yes" <?php if($incentive=="Yes"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="incentive1">Yes</label>
                </div>
                <div class="form-check form-check-inline col-lg-2">
                    <input class="form-check-input <?php echo(!empty($incentive_error))? 'is-invalid' : '' ;?>" type="radio" name="incentive" id="incentive2" value="No" <?php if($incentive=="No"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="incentive2">No</label>
                </div>
                <p class="text-danger"><?php echo $incentive_error?></p>
            </div> 
            <div class="form-row">             
                <label for="activity" class="col-xl-2">Business Activity<small class="text-danger font-weight-bold">*</small></label>
                <div class="form-check form-check-inline col-xl-2">
                    <input class="form-check-input  <?php echo(!empty($activity_error))? 'is-invalid' : '' ;?>" type="radio" name="activity" id="activity1" onclick="others()" value="Main Office" <?php if($activity=="Main Office"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="activity1">Main Office</label>
                </div>
                <div class="form-check form-check-inline col-xl-2">
                    <input class="form-check-input <?php echo(!empty($activity_error))? 'is-invalid' : '' ;?>" type="radio" name="activity" id="activity2" onclick="others()" value="Branch Office" <?php if($activity=="Branch Office"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="activity2">Branch Office</label>
                </div>
                <div class="form-check form-check-inline col-xl-2">
                    <input class="form-check-input <?php echo(!empty($activity_error))? 'is-invalid' : '' ;?>" type="radio" name="activity" id="activity3" onclick="others()" value="Admin Office" <?php if($activity=="Admin Office"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="activity3">Admin Office</label>
                </div>
                <div class="form-check form-check-inline col-xl-2">
                    <input class="form-check-input <?php echo(!empty($activity_error))? 'is-invalid' : '' ;?>" type="radio" name="activity" id="activity4"  onclick="others()"value="Warehouse" <?php if($activity=="Warehouse"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="activity4">Warehouse</label>
                </div>
                <div class="form-check form-check-inline col-xl-1">
                    <input class="form-check-input <?php echo(!empty($activity_error))? 'is-invalid' : '' ;?>" type="radio" name="activity" id="activity5" onclick="others()" value="Others" <?php if($activity=="Others"){ echo ' checked="checked"';} ?>>
                    <label class="form-check-label" for="activity5">Others</label>
                </div>
            </div>  
            <p class="text-danger mb-0"><?php echo $activity_error?></p>
            <div class="form-row d-none" id="specify">
                    <div class="form-group col-lg-3">
                        <label for="specify" class="font-italic">Please Specify</label>
                        <input type="text" class="form-control <?php echo(!empty($specify_error))? 'is-invalid' : '' ;?>" id="specify" name="specify" value="<?php echo $specify ?>">
                        <div class="invalid-feedback"><?php echo $specify_error; ?></div>
                    </div>
                </div>     
        </li>         
    
    <!--Philippine Standard Industrial Classification-->
    <li class="list-group-item">
        <label for="section" class="font-weight-bold form-row">Philippine Standard Industrial Classification</label>
        <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="section">Section <small class="text-danger font-weight-bold">*</small></label>
                    <select  class="form-control" name="section" id="section">
                            <option value="" disabled selected>Select Section</option>';
                            <?php if($psicRESULT->num_rows > 0){
                                    while($row = $psicRESULT->fetch_assoc()){
                                        echo "<option value='".$row["PSIC_SEC_DESC"]."'>".$row["PSIC_SEC_DESC"]."</option>";
                                    }}else{
                                        echo '<option value="">None</option>';}?>
                    </select>
            </div>
            <div class="form-group col-md-6">
                    <label for="division">Division <small class="text-danger font-weight-bold">*</small></label>
                    <select  class="form-control" name="division" id="division">
                        <option value="" disabled selected>Select Section first</option>
                    </select>
            </div>
        </div>
        <div class="form-row">  
            <div class="form-group col-md-6">
                    <label for="group">Group <small class="text-danger font-weight-bold">*</small></label>
                    <select  class="form-control" name="group" id="group">
                        <option value="" disabled selected>Select Division first</option>
                    </select>
            </div>
            <div class="form-group col-md-6">
                    <label for="class">Class <small class="text-danger font-weight-bold">*</small></label>
                    <select  class="form-control" name="class" id="class">
                        <option value="" disabled selected>Select Group first</option>
                    </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="subclass">Subclass <small class="text-danger font-weight-bold">*</small></label>
                    <select  class="form-control" name="subclass" id="subclass">
                        <option value="" disabled selected>Select Class first</option>
                    </select>
            </div>
            <div class="col-md-6 ">
                <div class="row pl-3 ">
                    <p class="mb-2">Line Of Business <small>(Select a Subclass before adding)</small> </p>
                </div>
                <div class="form-row">
                    <button type="button" class="btn btn-success mx-2" id="addROW">Add</button>
                    <button type="button" class="btn btn-danger mx-2" id="deleteROW">Remove</button>
                </div>
                <p class="text-danger my-2" id="lineError"></p>
            </div>
        </div>

    <!-- Line Of Business -->
    
        <table class="table table-striped" id="lineTable">
            <thead class="thead-light">
                <tr>
                <th scope="col">Line of Business</th>
                <th scope="col">Product/Services</th>
                <th scope="col">No. of Units</th>
                <th scope="col">Total Capitalization (₱)</th>
                </tr>
            </thead>
            <tbody id="lineBody">
            </tbody>
        </table>
        <p class="text-danger text-center my-2"><?php echo $lineCount_error ?></p>
        <input type="text" class="form-control d-none" id="lineCount" name="lineCount">
    </li>
</ul>
    </div>
                <div class="card-footer text-center" id="formFoot">
                    <input type="submit" class="btn btn-warning" name="formSubmit"  value="Submit">
                </div>
            </form>  
        </div>

    </div>

    <div class="col-2">
       
        <button type="button" id="annotationBTN" class="btn btn-danger sticky-top mt-5 d-none" data-toggle="modal" data-target="#annotation">
        Annotation
        </button>

       
        <div class="modal fade" id="annotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Annotations</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert"><?php echo $rowbn["annotation"]; ?></div>
            </div>

            </div>
        </div>
        </div>
    </div>

</div>
</div>
<script>
    // disable tax payer address
        function disable(){
        var check = document.getElementById("disableForm").checked;
        var form = document.getElementById("addressOwner");
            if(check){
                form.disabled = true;
            }else{
                form.disabled = false;
            }
        }
        disable();
</script>
<script>
   $(document).ready(function() {
    //owned property or not
        function owned(){
        var ownedSelected = $("input[type='radio'][name='owned']:checked").val();
        var yes = document.getElementById("ownedYES");
        var no = document.getElementById("ownedNO");
            if(ownedSelected=="Yes"){
                yes.classList.remove("d-none");
                no.classList.add("d-none");
            }else if(ownedSelected=="No"){
                yes.classList.add("d-none");
                no.classList.remove("d-none");
            }
        }
        $("input[name='owned']").on("change", owned);
        owned();
        });
</script>
<script>
    // input with dashes
        function moveToNext(current, next) {
            if (current.value.length == current.maxLength) {
            document.getElementById(next).focus();
            }
        }
</script>
<script>
    //others please specicy
    function others(){
        var other = $("input[type='radio'][name='activity']:checked").val();
        var specifyRow = document.getElementById("specify");
            if(other=="Others"){
                specifyRow.classList.remove("d-none");
            }else{
                specifyRow.classList.add("d-none");
            }
        }
        others();
</script>
<script>
    // psic
    $(document).ready(function(){
        //SECTION-Division
        $('#section').on('change', function(){
            var section = $(this).val();
            if(section){
                $.ajax({
                    type:'POST',
                    url:'/ajax/psicAjax.php',
                    data:'section='+section,
                    success:function(html){
                        $('#division').html(html); 
                        $('#group').html('<option value="">Select Divison first</option>'); 
                        $('#class').html('<option value="">Select Group first</option>'); 
                        $('#subclass').html('<option value="">Select Class first</option>'); 
                    }
                }); 
            }else{
                $('#division').html('<option value="">11111</option>');
                $('#group').html('<option value="">Select Division first</option>'); 
                $('#class').html('<option value="">Select Group first</option>'); 
                $('#subclass').html('<option value="">Select Class first</option>'); 
            }
        });
        //Divion - Group
        $('#division').on('change', function(){
            var division = $(this).val();
            if(division){
                $.ajax({
                    type:'POST',
                    url:'/ajax/psicAjax.php',
                    data:'division='+division,
                    success:function(html){
                        $('#group').html(html);
                        $('#class').html('<option value="">Select Group first</option>'); 
                        $('#subclass').html('<option value="">Select Class first</option>'); 
                    }
                }); 
            }else{
                $('#group').html('<option value="">Select Division first</option>'); 
                $('#class').html('<option value="">Select Group first</option>'); 
                $('#subclass').html('<option value="">Select Class first</option>'); 
            }
        });
        //Group- Class
        $('#group').on('change', function(){
            var group = $(this).val();
            if(group){
                $.ajax({
                    type:'POST',
                    url:'/ajax/psicAjax.php',
                    data:'group='+group,
                    success:function(html){
                        $('#class').html(html);
                        $('#subclass').html('<option value="">Select Class first</option>'); 
                    }
                }); 
            }else{
                $('#class').html('<option value="">Select Group first</option>'); 
                $('#subclass').html('<option value="">Select Class first</option>'); 
            }
        });
        //Class- Subclass
        $('#class').on('change', function(){
            var classV = $(this).val();
            if(classV){
                $.ajax({
                    type:'POST',
                    url:'/ajax/psicAjax.php',
                    data:'class='+classV,
                    success:function(html){
                        $('#subclass').html(html);
                    }
                }); 
            }else{
                $('#subclass').html('<option value="">Select Class first</option>'); 
            }
        });
    });
</script>
<script>
    // Line of Business Table Add and Remove
        function addTableRow() {
            var subclass = $("#subclass").val();
            
            if(subclass){
                $("#lineError").text("");
                var rowCount = $("#lineTable tbody tr").length;
                $("#lineTable tbody").append(
                "<tr>" +
                "<td><input class='form-control d-none' type='text' name='line-of-business" + (rowCount + 1) + "' value='" + subclass + "' ><input class='form-control' type='text' value='" + subclass + "' disabled></td>" +
                "<td><input class='form-control' type='text' name='product-services" + (rowCount + 1) + "'></td>" +
                "<td><input class='form-control' type='text' name='no-of-units" + (rowCount + 1) + "'></td>" +
                "<td><input class='form-control' type='text' name='total-capitalization" + (rowCount + 1) + "'></td>" +
                "</tr>"
                );
                $("#lineCount").val(rowCount+1);
            }else {
                $("#lineError").text("Subclass is empty");
            }
        }
                $("#addROW").click(addTableRow);

            function deleteTableRow(event) {
                var tr = document.getElementById("lineBody");
                tr.removeChild(tr.lastElementChild);
            }
                $("#deleteROW").click(deleteTableRow);
</script>
<script>
    var data = <?php echo json_encode($lineArray);?>;
    var error = <?php echo json_encode($lineError);?>;

    function loadTableRows() {

        for (var i = 0; i < data.length; i++) {

    $("#lineTable tbody").append(
      "<tr>" +
        "<td><input class='form-control d-none' type='text' name='line-of-business" + (i + 1) + "' value='"+ data[i][0] +"'>"
        +"<input class='form-control' type='text' value='"+ data[i][0] +"' disabled><p class='text-danger ml-2'>"+ error[i][0] +"</p></td>" +
        "<td><input class='form-control' type='text' name='product-services" + (i + 1) + "' value='"+ data[i][1] +"'><p class='text-danger ml-2'>"+ error[i][1] +"</p></td>" +
        "<td><input class='form-control' type='text' name='no-of-units" + (i + 1) + "' value='"+ data[i][2] +"'><p class='text-danger ml-2'>"+ error[i][2] +"</p></td>" +
        "<td><input class='form-control' type='text' name='total-capitalization" + (i + 1) + "' value='"+ data[i][3] +"'><p class='text-danger ml-2'>"+ error[i][3] +"</p></td>" +
      "</tr>"
    );
    $("#lineCount").val(i+1);
  }
}
loadTableRows();

</script>
</body>
</html>