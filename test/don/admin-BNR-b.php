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

}else{

        $file_SQL = "SELECT business_name_reg.user_id, business_name_reg.bn_appli_id, business_name_reg.bn_status, files.file_status, files.document_type, files.application FROM files INNER JOIN business_name_reg ON files.user_id = business_name_reg.user_id WHERE business_name_reg.bn_status = 'APPROVED' AND business_name_reg.bn_name_status = 'APPROVED' AND files.file_status = 'UPLOADED'";
        $resultSQL = $conn->query($file_SQL);


     
}



$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST["status"])){
        $status = validate($_POST["status"]);

        if($status=="Approve"){
            $status = "APPROVED";
            $annotation = "This document was approved";
            $statusSQL = "UPDATE files SET file_status = '$status', annotation = '$annotation' WHERE user_id = '$userID'";
            if($statusResult = $conn->query($statusSQL) === TRUE){
                        $form_submit = "APPROVED";
                        $noticeMessage = "This document was approved";
            }
            else{
                echo "Error updating the database";
            }
        }
        
        else if($status=="Submit"){
            $status = "REJECTED";

            if(!empty($_POST["notes"])){
                $annotation = validate($_POST["notes"]);

                    $statusSQL = "UPDATE files SET file_status = '$status', annotation = '$annotation'  WHERE user_id = '$userID'";
                    if($conn->query($statusSQL) === TRUE){
                        $form_submit = "REJECTED";
                        $noticeMessage = "This document was rejected";
                    }
                    else{
                        echo "Error updating the database";
                    }
            }else{
                $error = "No Notes has been input";
            }
            
        }

        
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
    <title>DOCUMENT</title>
    <link rel = "icon" href = "/aaa/images/TarlacCitySeal.png" type = "image/x-icon">
    <script>
        $(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        }); 
    </script>
    <script>
    var submit = "<?php echo $form_submit; ?>";

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
                <?php if($form_submit=="APPROVED"){ echo'
                <div class="alert alert-success" role="alert">'.$noticeMessage.'</div>';
            }else if($form_submit=="REJECTED"){ echo'
                <div class="alert alert-danger" role="alert">'.$noticeMessage.'</div>';
            }
            
            ?>
            </div>
                <div class="modal-footer">
                    <?php if($form_submit=="APPROVED"){ echo'
                    <a href="admin-BNR-b.php" class="btn btn-secondary">OK</a>';
                }else if($form_submit=="REJECTED"){ echo'
                    <a href="admin-BNR-b.php" class="btn btn-secondary">OK</a>';
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
                                <a href="admin-BNR-b.php" class="list-group-item list-group-item-action list-group-item-warning"><i class="bi bi-file-earmark-arrow-up"></i> Documents</a>
                                <a href="admin-BNR-c.php" class="list-group-item list-group-item-action list-group-item-light"><i class="bi bi-calendar-week"></i> Pick-Up</a>
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
            
            <?php  if(!empty($userID)){ echo '
            <div class="my-3">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="admin-BNR-b.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Table</a>
                    <a href="#" role="button" class="btn btn-primary active">Document</a>
                    <a href="admin-BNR-a-form.php?id='.$userID.'" role="button" class="btn btn-primary">Form <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="card mt-2 border-dark" id="formbody">
            <div class="card-header text-center" id="formHead"><h5 class="card-title m-0" id="formTitle">BUSINESS NAME REQUIREMENTS - '.$userID.' </h5></div>

            <div class="card-body">
                <div class="table-responsive-sm mt-3">
                <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Document Type</th>
                        <th scope="col">File Name</th>
                        <th class="text-center" scope="col">View</th>
                        <th class="text-center" scope="col">Download</th>
                    </tr>
                </thead>
                    <tbody>
                        <tr>
                            <th scope="row">'.$row_files["document_type"].'</th>
                            <td>'.$row_files["file_name"].'</td>
                            <td class="text-center"><a href="uploads/'.$userID.'/'.$row_files['file_name'].'" target="_blank"><i class="bi bi-eye-fill"></i></a></td>
                            <td class="text-center"><a href="uploads/'.$userID.'/'.$row_files['file_name'].'" download><i class="bi bi-download"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
            <div class="card-footer text-center" id="formFoot">
                <div id="bottombtn">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject">
                        Reject
                    </button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approve">
                        Approve
                    </button>
                </div>
            </div>
            </div>';

            }else{ echo ' 
                <div class="table-responsive-sm mt-3">';
                if($resultSQL->num_rows>0){
                    echo"
                    <table class='table table-bordered table-hover' >
                    <thead class='thead-light'>
                        <tr>
                            <th scope='col'>ID</th>
                            <th scope='col'>Application ID</th>
                            <th scope='col'>Type</th>
                            <th scope='col'>Document</th>
                        </tr>
                    </thead>";
                
                    while($row = $resultSQL->fetch_assoc()){

                       

                      echo  "<tbody>
                            <tr class='clickable-row' data-href='/aaa/admin-BNR-b.php?id=".$row["user_id"]."'>
                                <th scope='row'>".$row["user_id"]."</th>
                                <td>".$row["bn_appli_id"]."</td>";

                            echo "<td>".$row["document_type"]."</td>";
                                if($row["file_status"]=="UPLOADED"){
                                    echo "<td><span class='badge badge-secondary'>".$row["file_status"]."</span></td>";
                                }else if($row["file_status"]=="APPROVED"){
                                    echo "<td><span class='badge badge-success'>".$row["file_status"]."</span></td>";
                                }

                    echo  "</tr>";
                        
                    } echo "</tbody>
                    </table>";
                }else{
                    echo "0 results";
                }
            
           echo  "</div>";
            }
                
            ?>
        </div>
        <div class="col-md-2 px-0 mt-3 pt-2">
        <?php  if(!empty($userID)){ echo'
            <div class="card mt-5 border-dark">
                <h5 class="card-header" ">Details</h5>
                <div class="card-body">
                    <h6 class="card-title " id="headDits">The documents was '.$row_files["file_status"].'</h6>      
                    <button type="button" id="annotationBTN" class="btn btn-danger d-none" data-toggle="modal" data-target="#annotation">
                    Annotation
                    </button>
                </div>
            </div>';
        } 
        ?>
        <div class="modal fade" id="annotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Annotations</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert"><?php echo $row_files["annotation"]; ?></div>
            </div>

            </div>
        </div>
        </div>
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
                <p>Finished reviewing the uploaded requirement? If so annotate on why the requirement was rejected. </p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                    <label for="userID" class="col-sm-2 col-form-label">User ID</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $userID?>">
                        <input type="hidden" class="form-control" id="userID" name="userID" value="<?php echo $userID?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="10"></textarea>
                </div>
                <p class="text-danger"><?php echo $error; ?></p>
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
                <p>Finished reviewing the uploaded requirement? If so click the approve button</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                    <label for="userID" class="col-sm-2 col-form-label">User ID</label>
                    <div class="col-sm-10">
                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $userID?>">
                        <input type="hidden" class="form-control" id="userID" name="userID" value="<?php echo $userID?>">
                    </div>
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
   
    var file_stat = "<?php echo $row_files["file_status"];?>";
    var head = document.getElementById("headDits");
    var btnForm = document.getElementById("bottombtn");
    var note = document.getElementById("annotationBTN");
        if(file_stat=="APPROVED"){
            head.classList.add("text-success");
            btnForm.classList.add("d-none");
            btnForm.classList.add("disabled");
            note.classList.add("d-none");
        }else if(file_stat=="REJECTED"){
            head.classList.add("text-danger");
            btnForm.classList.add("d-none");
            btnForm.classList.add("disabled");
            note.classList.remove("d-none");
        }else{
            note.classList.add("d-none");
        }

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
var form = "<?php echo $form_stat;?>";
    var buttonBN = document.getElementById("bnbutton");
    if(form!=="APPROVED"){
        buttonBN.classList.add("d-none");
    }else{
        buttonBN.classList.remove("d-none");
    }
</script>
</body>
</html>