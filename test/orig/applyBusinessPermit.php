<?php 
include('connection.php');
session_start();
if(!isset($_SESSION['login_user'])){
	header("location: index.php");
}
$user_check = $_SESSION['login_user'];
$query = "SELECT * from userdata where Email='$user_check'";
$ses_sql0 = mysqli_query($con,$query);
$row0 = mysqli_fetch_assoc($ses_sql0);
$login_session = $row0['userID'];
$login_session2 = $row0['Email'];
$qry0 = "SELECT * from userdata where userID='$login_session'";
$ses_sql3 = mysqli_query($con,$qry0);
$row3 = mysqli_fetch_assoc($ses_sql3);
$id = $row3['userID'];
$qry = "SELECT * from profile where userID='$id'";
$ses_sql = mysqli_query($con,$qry);
$row = mysqli_fetch_assoc($ses_sql);
$fname = $row['firstName'];
$mname = $row['middleName'];
$lname = $row['lastName'];
$ID = $row['ID'];
$qry1 = "SELECT * from businessProfile where ID='$ID'";
$ses_sql1 = mysqli_query($con,$qry1);
$row1 = mysqli_fetch_assoc($ses_sql1);
$businessName = $row1['businessName'];
$logo = $row1['logo'];
$nature = $row1['natureOfBusiness'];
$locationId = $row1['businessID'];
$qry2 = "SELECT * from location where businessID='$locationId'";
$ses_sql2 = mysqli_query($con,$qry2);
$row2 = mysqli_fetch_assoc($ses_sql2);
$lat = $row2['latitude'];
$lng = $row2['longitude'];
$qry4 = "SELECT * from application where businessID='$locationId'";
$ses_sql4 = mysqli_query($con,$qry4);
$row4 = mysqli_fetch_assoc($ses_sql4);
$status = $row4['status'];
if(isset($_POST['upload'])){
	$title = $con->real_escape_string($_POST['title']);
	$con1 = new PDO("mysql:host=localhost;dbname=database_capstone","root","");
	$name = $_FILES['myfile']['name'];
	$type = $_FILES['myfile']['type'];
	$data = file_get_contents($_FILES['myfile']['tmp_name']);
	$st = $con1->prepare("INSERT INTO forms values ('0','$locationId','$title',?,?,?)");
	$st->bindParam(1,$name);
	$st->bindParam(2,$type);
	$st->bindParam(3,$data);
	$st->execute();
	$con->query("UPDATE application SET`status`= '1', `applicationType`='Business Permit' WHERE `businessID`='".$locationId."'");
	echo '<script type="text/javascript">alert("Successfuly Upload");</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

	<title><?php echo $businessName;?></title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#regForm {
  background-color: #ffffff;
  margin: 0px auto;
  width: 100%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 18px;
  border: 1px solid #aaaaaa;
  border-radius: 10px;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}
.line {
	background-color: #3b7ddd;
  height: 1px;
  width: 100%;
  margin: 1px;

  background-repeat: no-repeat;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #3b7ddd;
    border-color: #3b7ddd;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
	background-color: #3b7ddd;
    border-color: #3b7ddd;
	
}
.hr-lines{
  position: relative;
  max-width: 600px;
  margin: 50px auto;
  margin-bottom: 10px;
  text-align: center;
}

.hr-lines:before{
  content:" ";
  height: 1px;
  width: 130px;
  background: gray;
  display: block;
  position: absolute;
  top: 50%;
  left: 0;
}

.hr-lines:after{
  content:" ";
  height: 1px;
  width: 130px;
  background: gray;
  display: block;
  position: absolute;
  top: 50%;
  right: 0;
}
</style>
</head>

