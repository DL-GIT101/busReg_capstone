<?php 
  include('adminSession.php');
  if(!isset($_SESSION['login_user'])){
	  header("location: index.php");
  }
$ID = @$_GET["ID"]; 
$qry = "SELECT * from profile where ID='$ID'";
$ses_sql = mysqli_query($con,$qry);
$row = mysqli_fetch_assoc($ses_sql);
$qry1 = "SELECT * from businessProfile where ID='$ID'";
$ses_sql1 = mysqli_query($con,$qry1);
$row1 = mysqli_fetch_assoc($ses_sql1);
$ID1 = $row1['businessID'];
$qry2 = "SELECT * from location where businessID='$ID1'";
$ses_sql2 = mysqli_query($con,$qry2);
$row2 = mysqli_fetch_assoc($ses_sql2);
$lat = $row2['latitude'];
$lng = $row2['longitude'];
$qry3 = "SELECT * from application where businessID='$ID1'";
$ses_sql3 = mysqli_query($con,$qry3);
$row3 = mysqli_fetch_assoc($ses_sql3);
$status = $row3['status'];
$query="SELECT * FROM forms where businessID ='$ID1'";
$result=mysqli_query($con,$query);
if(isset($_POST['accept'])){
	$con->query("UPDATE application SET`status`= '2', `applicationType`='Business Permit' WHERE `businessID`='".$ID1."'");
	echo '<script type="text/javascript">alert("Bussiness Permit Approve");</script>';
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

	<title>Administrator</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="admin.php">
          <span class="align-middle">Administrator</span>
        </a>

				<ul class="sidebar-nav">

					<li class="sidebar-item ">
						<a class="sidebar-link" href="admin.php">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>
                    <li class="sidebar-item active">
						<a class="sidebar-link" href="MSME.php">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">MSME</span>
            </a>
					</li>

					<li class="sidebar-item ">
						<a class="sidebar-link" href="addMSME.php">
              <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Add MSME</span>
            </a>
					</li>

					

				
				</ul>

			
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <img src="img/avatars/avatar.png" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">Admin</span>
              </a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="logout.php">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">
                <div class="card">
                <div class="card-header">
									<h5 class="card-title mb-2">Business Profile</h5>
                 <h4 class="float-end text-navy mb-1"style="color: red;"> <b><?php echo  $row3['applicationNumber'];?></b></h4>
									<input class="form-control form-control-lg" type="hidden"  value="<?php echo $id;?>" readonly=""/>
							<div class="card-body">
								<div class="m-sm-2">
									<form action="" method="post" enctype="multipart/form-data">
									<div class="mb-3">
                                            <div class="row">
                                                <div class="col-4">
                                                <label class="form-label">Owner's Name</label>
                                                <input class="form-control form-control-lg" type="text" name="fname" value="<?php echo  $row['firstName'].' '.$row['middleName'].' '.$row['lastName']; ?>" readonly="" placeholder="First Name" />
                                                </div>
                                                <div class="col-4">
                                                <label class="form-label">Business Name</label>
                                                <input class="form-control form-control-lg" type="text" name="mname" value="<?php echo  $row1['businessName'];?>" readonly=""  />
                                                </div>
                                                <div class="col-4">
                                                <label class="form-label">Nature of Business</label>
                                                <input class="form-control form-control-lg" type="text" name="lname" value="<?php echo  $row1['natureOfBusiness'];?>" readonly="" />
                                                </div>
										
                                            </div>
											</div>
										
								
									
										
										
										<br>
									</form>
									<div class="col-12">
										 <div id="map" style="width: 100%; height: 50vh;"></div>
								</div>
								<hr>
									<table  class="table table-striped table-bordered ">
       			<thead>
		            <tr>
					<th width="10"></th>
					<th width="10"></th>
					<th>Title</th>
					<th>File Name</th>
		            </tr>
        		</thead>
        		<tbody>
				<?php
				if(isset($_POST['delete'])){
					$delete = $con->real_escape_string($_POST['formid']);
					mysqli_query($con,"DELETE FROM `forms` WHERE `formID`='$delete'");
					echo '<script type="text/javascript">alert("Delete Successful");</script>';
					}
                while($row4=mysqli_fetch_array($result)){
					$id= $row4['formID'];
					
                            	 ?>
           			 <tr>
						<td width="10">  
						<?php
                                           	echo "<a href='file.php?formID=".$id."'><i data-feather = 'download'></i></a>";
											?>
											
						</td>      
						
						<td width="10">	
					
							<form  action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="formid" value=" <?php echo $row4["formID"]; ?>">  
							<button type="submit" name="delete" class="btn btn-outline-danger "><i data-feather = 'trash-2'></i></button>
				</form>
			 </td>                          
						<td><?php echo $row4["title"]; ?></td>
						<td><?php echo $row4["name"]; ?></td>
					</tr>
					<?php
                            	}
                                ?>	
									
				</tbody>
    		</table>
								</div>
								<div class="row">
								<div class="col-lg-4">
								<form  action="" method="post" enctype="multipart/form-data">
										
<div class="text-center mt-3">
											<input type="submit"  class="btn btn-lg btn-primary" Value="Approve" name="accept"></br>
										
										</div>
										</form>
							</div>

							</div>
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