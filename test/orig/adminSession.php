<?php 
include('connection.php');
session_start();

$user_check = $_SESSION['login_user'];
$query = "SELECT * from userdata where Email='$user_check'";
$ses_sql = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($ses_sql);
$login_session = $row['userID'];
$login_session2 = $row['Email'];

?>