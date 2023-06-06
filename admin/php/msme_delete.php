<?php 
require_once "/opt/lampp/htdocs/busReg_capstone/php/connection.php";
ini_set('display_errors', 1);

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
    header("location: ../msme_management.php");
}

$userDirectory = '/opt/lampp/htdocs/busReg_capstone/user/upload/' . $user_id;

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $param_id);
    $param_id = $user_id;
    if ($stmt->execute()) {
       
        deleteDirectory($userDirectory,$page);
        if($page === "profile"){
            header("location: ../msme_profile.php?id=".$user_id);
        }else if($page === "management") {
            header("location: ../msme_management.php");
        } else if($page === "documents"){
            header("location: ../msme_documents.php?id=".$user_id);
        }
    } else {
        echo "Error deleting user";
        header("location: ../msme_management.php");
    }
    $stmt->close();
}
$mysqli->close();

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        return $data;
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