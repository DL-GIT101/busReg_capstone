<?php
session_start();
require_once "connect.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: login.php");
    exit;
}
    
if(isset($_GET["id"]) || isset($_POST["userID"])){
    if(isset($_GET["id"])){
        $userID = validate($_GET["id"]);
    }
    else if(isset($_POST["userID"])){
        $userID = validate($_POST["userID"]);
    }
    
    $bn_formSQL = "SELECT * FROM business_name_reg WHERE user_id = '$userID'";
    $result_form = $conn->query($bn_formSQL);
    $row_form = $result_form->fetch_assoc();

    $bdSQL = "SELECT * FROM business_details WHERE user_id = '$userID'";
    $result_bd = $conn->query($bdSQL);
    $row_bd = $result_bd->fetch_assoc();

    $ownerSQL = "SELECT * FROM owner_details WHERE user_id = '$userID'";
    $result_owner = $conn->query($ownerSQL);
    $row_owner = $result_owner->fetch_assoc();

    $userSQL = "SELECT * FROM userinfo WHERE user_id = '$userID'";
    $result_user = $conn->query($userSQL);
    $row_user = $result_user->fetch_assoc();

    $fiel_SQL = "SELECT * FROM files WHERE user_id = '$userID'";
    $result_files = $conn->query($fiel_SQL);
    $row_files = $result_files->fetch_assoc();

    $schedSQL = "SELECT * FROM schedule WHERE user_id = '$userID'";
    $result_sched = $conn->query($schedSQL);
    $row_sched = $result_sched->fetch_assoc();

    

}else{

    if(isset($_GET["date"])){
        $date_slct = validate($_GET["date"]);

        $sched_SQL = "SELECT business_name_reg.user_id, business_name_reg.bn_appli_id, schedule.date, schedule.time FROM schedule INNER JOIN business_name_reg ON schedule.user_id = business_name_reg.user_id WHERE date = '$date_slct' AND NOT status='CLAIMED'";
        $resultSQL = $conn->query($sched_SQL);

    }else{

        $sched_SQL = "SELECT business_name_reg.user_id, business_name_reg.bn_appli_id, schedule.date, schedule.time FROM schedule INNER JOIN business_name_reg ON schedule.user_id = business_name_reg.user_id WHERE NOT status='CLAIMED'";
        $resultSQL = $conn->query($sched_SQL);
    }
    



}
date_default_timezone_set("Asia/Manila");
$today = date("Y-m-d");
$todayDay = date("d");  
$tomorrow = date("Y-m-").++$todayDay;



$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    
    $sched_id = validate($_POST["sched_id"]);
    $sched_user = validate($_POST["date"]);
    
if($sched_user==$today){
    if(isset($_POST["status"])){
        $status = validate($_POST["status"]);

        if($status=="Approve"){
           $sql_Approve = "UPDATE schedule SET status = 'CLAIMED' WHERE sched_id = '$sched_id'";
           if($conn->query($sql_Approve)==TRUE){
                $submit = "APPROVED";
                $noticeMessage = "This schedule has been confirmed";
           }else{
            echo "error updating database";
           }
        }
        
        else if($status=="Submit"){
            
            $sql_Approve = "DELETE FROM schedule  WHERE sched_id = '$sched_id'";
            if($conn->query($sql_Approve)==TRUE){
                 $submit = "REJECTED";
                 $noticeMessage = "This schedule has been deleted";
            }else{
             echo "error updating database";
            }
        }
    }
}else{
    $submit = "REJECTED";
    $noticeMessage = "Schedule date is not today";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>PICK-UP</title>
    <link rel = "icon" href = "/aaa/images/TarlacCitySeal.png" type = "image/x-icon">
    <script>
        $(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        }); 
    </script>
    <script>
    var submit = "<?php echo $submit; ?>";

    $(document).ready(function(){
        if(submit=="APPROVED" ||submit=="REJECTED"){
            $('#noticeModal').modal('show')
        }     
    }); 
    </script>
