<?php 
session_start();
require_once "../../php/connection.php";
require_once "../../php/functions.php";
require_once "../../php/checkPermit.php";

if(isset($_GET['id_user'])){
    $user_id = urldecode($_GET['id_user']);
    $sql = "DELETE FROM users WHERE id = ?";
    $page = "management";
}else if(isset($_GET['profile'])){
    $user_id = validate($_GET['profile']);
    $sql = "DELETE user_profile, new_documents
        FROM user_profile
        LEFT JOIN new_documents ON user_profile.user_id = new_documents.user_id
        WHERE user_profile.user_id = ?";
    $page = "profile";
}else if(isset($_GET['documents'])){
    $user_id = validate($_GET['documents']);
    $sql = "DELETE FROM new_documents WHERE user_id = ?";
    $page = "documents";
}else{
    header("location: ../management/users.php");
}

$userDirectory = '../../user/upload/' . $user_id;
if(checkPermit($mysqli) !== "Approved"){
 if ($stmt = $mysqli->prepare($sql)) {

        $stmt->bind_param("s", $param_id);
        $param_id = $user_id;
        if ($stmt->execute()) {
        
            deleteDirectory($userDirectory,$page);
            if($page === "profile"){
                header("location: ../management/profiles.php?id=".$user_id);
            }else if($page === "management") {
                header("location: ../management/users.php");
            } else if($page === "documents"){
                header("location: ../management/documents.php?id=".$user_id);
            }
        } else {
            echo "Error deleting user";
            header("location: ../management/users.php");
        }
        $stmt->close();
    }

$mysqli->close(); 
}else{
    $message = '<modal>
                    <div class="content success">
                        <p class="title">Documents cannot be deleted</p>
                        <p class="sentence">The permit has already been approved</p>
                        <a href="../management/profiles.php">OK</a>
                    </div>
                </modal>
    ';
    header("location: ../management/profiles.php?message=". urlencode($message));
}

function deleteDirectory($directory,$page) {
    if (!is_dir($directory)) {
        return;
    }

    $files = glob($directory . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDirectory($file,$page);
        } else {
            unlink($file);
        }
    }

    if($page === "management"){
        rmdir($directory);
    }
}
?>