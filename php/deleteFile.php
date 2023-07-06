<?php ini_set('display_errors', 1);
session_start();
require_once "connection.php";
require_once "functions.php";

if($_SESSION["role"] === "Admin"){
    $user_id = validate($_SESSION['user_id']);
    $ownerID = hasOwnerProfile($user_id);
    $id = hasBusinessProfile($ownerID);
    $link = "../admin/management/documents.php";
}elseif($_SESSION["role"] === "User"){
    $id = validate($_SESSION['BusinessID']);
    $link = "../user/Business/upload_requirement.php";
}else{
    $error = "yes";
}

if(checkPermit($id) === "None"){

    if (isset($_GET['file'])) {

        $fileName = urldecode($_GET['file']);
        $filePath = '../user/Business/upload/'.$id.'/'.$fileName;
    
        if(unlink($filePath)){

            $sql = "DELETE FROM Requirement WHERE FileName = ? AND BusinessID = ?";

            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param('ss', $param_fileName,$param_BusinessID);

                $param_fileName = $fileName;
                $param_BusinessID = $id;

                if($stmt->execute()) {
                    $message = '<modal>
                    <div class="content success">
                        <p class="title">Delete File Success</p>
                        <p class="sentence">File has been deleted</p>
                        <div class="button-group">
                            <button class="close">Close</button>    
                        </div>   
                    </div>
                </modal>
        ';
        header('location: '.$link.'?message='. urlencode($message));
                }else{
                    $error = "Error on deleteing database row";
                }
            }else{
                $error = "Error on updating file";
            }
        }else{
            $error = "Failed to delete File";
        }

        $mysqli->close();

    }else {
        $error = "No File specified";
    } 

    if($error){
        $message = '<modal>
                    <div class="content error">
                        <p class="title">Delete File Error</p>
                        <p class="sentence">'.$error.'</p>
                        <div class="button-group">
                            <button class="close">Close</button>    
                        </div>   
                    </div>
                </modal>
        ';
        header('location: '.$link.'?message='. urlencode($message));
    }
}else if(checkPermit($id) === "Issued"){
    $message = '<modal>
                    <div class="content warning">
                        <p class="title">Document cannot be deleted</p>
                        <p class="sentence">The permit has already been Issued</p>
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
                        <p class="title">Something went wrong</p>
                        <p class="sentence">Try again later</p>
                        <div class="button-group">
                            <button class="close">Close</button>    
                        </div>   
                    </div>
                </modal>
';
header('location: '.$link.'?message='. urlencode($message));
}
?>