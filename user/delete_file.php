<?php
session_start();
require_once "../php/config.php";

if (isset($_GET['file']) && isset($_GET['count'])) {

    $file = urldecode($_GET['file']);
    $filePath = 'upload/'.$_SESSION['id'].'/'.$file;

    $count = urldecode($_GET['count']);

    $sql = "SELECT * FROM new_permit WHERE user_id = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        
        $stmt->bind_param("s",$param_id);
        
        $param_id = validate($_SESSION['id']);
       
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
           
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_requirements = $row["requirements"];
                $serialized_status = $row["status"];
                $requirements = unserialize($serialized_requirements);
                $status = unserialize($serialized_status);
                
                $requirements[$count-1] = null;
                $status[$count-1] = '-';

                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                      //  echo "File deleted successfully.";
                    } else {
                      //  echo "Error deleting file.";
                    }
                } else {
                   // echo "File not found.";
                }
            }
        }else {
            $error = "yes";
        }
    }
    $stmt->close();

    if($error !== "yes"){
        $sql = "UPDATE new_permit SET requirements = ?, status = ? WHERE user_id = ?";

        $serialized_requirements = serialize($requirements);
        $serialized_status = serialize($status);

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('ss',$param_req, $param_Stat, $param_id);

            $param_id = validate($_SESSION['id']);
            $param_req = $serialized_requirements;
            $param_Stat = $serialized_status;

            if($stmt->execute()){
                header("location: permit.php");
            }else{
                echo "error";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>