<body>
	<div class="wrapper">
	

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
				<img src="img/icons/icon-48x48.png"  />
        </a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
							<a class=" d-sm-inline-block" href="logout.php" >
                <img src="img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">logout</span>
              </a>
						
						</li>
					</ul>
				</div>
			</nav>
            <main class="content">
				<div class="container-fluid p-0">

			
					<div class="row">
						<div class="col-md-4 col-xl-3">
							<div class="card mb-3">
								<div class="card-header">
									<h5 class="card-title mb-0">Business Profile</h5>
								</div>
								<div class="card-body text-center">
									<img src="img/<?php echo $logo; ?>" alt="LOGO" class="img-fluid rounded-circle mb-2" width="128" height="128" />
									<h1 ><?php echo $businessName;?></h1>
									<h5 class="card-title mb-0"><?php echo $fname ." ". $mname ." " . $lname;?></h5>
								</div>
								<hr class="my-0" />
								<div class="card-body">
									<h5 class="h6 card-title">Nature Of Business</h5>
									<a href="#" class="badge bg-primary me-1 my-1"><?php echo $nature;?></a>
									
		
								</div>
								<hr class="my-0" />
								<div class="card-body">
									<h5 class="h6 card-title">Business Permit Status</h5>
							
									<?php
                                           	$approve = "<a href='#' class='badge bg-success me-1 my-1'>Approve</a>";
										    $pending = "<a href='#' class='badge bg-warning me-1 my-1'>On Process</a>";
											$none = "<a href='#' class='badge bg-danger me-1 my-1'>No Application</a>";

                                           

                                           if($status == '0'){
                                            echo $none;
										}
                                           else if($status == '1'){
                                           	echo $pending;
                                           }
										   else{
											   echo $approve;
										   }
										   ?>
    
								</div>
								
							</div>
						</div>

						<div class="col-md-8 col-xl-9">
							<div class="card p-3">
                            <div class="m-sm-2">
							<form  action="" method="post" enctype="multipart/form-data">
							
  <!-- One "tab" for each step in the form: -->
  
		
  <label class="form-check mb-2">
					
		  <input class="form-check-input" type="radio" value="Proof of Business Registration " name="title" checked>
            <span class="form-check-label ">
			Proof of Business Registration (DTI for Sole Proprietorship/SEC for Corporations and Partnerships/CDA for Cooperatives)
            </span>
          </label>
										<label class="form-check  mb-2">
            <input class="form-check-input" type="radio" value="Locational Clearance" name="title">
            <span class="form-check-label">
			Locational Clearance
            </span>
          </label>
		  <label class="form-check  mb-2">
            <input class="form-check-input" type="radio" value="Contract of Lease / or Tax Declaration or Transfer Certificate of Title" name="title">
            <span class="form-check-label">
			Contract of Lease (if leased) or Tax Declaration or Transfer Certificate of Title (TCT) (if owned)
            </span>
          </label>
		  <label class="form-check  mb-2">
            <input class="form-check-input" type="radio" value="Barangay Clearance" name="title">
            <span class="form-check-label">
			Barangay Clearance
            </span>
          </label>
		  <label class="form-check  mb-2">
            <input class="form-check-input" type="radio" value="Occupancy Permit" name="title">
            <span class="form-check-label">
			Occupancy Permit (if required)
            </span>
          </label>
		  <label class="form-check  mb-2">
            <input class="form-check-input" type="radio" value="Sketch and photos of location of business" name="title">
            <span class="form-check-label">
			Sketch and photos of location of business (if required)
            </span>
          </label>
  
  <br>

<div class="mb-3">
											
											<input class="form-control form-control-lg" type="file" name="myfile" required=""  />
										</div>
										
