<?php
session_start();
require_once "../php/config.php";

if (isset($_GET['id'])) {

$user_id = urldecode($_GET['id']);
echo $user_id;

} 
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
        return $data;
}
?>