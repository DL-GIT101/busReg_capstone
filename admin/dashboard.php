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
            <li id="dashboard">Dashboard</li>
            <li id="list">MSME List</li>
            <li id="register">Register MSME</li>
        </ul>
    </nav>

    <div id="content">

        <div class="container"> 
            
        
        </div>

    </div>
</div>

</body>
</html>