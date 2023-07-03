<?php

function validate($data) {
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        
    return $data;
}

function hasOwnerProfile($id){

    global $mysqli;

    $sql = "SELECT * FROM Owner WHERE UserID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_assoc();
                if($_SESSION["role"] === "Admin"){
                    return $row['OwnerID'];
                }else{
                    $_SESSION["OwnerID"] = $row['OwnerID'];
                    return true;
                }
                
            }else {
                return false;
            }

        }else{
            return "Error";
        }

    }else {
        return "Error";
    }

    $stmt->close();
}

function hasBusinessProfile($id){

    global $mysqli;

    $sql = "SELECT * FROM Business WHERE OwnerID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_assoc();
                $businessID = $row['BusinessID'];
                $_SESSION["BusinessID"] = $businessID;

                return true;
            }else {
                return false;
            }

        }else{
            return "Error";
        }

    }else {
        return "Error";
    }
    
    $stmt->close();
}

function checkPermit($id){
    global $mysqli;

    $sql = "SELECT * FROM Permit WHERE BusinessID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){
                return "Issued";
            }else{
                return "None";
            }
        }else{
            return "Error";
        }
    }else {
        return "Error";
    }
    $stmt->close();
}

function hasAdminProfile($id){

    global $mysqli;

    $sql = "SELECT * FROM Admin WHERE UserID = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){

                $row = $result->fetch_assoc();
                $_SESSION["AdminID"] = $row['AdminID'];
                $_SESSION["AdminRole"] = $row['Role'];

                return true;
            }else {
                return false;
            }

        }else{
            return "Error";
        }

    }else {
        return "Error";
    }

    $stmt->close();
}
?>