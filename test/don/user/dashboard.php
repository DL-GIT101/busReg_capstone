<?php
session_start();
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: /login.php");
        exit;
    }

    $IDuser = $_SESSION["IDuser"];
    
    $permitSQL = "  SELECT permit.status
                    FROM user
                    JOIN owner ON user.ID = owner.userID
                    JOIN business ON owner.ID = business.ownerID
                    JOIN permit ON business.ID = permit.businessID WHERE user.ID = '$IDuser';";
    $permitRESULT = $conn->query($permitSQL);
    $permitROW = $permitRESULT->fetch_assoc();

    if(!empty($permitROW["status"])){
    $permitStatus = $permitROW["status"];
    }else{
        $permitStatus = "none";
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
    <title>DASHBOARD</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">

</head>
<body class="bg-light">

    <!--Navigation Bar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="index.php"><img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">Tarlac City BPLS</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="permit/panel.php">Business Permit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
           <?php if(!empty($_SESSION["name"])){
                echo    '<div class="mr-2">
                        <a href="/user/dashboard.php" class="btn btn-light"> Hi, '.$_SESSION["name"]  .'</a>
                        </div>';
                }   
            ?>
                <div class="my-2 my-sm-0">
                    <a href="/logout.php" class="btn btn-danger">Logout</a>
                </div>
        </div>
  </nav>
  <div class="alert alert-info text-center" role="alert">This system can only accomodate <b>Single Proprietorship Business</b>  and <b>Filipino Owner</b> for now</div>
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs nav-justified" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                    <button class="nav-link active btn-block"  id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">BUSINESS PERMIT <i class="bi bi-file-text-fill"></i></button>
            </li>
            <li class="nav-item" role="presentation">
                    <button class="nav-link btn-block" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">BUSINESS PROFILE <i class="bi bi-building-fill"></i></button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="pills-tabContent">
    <!--BUSINESS PERMIT -->
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-dark">
                        <div class="card-body">
                            <h5 class="card-title">Application Form</h5>

                            <h6 id="permitStatus" class="d-none">Application Status: <span id="statusBadge" class="badge "></span></h6>

                            <a id="fillout" class="btn btn-outline-primary" href="permit/form.php" role="button">Fill out the Form</a>
                            
                        </div>
                        </div>
                    </div>
                    <div class="col-md-4 my-2 my-md-0">
                        <div class="card border-dark">
                        <div class="card-body ">
                            <h5 class="card-title">Documentary Requirements</h5>
                            <a class="btn btn-primary" href="permit/document.php" role="button">Upload Documents</a>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-dark">
                        <div class="card-body">
                            <h5 class="card-title">Schedule</h5>
                            <a class="btn btn-primary" href="permit/schedule.php" role="button">Book Pick-up</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>           

<!--BUSINESS PROFILE -->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    
                </div>
        </div>
    </div>  
</div>
<script>
    window.onload = function() {
        var permit = "<?php echo $permitStatus;?>";
        var status = document.getElementById("permitStatus");
        var badge = document.getElementById("statusBadge");
        var submit = document.getElementById("fillout");

        if(permit=="SUBMITTED"){
        status.classList.remove("d-none");
        badge.innerHTML = "SUBMITTED";
        badge.classList.add("badge-secondary");
        submit.classList.add("d-none");
        }else if(permit=="REJECTED"){
        status.classList.remove("d-none");
        badge.innerHTML = "REJECTED";
        badge.classList.add("badge-danger");
        submit.innerHTML = "Submit Another";
        }else if(permit=="APPROVED"){
        status.classList.remove("d-none");
        badge.innerHTML = "APPROVED";
        badge.classList.add("badge-success");
        submit.classList.add("d-none");
        }
    }
</script>
</body>
</html>