<?php

function validate($data) {
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        
    return $data;
}

function checkRole($role) {

    if($role === "admin"){
        return "admin";
    }elseif($role === "user"){
        return "user";
    }else{
        header("location: /opt/lampp/htdocs/busReg_capstone/index.php");
    }
}

?>