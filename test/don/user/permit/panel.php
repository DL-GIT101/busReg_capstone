<?php 
session_start();
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

if(isset($_SESSION["userID"])){
    $userID = $_SESSION["userID"];
}else{
    $userID = "";
}

    $sqlBN_status = "SELECT * FROM permit WHERE ID = '$userID'";
    $resultBN_staus = $conn->query($sqlBN_status);
    $rowBN_status = $resultBN_staus->fetch_assoc();

    if(isset($rowBN_status["bn_status"])){
        $bnstats = $rowBN_status["bn_status"];
    }else{
        $bnstats = "";
    }

    $sqlFILE_status = "SELECT * FROM files WHERE user_id = '$userID'";
    $resultFILE_staus = $conn->query($sqlFILE_status);
    $rowFILE_status = $resultFILE_staus->fetch_assoc();

    if(isset($rowFILE_status["application"])){
        $file_application = $rowFILE_status["application"];
    }else{
        $file_application = "";
    }
    if(isset($rowFILE_status["file_status"])){
        $bn_validID_stats = $rowFILE_status["file_status"];

    }else{
        $bn_validID_stats = "";
    }

        $checkSchedSQL = "SELECT * FROM schedule WHERE application_type = 'BN' AND user_id = '$userID'";
        $crResult = $conn->query($checkSchedSQL);
        $crRows = $crResult->fetch_assoc();
        if($bnstats=="APPROVED" && $rowBN_status["bn_name_status"]=="APPROVED" && $bn_validID_stats=="APPROVED"){
            $sched = "APPROVED";
            if(isset($crRows["status"])){
                $sched = $crRows["status"];
            }
        }else{
            $sched = "NOT";
        }

    if(empty($_GET["application"])){
        $application = "none";
    }
    else{
        $application = $_GET["application"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>PERMIT REGISTRATION</title>
    <link rel = "icon" href = "/images/TarlacCitySeal.png" type = "image/x-icon">
    <script>
    </script>
</head>
    <body class="bg-light">

  <!--Navigation Bar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="index.php"><img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">Tarlac City BPLS</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item active ">
                        <a class="nav-link" href="panel.php">Business Permit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
                <?php
                    if(isset($_SESSION["loggedin"])){echo '
                        <div class="mr-2">
                            <a href="userdashboard.php" class="btn btn-light"> Hi, '.$_SESSION["name"]  .'</a>
                        </div>
                        <div class="my-2 my-sm-0">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>';
                    }else{ echo  '
                        <div class="mr-2">
                            <a href="login.php" class="btn btn-light">Login</a>
                        </div>
                        <div class="my-2 my-sm-0">
                            <a href="register.php" class="btn btn-warning">Register</a>
                        </div>';
                    }
                    ?>
        </div>
  </nav>
  
<div class="jumbotron bg-info text-white">
  <h1 class="display-4">Business Permit Registration</h1>
  <p class="lead">Register your business online </p>
</div>

<div class="container-fluid">
<div class="row">
    <div class="col-1"></div>

    <div class="col-10">
        <div class="card-deck">
            <div class="col-md">
                <div class="card border-dark">
                    <div class="card-body">
                        <h5 class="card-title">Step 1: Fill out the Form</h5>
                        <a class="btn btn-primary" href="form.php" role="button">Fill out the Form</a>
                    </div>
                </div>
            </div>
    
            <div class="col-md my-2 my-md-0">
                <div class="card border-dark">
                    <div class="card-body">
                        <h5 class="card-title">Step 2: Upload Documents</h5>
                        <a class="btn btn-primary" href="document.php" role="button">Upload Documents</a>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-dark">
                    <div class="card-body">
                        <h5 class="card-title">Step 3: Schedule Pick-up</h5>
                        <a class="btn btn-primary" href="schedule.php" role="button">Book Pick-up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-1"></div>
</div>
</div>

<script>
    var bnamestatus = "<?php echo $rowBN_status["bn_name_status"]; ?>";
    var divBNstats = document.getElementById("bNameRejected");
    var badge = document.getElementById("bNamestats_badge");
    var send = document.getElementById("send");
        if(bnamestatus=="APPROVED"){
            divBNstats.classList.remove("d-none");
            badge.classList.add("badge-success");
        }else if(bnamestatus=="REJECTED"){
            divBNstats.classList.remove("d-none");
            badge.classList.add("badge-danger");
            send.classList.remove("d-none");
        }else if(bnamestatus=="SUBMITTED"){
            divBNstats.classList.remove("d-none");
            badge.classList.add("badge-secondary");
        }
            
</script>
<script>
    // business name registration status
    var bn_Stats = "<?php echo $bnstats; ?>";
    var form = document.getElementById("submitted");
    var formbadge = document.getElementById("formbadge");
    var formb = document.getElementById("fillup");
    var rejected = document.getElementById("rejected");
            if (bn_Stats=="SUBMITTED") {
                form.classList.remove("d-none");
                formbadge.classList.add("badge-secondary");
                formb.classList.add("d-none");
            }else if (bn_Stats=="APPROVED") {
                form.classList.remove("d-none");
                formbadge.classList.add("badge-success");
                formb.classList.add("d-none");
            }else if (bn_Stats=="REJECTED") {
                form.classList.remove("d-none");
                formbadge.classList.add("badge-danger");
                formb.classList.add("d-none");
                rejected.classList.remove("d-none");
            }
    
</script>
<script>
    var bn_id_Stats = "<?php echo $bn_validID_stats; ?>";
    var bn_file_check = "<?php echo $file_application; ?>";
    var rejectedF = document.getElementById("rejectedFiles");
    var upload = document.getElementById("uploadedID");
    var IDbadge = document.getElementById("IDbadge");
    var submitreq = document.getElementById("submitReq");
        if(bn_file_check=="BN"){
            if(bn_id_Stats=="UPLOADED") {
                upload.classList.remove("d-none");
                IDbadge.classList.add("badge-secondary");
                submitreq.classList.add("d-none");
            }
            else if(bn_id_Stats=="APPROVED") {
                upload.classList.remove("d-none");
                IDbadge.classList.add("badge-success");
                submitreq.classList.add("d-none");
            }else if(bn_id_Stats=="REJECTED") {
                upload.classList.remove("d-none");
                IDbadge.classList.add("badge-danger");
                submitreq.classList.add("d-none");
                rejectedF.classList.remove("d-none");
            }
        }
</script>
<script>
    var sched = "<?php echo $sched;?>";
    var booked = document.getElementById("booked");
    var bookedbadge = document.getElementById("bookBadge");
    var booking = document.getElementById("booking");
    var notyet = document.getElementById("notyet");
    if(sched=="BOOKED"){
        booked.classList.remove("d-none");
        bookedbadge.classList.add("badge-secondary");
    }else if(sched=="CLAIMED"){
        booked.classList.remove("d-none");
        bookedbadge.classList.add("badge-success");
    }else if(sched=="APPROVED"){
        booking.classList.remove("d-none");
    }else if(sched=="NOT"){
        notyet.classList.remove("d-none");
    }
</script>
</body>
</html>