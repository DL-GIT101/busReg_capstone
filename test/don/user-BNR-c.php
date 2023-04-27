<?php // compare date if nalagpasan na and today
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "connect.php";
$userID = $_SESSION["userID"];

$checkSchedSQL = "SELECT * FROM schedule WHERE application_type = 'BN' AND user_id = '$userID'";
$crResult = $conn->query($checkSchedSQL);
$crRows = $crResult->fetch_assoc();
$crNumRows = $crResult->num_rows;

$formIDstatsSQL = "SELECT business_name_reg.bn_status, business_name_reg.bn_name_status, files.file_status, files.application FROM business_name_reg INNER JOIN files ON business_name_reg.user_id = files.user_id WHERE files.application = 'BN' AND business_name_reg.user_id = '$userID'";
$formIDstatsRSLT = $conn->query($formIDstatsSQL);
$formRow = $formIDstatsRSLT->fetch_assoc();

$error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $sqlID_sched = "SELECT * FROM schedule ORDER BY sched_id DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $resultID_sched = $conn->query($sqlID_sched);
        $rowID_sched = $resultID_sched->fetch_assoc();
        $idNum_sched = "000";
        if(isset($rowID_sched["sched_id"])){
            $lastsched_id = $rowID_sched["sched_id"];
        }else{
            $lastsched_id = "";
        }
            $accNum_sched = substr($lastsched_id,10,13);
            $accNumInt_sched = (int)$accNum_sched;

            if(substr($lastsched_id,2,8)==date("Ymd")){
                ++$accNumInt_sched;
                $idNum_sched = str_pad($accNumInt_sched,3,"0",STR_PAD_LEFT);
                $id_sched = "PU" . date("Ymd") . $idNum_sched;
            }else{
                $id_sched = "PU" . date("Ymd") . $idNum_sched;
            }

           if(!empty($_POST["pickedDate"])){
            $dateSelect = validate($_POST["pickedDate"]);
                if($dateSelect > date("Y-m-d")){
                    $fdateSelect = $dateSelect;
                }else{
                    $error .= "Choose latest date \n";
                }
           }
           else{
            $error .= "Pick a Date \n";
           }

           if(!empty($_POST["pickedTime"])){
            $timeSelect = validate($_POST["pickedTime"]);
           }else{
            $error .= "Pick a Time \n";
           }

           
                
    if($formRow["bn_status"]=="APPROVED" && $formRow["bn_name_status"]=="APPROVED" && $formRow["file_status"]=="APPROVED" ){
        if($crNumRows==0){
           if(empty($error)){
            $schedSQL = "SELECT * FROM schedule WHERE application_type = 'BN' AND date = '$fdateSelect' AND time = '$timeSelect'";
            $schedRslt = $conn->query($schedSQL);
                if($schedRslt->num_rows == 0){
                    $bookingSQL = "INSERT INTO schedule (sched_id, status, date, time, application_type, user_id) VALUES ('$id_sched', 'BOOKED', '$fdateSelect', '$timeSelect', 'BN', '$userID')";

                    if($conn->query($bookingSQL)===TRUE){ 
                        $sched_stat = "Success";
                        $noticeMessage = "You have been schedule for the pick-up of the DTI Certificate and bring the physical card of the Uploaded ID on ".$fdateSelect." at ".$timeSelect."";
                    }else{
                        echo 'error on database';
                    }
                   

                }else{
                    $sched_stat = "Booked";
                    $noticeMessage = "This slot has been booked";
                }
           }
        }else{
            $sched_stat = "Booked";
            $noticeMessage = "You have been already scheduled on ".$crRows["date"]." at ".$crRows["time"]."";
        }
    }else{
        $sched_stat = "Booked";
        $noticeMessage = "Application Form and Document are not yet approved";
    }
    }

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>PICK-UP SCHEDULE</title>
    <link rel = "icon" href = "/aaa/images/TarlacCitySeal.png" type = "image/x-icon">
    
</head>
<script>
        var application = "<?php echo $sched_stat; ?>";

        $(document).ready(function(){
            if(application=="Success"||application=="Booked"){
                $('#noticeModal').modal('show')
            }     
        }); 
    </script>
