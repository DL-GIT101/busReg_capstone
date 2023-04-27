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
    <script src="https://kit.fontawesome.com/e7aa253b6e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <title>APPLICATION FORM</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">
    
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark ">
        <div>
        <a class="navbar-brand" href="/admin/dashboard.php">
            <img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Tarlac City BPLS - ADMIN
        </a>
        <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample" onclick="sideBar()">
        <i class="bi bi-arrow-bar-left" id="leftArrow"></i><i class="bi bi-arrow-bar-right d-none" id="rightArrow"></i>
            </button>
            </div>
        
            <?php 
                    echo    '<div class="dropdown mr-4 pr-1">
                                <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                Hi, '.$_SESSION["name"]  .'
                                <i class="bi bi-person-square"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/aaa/logout.php">Logout</a>
                                </div>
                            </div>';
                    
                ?>  
    </nav>
<div class="container-fluid">
    <div class="row">
        <!--Vertical Side bar -->
            <div class="collapse show width bg-warning" id="collapseWidthExample">
            <div class="col-md-2 p-0" >
                <div style="height:92vh ;">

                <div class="accordion" id="accordionExample" style="width:200px; ">

                    <div class="card bg-warning">
                        <div class="card-header" id="headingOne">
                        <h2 class="mb-0 ">
                            <button class="btn btn-block text-left " type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="bi bi-bar-chart-fill"></i>
                            Dashboard
                            </button>
                        </h2>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="list-group">
                                <a href="adminDashboard.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-credit-card-2-front"></i> Statistics</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card bg-light">
                        <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="bi bi-card-checklist"></i>
                            Business Name
                            </button>
                        </h2>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="list-group">
                                <a href="form.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-credit-card-2-front"></i> Form</a>
                                <a href="admin-BNR-b.php" class="list-group-item list-group-item-action list-group-item-light"><i class="bi bi-file-earmark-arrow-up"></i> Documents</a>
                                <a href="admin-BNR-c.php" class="list-group-item list-group-item-action list-group-item-light"><i class="bi bi-calendar-week"></i> Pick-Up</a>
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

            <h2 class="text-center mt-2">Business Permit Application Form</h2><form action="/admin/permit/review.php" method="post">
            <table class="table table-bordered table-hover shadow">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Owner</th>
                <th scope="col">Business Name</th>
                <th scope="col">Application</th>
                <th scope="col">
                    <div class="btn-group">
                        <button class="btn dropdown-toggle py-0 text-white font-weight-bold" type="button" data-toggle="dropdown" aria-expanded="false">Status</button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" value="submitted">Submitted</button>
                            <button class="dropdown-item" type="button" value="approved">Approved</button>
                            <button class="dropdown-item" type="button" value="rejected">Rejected</button>
                            <button class="dropdown-item" type="button" value="all">All</button>
                        </div>
                    </div>
                </th>
                <th scope="col" class="text-center"></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                
            </tbody></form> 
            </table>

            </div>
                
        </div>
    </div>
</div>

<script>//sidebar
    function sideBar(){
        var left = document.getElementById("leftArrow");
        var right = document.getElementById("rightArrow");

        if(left.classList.contains('d-none')){
            left.classList.remove('d-none');
            right.classList.add('d-none');
        }else if(right.classList.contains('d-none')){
            right.classList.remove('d-none');
            left.classList.add('d-none');
        }
    }
</script>
<script>//table ajax
    $(document).ready(function() {
    // Send an AJAX request when a dropdown item is clicked
    $('.dropdown-item').on('click', function() {
        var value = $(this).val();
        $.ajax({
        type: 'POST',
        url: '/ajax/formTableAjax.php',
        data: { status: value },
        success: function(html) {
            $('#tableBody').html(html); 
        }
        });
    }); var value = "submitted";
        $.ajax({
        type: 'POST',
        url: '/ajax/formTableAjax.php',
        data: { status: value },
        success: function(html) {
            $('#tableBody').html(html); 
        }
    });
    });
</script>

</body>
</html>