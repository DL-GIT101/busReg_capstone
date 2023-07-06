<?php
session_start();
 
$_SESSION = array();
 
session_destroy();
 
header("location: /busReg_capstone/index.php");
//header("location: /busReg_capstone/index.php"); for hosting
exit;
?>