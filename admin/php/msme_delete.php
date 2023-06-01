<?php 
require_once "/opt/lampp/htdocs/busReg_capstone/php/connection.php";
ini_set('display_errors', 1);

$sql = "DELETE FROM users WHERE id = ?";

$user_id = urldecode($_GET['id']);
$userDirectory = '/opt/lampp/htdocs/busReg_capstone/user/upload/' . $user_id;

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $param_id);
    $param_id = $user_id;
    if ($stmt->execute()) {
        echo "User has been deleted";
        deleteDirectory($userDirectory);
        header("location: ../msme_management.php");
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

function deleteDirectory($directory) {
    if (!is_dir($directory)) {
        return;
    }

    $files = glob($directory . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDirectory($file);
        } else {
            unlink($file);
        }
    }

    rmdir($directory);
}
?>