<script>
    $(document).ready(function(){
        $('#sched').on('change', function(){
            var pickDate = $(this).val();
            if(pickDate){
                $.ajax({
                    type:'POST',
                    url:'pickupAjax.php',
                    data:'pickDate='+pickDate,
                    success:function(html){
                        $('#timetable').html(html);
                        $('#footer').html('<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#booking">Book</button>');
                    }
                }); 
            }else{
                $('#timetable').html('<h5 class="text-center">Pick a Date first - ajax</h5>');
            }
        });
    });
</script>
<body class="bg-light">
    <div class="modal fade" id="noticeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">NOTICE</h5>
            </div>
            <div class="modal-body">
            <?php if($sched_stat=="Success"){ echo'
                    <div class="alert alert-success" role="alert">'.$noticeMessage.'</div>';
                }else if($sched_stat=="Booked"){ echo'
                    <div class="alert alert-danger" role="alert">'.$noticeMessage.'</div>';
                }
                
                ?>
            </div>
            <div class="modal-footer">
                <a href="user-BNR.php" class="btn btn-secondary">OK</a>
            </div>
            </div>
        </div>
    </div>
  <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand" href="/aaa/index.php">
        <img src="/aaa/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Tarlac City BPLS
    </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="userdashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="user-BNR.php">Register Name</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user-permit.php">Acquire Permit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Create Profile</a>
                </li>
            </ul>
            <?php
            if(isset($_SESSION["loggedin"])){
                echo '<div>
                <a href="userdashboard.php" class="btn btn-light" style="margin-right: 10px;"> Hi, '.$_SESSION["name"]  .'</a>
                </div>'.
                '<div>
                <a href="/aaa/logout.php" class="btn btn-danger">Logout</a>
                </div>';
            }
            else{
             echo  '<div style="margin-right: 15px;">
                        <a href="login.php" class="btn btn-light">Login</a>
                    </div>
                    <div>
                        <a href="register.php" class="btn btn-warning">Register</a>
                    </div>';
            }
             ?>
        </div>
        
  </nav>
  <div class="container-fluid">
  <div class="row">
  <div class="col-sm-3"></div>

  <div class="col-sm-6">
  <div class="card mt-5 border-dark" id="">
        <div class="card-header text-center "><h5 class="card-title m-0">SCHEDULE PICK-UP</h5></div>
            <div class="card-body">
            
                <div class="row mb-3">
                    <div class="col-3"></div>
                    <label class="col-3" for="sched">Pick a Schedule Date:</label>
                    <input class="col-3" type="date" id="sched" name="sched" onchange="getDate(this.value)">
                    <div class="col-3"></div>
                </div>
                <div id="timetable">
                <h5 class="text-center">Pick a Date first</h5>
                <h5 class="text-center text-danger"><?php echo $error; ?></h5>
                </div>
            </div>

            <div class="card-footer text-center" id="footer">
                
            </div>
    </div>
  </div>

  <div class="col-sm-3"></div>
  
  </div>
  <div class="modal fade" id="booking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body"><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group row">
                <label for="DpickedDate" class="col-sm-5 col-form-label">Schedule Date</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="DpickedDate" name="DpickedDate" value="">
                    <input type="hidden" class="form-control" id="pickedDate" name="pickedDate" value="">
                </div>
            </div>
            <div class="form-group row">
                <label for="DpickedTime" class="col-sm-5 col-form-label">Time</label>
                <div class="col-sm-7">
                    <input type="text" readonly class="form-control-plaintext" id="DpickedTime" name="DpickedTime" value="">
                    <input type="hidden" class="form-control" id="pickedTime" name="pickedTime" value="">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="mx-auto">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Submit">
            </div>
        </div></form>
        </div>
    </div>
    </div>
<script>
    function getDate(date){
    document.getElementById("DpickedDate").value = date;
    document.getElementById("pickedDate").value = date;
    }

    function getTime(time){
    document.getElementById("DpickedTime").value = time;
    document.getElementById("pickedTime").value = time;
    }
</script>
</body>
</html>