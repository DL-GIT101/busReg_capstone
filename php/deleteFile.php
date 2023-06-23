<?php
ini_set('display_errors', 1);
session_start();

require_once "connection.php";
require_once "functions.php";

if(checkRole($_SESSION["role"]) === "admin"){
    $id = validate($_SESSION['user_id']);
    $link = "../admin/management/documents.php";
}elseif(checkRole($_SESSION["role"]) === "user"){
    $id = validate($_SESSION['id']);
    $link = "../user/documents.php";
}else{
    $error = "yes";
}

if(checkPermit($id) === "None"){

    if (isset($_GET['file'])) {

        $file = urldecode($_GET['file']);
        $filePath = '../user/upload/'.$id.'/'.$file;
    
        $sql = "SELECT * FROM new_documents WHERE user_id = ?";
    
        if($stmt = $mysqli->prepare($sql)){
            
            $stmt->bind_param("s",$param_id);
            
            $param_id = validate($id);
        
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result->num_rows == 1){
            
                $row = $result->fetch_array(MYSQLI_ASSOC);

                    $serialized_requirements = $row["requirements"];
                    $serialized_status = $row["status"];
                    $requirements = unserialize($serialized_requirements);
                    $status = unserialize($serialized_status);

                    $index = array_search($file, $requirements);
                    
                    if (file_exists($filePath)) {
                        if (unlink($filePath)) {
                            $requirements[$index] = null;
                            $status[$index] = null;
                        } else {
                            $error = "yes";
                        }
                    } else {
                        $error = "yes";
                    }
                }
            }else {
                $error = "yes";
            }

            $stmt->close();

        } else{

            $error = "yes";
        }
        

        if($error !== "yes"){
            $sql = "UPDATE new_documents SET requirements = ?, status = ? WHERE user_id = ?";

            $serialized_requirements = serialize($requirements);
            $serialized_status = serialize($status);

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('sss',$param_req, $param_Stat, $param_id);

                $param_id = validate($id);
                $param_req = $serialized_requirements;
                $param_Stat = $serialized_status;

                if($stmt->execute()){
                    $message = '<modal>
                                    <div class="content success">
                                        <p class="title">File Deleted</p>
                                        <p class="sentence">You can now upload another file</p>
                                        <div class="button-group">
                                            <button class="close">Close</button>    
                                        </div>
                                    </div>
                                </modal>
                        ';
                        header('location: '.$link.'?message='. urlencode($message));
                }else{
                    $message = '<modal>
                            <div class="content error">
                                <p class="title">An error has occured</p>
                                <p class="sentence">Try again later</p>
                                <div class="button-group">
                                    <button class="close">Close</button>    
                                </div>    
                            </div>
                        </modal>
                        ';
                        header('location: '.$link.'?message='. urlencode($message));
                }
                $stmt->close();

            } else{
                $message = '<modal>
                            <div class="content error">
                                <p class="title">An error has occured</p>
                                <p class="sentence">Try again later</p>
                                <div class="button-group">
                                    <button class="close">Close</button>    
                                </div>    
                            </div>
                        </modal>
                        ';
                        header('location: '.$link.'?message='. urlencode($message));
            }
        }else{
            $message = '<modal>
                            <div class="content error">
                                <p class="title">An error has occured</p>
                                <p class="sentence">Try again later</p>
                                <div class="button-group">
                                    <button class="close">Close</button>    
                                </div>    
                            </div>
                        </modal>
                        ';
                        header('location: '.$link.'?message='. urlencode($message));
        }

        $mysqli->close();

    }else {
        header("location: " . $link);
    } 
}else if(checkPermit($id) === "Approved"){
    $message = '<modal>
                    <div class="content warning">
                        <p class="title">Document cannot be deleted</p>
                        <p class="sentence">The permit has already been approved</p>
                        <div class="button-group">
                                    <button class="close">Close</button>    
                                </div>   
                    </div>
                </modal>
    ';
    header('location: '.$link.'?message='. urlencode($message));
}
?>