<div class="text-center mt-3">
											<input type="submit"  class="btn btn-lg btn-primary" Value="Upload" name="upload"></br>
										
										</div>
										</form>
										<br>
										<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Submitted Requirments</th>
      <th width="10">Download</th>
    </tr>
  </thead>
  <tbody>
	  <?php
	$query5="SELECT * FROM forms where businessID ='$locationId'";
	$result5=mysqli_query($con,$query5);
  while($row5=mysqli_fetch_array($result5))
 {
	 $formId = $row5['formID'];
	
	 
		 

  
	 ?>
    <tr>
    

      <td><?php echo $row5['title']; ?></td>
      <td width="10">
	  <?php
                                           	echo "<a href='file.php?formID=".$formId."'><i data-feather = 'download'></i></a>";
											?>

                                           
										   
                                          

										 </td>
    </tr>
 <?php }
  ?>
  </tbody>
</table>
<div class="text-center mt-3">
											<button type="button" class="btn btn-lg btn-primary" style="width:10%;" Value="Upload" onclick="history.back()">Go back</button></br>
										
										</div>
								</div>
						
							
									</div>
								
						</div>
					</div>

				</div>
			</main>
		

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>Tarlac State University</strong></a> &copy;
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="js/app.js"></script>
	<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  //... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  //... and run a function that will display the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form...
  if (currentTab >= x.length) {
    // ... the form gets submitted:
    document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class on the current step:
  x[n].className += " active";
}
</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
			var gradient = ctx.createLinearGradient(0, 0, 0, 225);
			gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
			gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
			// Line chart
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: gradient,
						borderColor: window.theme.primary,
						data: [
							2115,
							1562,
							1584,
							1892,
							1587,
							1923,
							2566,
							2448,
							2805,
							3438,
							2917,
							3327
						]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.0)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 1000
							},
							display: true,
							borderDash: [3, 3],
							gridLines: {
								color: "rgba(0,0,0,0.0)"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Pie chart
			new Chart(document.getElementById("chartjs-dashboard-pie"), {
				type: "pie",
				data: {
					labels: ["Chrome", "Firefox", "IE"],
					datasets: [{
						data: [4306, 3801, 1689],
						backgroundColor: [
							window.theme.primary,
							window.theme.warning,
							window.theme.danger
						],
						borderWidth: 5
					}]
				},
				options: {
					responsive: !window.MSInputMethodContext,
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					cutoutPercentage: 75
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "This year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
						barPercentage: .75,
						categoryPercentage: .5
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: false,
							ticks: {
								stepSize: 20
							}
						}],
						xAxes: [{
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var markers = [
				{
					coords:[15.3675, 120.5941],
					name: "Capas"
				}
			];
			var map = new jsVectorMap({
				map: "world",
				selector: "#world_map",
				zoomButtons: true,
				markers: markers,
				markerStyle: {
					initial: {
						r: 9,
						strokeWidth: 7,
						stokeOpacity: .4,
						fill: window.theme.primary
					},
					hover: {
						fill: window.theme.primary,
						stroke: window.theme.primary
					}
				},
				zoomOnScroll: true
			});
			window.addEventListener("resize", () => {
				map.updateSize();
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
			var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
			document.getElementById("datetimepicker-dashboard").flatpickr({
				inline: true,
				prevArrow: "<span title=\"Previous month\">&laquo;</span>",
				nextArrow: "<span title=\"Next month\">&raquo;</span>",
				defaultDate: defaultDate
			});
		});
	</script>
	<script>
                    let map;
					const center2 = 	<?php echo '{ lat: '.$lat.' , lng: '.$lng.' }';  ?> 
                    function initMap() {
                        map = new google.maps.Map(document.getElementById("map"), {
                            center: center2,
                            zoom: 18,
                            scrollwheel: true,
                        });
						const center = 	<?php echo '{ lat: '.$lat.' , lng: '.$lng.' }';  ?> 
                        let marker = new google.maps.Marker({
                            position: center,
                            map: map,
							animation : google.maps.Animation.BOUNCE,
                        });

                    
                    
                    }
                </script>
 
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARTNDN7evBJcxi0INQSBxIQ_Ui_7ncIPc&callback=initMap&v=weekly"
      defer
    ></script>

</body>

</html>