<?php
session_start();
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>ADMIN DASHBOARD</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark ">
    <div>
    <a class="navbar-brand" href="/admin/dashboard.php">
        <img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Tarlac City BPLS - ADMIN
    </a>
    <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
    <i class="bi bi-arrow-bar-left"></i>
        </button>
        </div>
    
           <?php 
                echo    '<div class="dropdown mr-4 pr-1">
                            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            Hi, '.$_SESSION["name"]  .'
                            <i class="bi bi-person-square"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/logout.php">Logout</a>
                            </div>
                        </div>';
                
            ?>  
  </nav>
<div class="container-fluid">
    <div class="row">
        <div class="collapse show width bg-warning" id="collapseWidthExample">
            <div class="col-md-2 p-0" >
                <div class=""  style="height:95vh ;">

                <div class="accordion" id="accordionExample" style="width:200px ;">

                    <div class="card bg-light">
                        <div class="card-header" id="headingOne">
                        <h2 class="mb-0 ">
                            <button class="btn btn-block text-left " type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="bi bi-bar-chart-fill"></i>
                            Dashboard
                            </button>
                        </h2>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="list-group">
                                <a href="/admin/dashboard.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-credit-card-2-front"></i> Statistics</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="bi bi-card-checklist"></i>
                            Business Permit
                            </button>
                        </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="list-group">
                                <a href="permit/form.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-credit-card-2-front"></i> Form</a>
                                <a href="admin-BNR-b.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-file-earmark-arrow-up"></i> Documents</a>
                                <a href="admin-BNR-c.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-calendar-week"></i> Pick-Up</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                            <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="bi bi-person-vcard"></i>
                            Business Profile
                            </button>
                        </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                            And lastly, the placeholder content for the third and final accordion panel. This panel is hidden by default.
                        </div>
                        </div>
                    </div>

                </div>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            Chart etc dito
        </div>
        <div class="col-md-2"></div>
    </div>
</div>


</body>
</html>