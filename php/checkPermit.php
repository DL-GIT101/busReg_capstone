<?php

function checkPermit($mysqli){

    $param_id = "";

    $sql = "SELECT status FROM permit WHERE user_id = ?";

    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("s",$param_id);

        $param_id = validate($_SESSION['id']);

        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows === 1){
                $row = $result->fetch_assoc();
                $status = $row['status'];
                return $status;
            }else{
                return null;
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }

    $stmt->close();

    }
}
?>