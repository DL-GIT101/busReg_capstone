<?php 
include('connection.php');
session_start();

$user_check = $_SESSION['login_user'];
$query = "SELECT * from userdata where Email='$user_check'";
$ses_sql = mysqli_query($con,$query);
$row = mysqli_fetch_assoc($ses_sql);
$login_session = $row['userID'];
$login_session2 = $row['Email'];

$qry = "SELECT * from userdata where userID='$login_session'";
$ses_sql2 = mysqli_query($con,$qry);
$row2 = mysqli_fetch_assoc($ses_sql2);
$id = $row2['userID'];

$qry1 = "SELECT * from profile where userID='$login_session'";
$ses_sql3 = mysqli_query($con,$qry1);
$row3 = mysqli_fetch_assoc($ses_sql3);


?>