<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: ../login.php");
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Dashboard</title>
</head>
<body>
<nav id="navbar">
<p>ADMIN</p> 
    <div id="logo">
        <a href="../index.php">
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p> 
        </a>
    </div>
    
    <div id="user">
        <a href="../php/logout.php">Logout</a>
    </div>
</nav>

<div class="row">

    <nav id="sidebar">
        <ul>
            <li><img src="../img/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li><img src="../img/list.png" alt=""><a href="">MSME List</a></li>
            <li><img src="../img/register.png" alt=""><a href="">Register MSME</a></li>
        </ul>
    </nav>

    <div id="content">

        <div class="container"> 
            
        
        </div>

    </div>
</div>

</body>
</html>