</head>
<body class="bg-light">
    <div class="modal fade" id="noticeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">NOTICE</h5>
            </div>
            <div class="modal-body">
                <?php if($submit=="APPROVED"){ echo'
                <div class="alert alert-success" role="alert">'.$noticeMessage.'</div>';
            }else if($submit=="REJECTED"){ echo'
                <div class="alert alert-danger" role="alert">'.$noticeMessage.'</div>';
            }
            
            ?>
            </div>
                <div class="modal-footer">
                    <?php if($submit=="APPROVED"){ echo'
                    <a href="admin-BNR-c.php" class="btn btn-secondary">OK</a>';
                }else if($submit=="REJECTED"){ echo'
                    <a href="admin-BNR-c.php" class="btn btn-secondary">OK</a>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>

<nav class="navbar navbar-dark bg-dark ">
    <div>
    <a class="navbar-brand" href="/aaa/adminDashboard.php">
        <img src="/aaa/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">
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
                                <a href="admin-BNR-a-form.php" class="list-group-item list-group-item-action list-group-item-light"><i class="bi bi-credit-card-2-front"></i> Form</a>
                                <a href="admin-BNR-b.php" class="list-group-item list-group-item-action list-group-item-light"><i class="bi bi-file-earmark-arrow-up"></i> Documents</a>
                                <a href="admin-BNR-c.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-calendar-week"></i> Pick-Up</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card bg-warning">
                        <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="bi bi-card-text"></i>
                            Business Permit
                            </button>
                        </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-credit-card-2-front"></i> Form</a>
                                <a href="#" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-file-earmark-arrow-up"></i> Documents</a>
                                <a href="#" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-calendar-week"></i> Pick-Up</a>
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
            
            <?php
            
            
            if(!empty($userID)){
                
                if($row_user["user_suffix"]=="N/A"){
                $suffix = "";
                } echo '
            <div class="my-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="admin-BNR-c.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Table</a>
                <a href="#" role="button" class="btn btn-primary active">Schedule</a>
                
            </div>
        </div>
        <div class="card mt-2 border-dark" id="formbody">
        <div class="card-header text-center" id="formHead"><h5 class="card-title m-0" id="formTitle">BUSINESS NAME PICK-UP - '.$userID.' </h5></div>

        <div class="card-body">
            <div class="table-responsive-sm mt-3">
            <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Owner</th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                </tr>
            </thead>
                <tbody>
                    <tr>
                        <th scope="row">'.$row_user["user_lname"].', '.$row_user["user_fname"].' '.$suffix.'</th>
                        <td>'.$row_bd["business_name"].'</td>
                        <td>'.$row_sched["date"].'</td>
                        <td>'.$row_sched["time"].'</td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
        <div class="card-footer text-center" id="formFoot">
            <div id="bottombtn">
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject">
                    Cancel
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approve">
                    Confirm
                </button>
            </div>
        </div>
        </div>';

            }else{ echo '
                <div class="row mt-3">
                <div class="col-3"></div>
                <h5 class="col-3 bg-warning mt-2" for="sched" >Pick a Schedule Date:</h5>
                <input class="col-3" type="date" id="sched" name="sched" onchange="getDate(this.value)">
                <div class="col-3"><a class="btn btn-secondary" href="#" role="button" id="dateLink">Find</a></div>
                </div>

                <div class="row mt-3">
                <div class="col-2 text-center "><p class="font-weight-bold my-1 bg-warning">Date</p></div>
                <div class="btn-group col" data-toggle="buttons">
                    <a class="btn btn-dark" href="admin-BNR-c.php" role="button" id="today">ALL</a>
                    <a class="btn btn-primary" href="admin-BNR-c.php?date='.$today.'" role="button" id="today">TODAY</a>
                    <a class="btn btn-info" href="admin-BNR-c.php?date='.$tomorrow.'" role="button" id="tomorrow">TOMORROW</a>
                </div>
                </div>
                

                <div class="table-responsive-sm mt-3" id="checkDate">';
                if($resultSQL->num_rows>0){
                    echo"
                    <table class='table table-bordered table-hover' >
                    <thead class='thead-light'>
                        <tr>
                            <th scope='col'>ID</th>
                            <th scope='col'>Application ID</th>
                            <th scope='col'>Date</th>
                            <th scope='col'>Time</th>
                        </tr>
                    </thead>";
                
                    while($row = $resultSQL->fetch_assoc()){

                       

                      echo  "<tbody>
                            <tr class='clickable-row' data-href='/aaa/admin-BNR-c.php?id=".$row["user_id"]."'>
                                <th scope='row'>".$row["user_id"]."</th>
                                <td>".$row["bn_appli_id"]."</td>
                                <td>".$row["date"]."</td>
                                <td>".$row["time"]."</td>";
                                
                               

                    echo  '</tr>';
                        
                    } echo '</tbody>
                    </table>';
                }else{
                    echo '0 results';
                }
            
           echo  '</div>';
            }
                
            ?>
        </div>
        <div class="col-md-2 px-0 mt-3 pt-2">

        </div>
    </div>
</div>
<div class="modal fade" id="reject" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Reject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Did the business owner did not show up or the document did is invalid? If yes then click Submit.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                    <label for="userID" class="col-sm-2 col-form-label">User ID</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $userID?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userID" class="col-sm-4 col-form-label">Business Name</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $row_bd["business_name"]?>">
                    </div>
                </div>
                <input type="text" class="form-control d-none" value="<?php echo $row_sched["sched_id"]?>" name="sched_id">
                <input type="text" class="form-control d-none" value="<?php echo $row_sched["date"]?>" name="date">
            </div>
            <div class="modal-footer">
                <div class="mx-auto">
                <input type="submit" class="btn btn-primary"  name="status" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approve" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Approve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Did the business owner got its document? If yes then click Approve</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                    <label for="userID" class="col-sm-2 col-form-label">User ID</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $userID?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="userID" class="col-sm-4 col-form-label">Business Name</label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $row_bd["business_name"]?>">
                    </div>
                    <input type="text" class="form-control d-none" value="<?php echo $row_sched["sched_id"]?>" name="sched_id">
                <input type="text" class="form-control d-none" value="<?php echo $row_sched["date"]?>" name="date">
                </div>
            </div>
            <div class="modal-footer">
                <div class="mx-auto">
                <input type="submit" class="btn btn-success"  name="status" value="Approve">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>

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
<script>
    function getDate(){
    var date = document.getElementById("sched").value;
    var date = document.getElementById("dateLink").href = "admin-BNR-c.php?date=" + date;
    
    }
</script>
</body>
</html>