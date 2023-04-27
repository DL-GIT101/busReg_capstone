<?php
session_start();
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: login.php");
    exit;
}

if(isset($_POST['idPermit'])){
$IDpermit =  validate($_POST['idPermit']);
 

$lineSQL = "SELECT * FROM lineofbusiness WHERE permitID = '$IDpermit'";
$lineRESULT = $conn->query($lineSQL);

$permitSQL = "SELECT * FROM permit WHERE ID = '$IDpermit'";
$permitRESULT = $conn->query($permitSQL);
$permitROW = $permitRESULT->fetch_assoc();

$IDbusiness = $permitROW["businessID"];

$businessSQL = "SELECT * FROM business WHERE ID = '$IDbusiness'";
$businessRESULT = $conn->query($businessSQL);
$businessROW = $businessRESULT->fetch_assoc();

$IDowner = $businessROW["ownerID"];

$ownerSQL = "SELECT * FROM owner WHERE ID = '$IDowner'";
$ownerRESULT = $conn->query($ownerSQL);
$ownerROW = $ownerRESULT->fetch_assoc();

$IDuser = $ownerROW["userID"];

$userSQL = "SELECT * FROM user WHERE ID = '$IDuser'";
$userRESULT = $conn->query($userSQL);
$userROW = $userRESULT->fetch_assoc();
}else{
    $display = "none";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>REVIEW FORM</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">

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
                    <a href="admin-BNR-a-name.php?userID='.$userID.'" class="btn btn-secondary">OK</a>';
                }else if($form_submit=="REJECTED"){ echo'
                    <a href="admin-BNR-a-form.php" class="btn btn-secondary">OK</a>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
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

        <div class="row <?php echo ($display=="none")? '' : 'd-none' ;?>">
        <div class="col-1 mt-3">
                <a class="btn btn-dark" href="/admin/permit/form.php" role="button"><i class="bi bi-arrow-left-circle"></i></a>
            </div>
        <h2 class="text-center mt-2 col ">Choose an Applicantion Form First</h2>
        </div>

        <div class="row <?php echo ($display=="none")? 'd-none' : '' ;?>">
            <div class="col-1 mt-3">
                <a class="btn btn-dark" href="/admin/permit/form.php" role="button"><i class="bi bi-arrow-left-circle"></i></a>
            </div>
            
            <h2 class="text-center mt-2 col">Review Form - <?php echo $IDbusiness; ?></h2>
        </div>

        <div class="card my-2 shadow <?php echo ($display=="none")? 'd-none' : '' ;?>" id="formbody">
            <div class="card-header text-center" id="formHead">
                <h5 class="card-title m-0" id="formTitle">APPLICATION FORM </h5>
            </div>
            <div class="card-body">
                
<ul class="list-group list-group-flush ">
    <!--Type of Registration--> 
        <li class="list-group-item">
            <div class="form-row">
                <label for="type" class="font-weight-bold pt-2 col-xl-3">Type of Registration </label>
                <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["registration"] ?>">
            </div>

        </li>
    <!--Payment-->
        <li class="list-group-item">
            <div class="form-row">
                <label for="payment" class="font-weight-bold col-xl-3">Payment Schedule</label>
                <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["payment"] ?>">
            </div>
        </li>
    <!--A. BUSINESS INFORMATION AND REGISTRATION-->    
        <li class="list-group-item">
            <h5>A. BUSINESS INFORMATION AND REGISTRATION</h5>
        </li>
    <!--Type of Business-->    
    <li class="list-group-item">
        <div class="form-row">
            <label for="business" class="font-weight-bold col-xl-3">Business Type</label>
            <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["business"] ?>">
        </div>
    </li>
    <!--DTI and TIN-->
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dti">DTI Registration No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["certificateNo"] ?>">
                </div>
                <div class="form-group col-xl-6">
                    <label for="tin">Tax Identification No.(TIN) <small class="text-muted">Ex. (012-345-678-900)</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["tin"] ?>">
            </div>
        </li>
    <!--Business Name-->
    <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-lg-6">
                    <label for="business_name">Business Name</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["name"] ?>">
                </div>
                <div class="form-group col-lg-6">
                    <label for="franchise">Trade Name/Franchise<small> (if applicable)</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["tradeName"] ?>">
                </div>
            </div>
        </li>
    <!--Business Address-->
        <li class="list-group-item">
            <label for="businessAddress" class="font-weight-bold form-row">Business Address <small class="text-danger font-weight-bold"> *</small></label>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="houseNo">House/Bldg.No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["number"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="building">Name of Building</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["building"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="lotNo">Lot No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["lot"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="blockNo">Block No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["block"] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="street">Street</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["street"] ?>">
                </div>
                <div class="form-group col-lg-3">
                        <label for="barangay">Barangay</label>
                        <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["barangay"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="subdivision">Subdivision</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["subdivision"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="city">City/Municipality</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="Tarlac City">
                </div>
            </div>
    <!--Owned -->
            <div class="form-row">             
                <label for="owned" class="col-lg-3 ">Owned?</label>
            </div>
            <div class="form-row" id="ownedYES">
                <div class="form-group col-lg-6">
                <label for="owned" class="col-lg-3 font-italic">If Yes, </label>
                    <label for="property">Tax Declaration No./Property Identification No. <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["property"] ?>">
                </div>  
            </div>
            <label for="owned" class="col-lg-3 font-italic">If No, </label>
            <div class="form-row" id="ownedNO">
                
                <div class="form-group col-lg-6">
                
                    <label for="lessor">Lessor Name <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["lessor"] ?>">
                </div>  
                <div class="form-group col-lg-6">
                    <label for="rent">Monthly Rental <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="₱ <?php echo $permitROW["rent"] ?>">
                </div>  
            </div>                  
        </li>
    <!--Telephone and Mobile Number-->     
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-xl-6">
                    <label for="telephone">Telephone No. <small class="text-muted">(Ex. 123-4567)</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["telephone"] ?>">
                </div>
                <div class="form-group col-xl-6">
                    <label for="mobile">Mobile No. <small class="text-muted">(Ex. 912-345-6789)</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["mobile"] ?>">
                </div>
            </div> 
    </li>
    <!--Name of Owner-->
        <li class="list-group-item">
            <label for="name" class="font-weight-bold form-row">For Sole Proprietorship</label>
            <label for="name" >Name of Owner</label>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="surname">Surname</label>
                    <input type="text" readonly class="form-control-plaintext col" id="surname" value="<?php echo $userROW["last"]?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="given">Given Name</label>
                    <input type="text" readonly class="form-control-plaintext col" id="given" value="<?php echo $userROW["first"]?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="middle">Middle Name</label>
                    <input type="text" readonly class="form-control-plaintext col" id="middle" value="<?php echo $userROW["middle"]?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="suffix">Suffix</label>
                    <input type="text" readonly class="form-control-plaintext col" id="suffix" value="<?php echo $userROW["suffix"] ?>">
                </div>
            </div>

            <div class="form-row">             
                <label for="gender" class="col-lg-3">Gender <small class="text-danger font-weight-bold">*</small></label>
                <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["gender"] ?>">
            </div>                   
    </li>
    <!--B. BUSINESS OPERATION-->    
        <li class="list-group-item">
                <h5>B. BUSINESS OPERATION</h5>
        </li>
    <!--Business Area Employees Vehicles-->
        <li class="list-group-item">
            <div class="form-row">
                <div class="form-group col-xl-4">
                    <label for="area">Business Area <small>(in sq.m)</small><small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["area"] ?>">
                </div>
                <div class="form-group col-xl-4">
                    <label for="van">No. of Delivery Vehicles <small>(if applicable)</small><small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["vanTruck"] ?>">
                </div>
                <div class="form-group col-xl-4">
                    <label for="motorcycle" class="invisible d-none d-xl-block">Label </label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["motorcycle"] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-xl-5">
                    <label for="empTarlac">No. of Employees residing within Tarlac City <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["empTarlac"] ?>">
                </div>
                <div class="form-group col-xl-4">
                    <label for="empMale">Total No. of Employees in Establishment <small class="text-danger font-weight-bold">*</small></label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="Male : <?php echo $permitROW["empMale"] ?>">
                </div>
                <div class="form-group col-xl-3">
                    <label for="empFemale" class="invisible d-none d-xl-block">Label </label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="Female : <?php echo $permitROW["empFemale"] ?>">
                </div>
            </div>
        </li>
    <!--Taxpayers Address--> 
        <li class="list-group-item">
            <div class="form-row">
            <label for="ownerAddress" class="font-weight-bold col-lg-3" id="labelTaxpayer">Taxpayer's Address<small class="text-danger font-weight-bold"> *</small></label>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="houseNo">House/Bldg.No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["number"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="building">Name of Building</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["building"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="lotNo">Lot No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["lot"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="blockNo">Block No.</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["block"] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-3">
                    <label for="street">Street</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["street"] ?>">
                </div>
                <div class="form-group col-lg-3">
                        <label for="barangay">Barangay</label>
                        <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["barangay"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="subdivision">Subdivision</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $ownerROW["subdivision"] ?>">
                </div>
                <div class="form-group col-lg-3">
                    <label for="city">City/Municipality</label>
                    <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="Tarlac City">
                </div>
            </div>

            <div class="form-row">             
                <label for="incentive" class="col-lg-5">Tax Incentives from any Government Entity <small class="text-danger font-weight-bold">*</small></label>
                <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $permitROW["incentive"] ?>">
            </div> 
            <div class="form-row">             
                <label for="activity" class="col-xl-2">Business Activity<small class="text-danger font-weight-bold">*</small></label>
                <input type="text" readonly class="form-control-plaintext col" id="staticEmail" value="<?php echo $businessROW["activity"] ?>">
        </li>         
    
    <!--Philippine Standard Industrial Classification-->
    <li class="list-group-item">
        <label for="section" class="font-weight-bold form-row">Philippine Standard Industrial Classification</label>

    <!-- Line Of Business -->
    
        <table class="table table-striped" id="lineTable">
            <thead class="thead-light">
                <tr>
                <th scope="col">Line of Business</th>
                <th scope="col">Product/Services</th>
                <th scope="col">No. of Units</th>
                <th scope="col">Total Capitalization (₱)</th>
                </tr>
            </thead>
            <tbody id="lineBody">
                <?php 
                if($lineRESULT->num_rows > 0){ 
                    while($row = $lineRESULT->fetch_assoc()){  
                        echo '  <tr>  
                                    <td>'.$row["subclass"].'</td>
                                    <td>'.$row["productServices"].'</td>
                                    <td>'.$row["unit"].'</td>
                                    <td>'.$row["capital"].'</td>
                                </tr>'; 
                    } 
                }else{ 
                    echo '0 Results'; 
                } 
                ?>
            </tbody>
        </table>
    </li>
</ul>
    </div>
            <div class="card-footer text-center" id="formFoot">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approve">Approve</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject">Reject</button>
            </div>
        </div>

    </div>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>


<!-- approve -->
<div class="modal fade" id="approve" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Finished reviewing the Applicaiton Form?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        approve
      </div>
      <div class="modal-footer ">
        <div class="mx-auto">
        <button type="button" class="btn btn-secondary">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
        </div>
      </div></form>
    </div>
  </div>
</div>
<!-- reject -->
<div class="modal fade" id="reject" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Finished reviewing the Applicaiton Form?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body"><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        reject
      </div>
      <div class="modal-footer ">
        <div class="mx-auto">
        <button type="button" class="btn btn-secondary">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
        </div>
      </div></form>
    </div>
  </div>
</div>

</body>
</html>