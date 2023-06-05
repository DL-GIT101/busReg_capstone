<?php
session_start();
require_once "/opt/lampp/htdocs/busReg_capstone/php/connection.php";

if(isset($_GET['id'])){
    $user_id = $_SESSION['user_id'] =  urldecode($_GET['id']);
}else if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    header("location: ../msme_management.php");
}


$file = urldecode($_GET['file']);
$filePath = '../user/upload/'.$user_id.'/'.$file;
 
    $sql = "SELECT * FROM new_documents WHERE user_id = ?";
   
    if($stmt = $mysqli->prepare($sql)){
        
        $stmt->bind_param("s",$param_id);
        
        $param_id = validate($user_id);
       
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
           
               $row = $result->fetch_array(MYSQLI_ASSOC);

                $serialized_requirements = $row["requirements"];
                $serialized_status = $row["status"];
                $requirements = unserialize($serialized_requirements);
                $status = unserialize($serialized_status);

                $index = array_search($file, $requirements);
                
                $requirements[$index] = null;
                $status[$index] = '-';

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
        $sql = "UPDATE new_documents SET requirements = ?, status = ? WHERE user_id = ?";

        $serialized_requirements = serialize($requirements);
        $serialized_status = serialize($status);

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param('sss',$param_req, $param_Stat, $param_id);

            $param_id = validate($user_id);
            $param_req = $serialized_requirements;
            $param_Stat = $serialized_status;

            if($stmt->execute()){
                header("location: ../msme_documents.php");
            }else{
                echo "error";
            }
            $stmt->close();
        }
    }
    $mysqli->close();

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        return $data;
}
?>