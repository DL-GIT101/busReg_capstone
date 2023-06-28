<?php

function validate($data) {
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        
    return $data;
}

function hasProfile($id){

    global $mysqli;

    $sql = "SELECT * FROM user_profile WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){
                return true;
            }else {
                return false;
            }

        }else{
            return "Error";
        }

    $stmt->close();

    }else {

        return "Error";
        $stmt->close();
    }
}

function checkPermit($id){
    global $mysqli;

    $sql = "SELECT status FROM permit WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = $id;

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                $status = $row['status'];
                return $status;
            }else{
                return "None";
            }
        }else{
            return "Error";
        }

    $stmt->close();

    }else {

        return "Error";
        $stmt->close();
    }

}
?>