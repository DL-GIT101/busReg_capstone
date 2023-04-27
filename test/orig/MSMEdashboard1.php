<?php 
include('connection.php');
session_start();
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
								<div class="row">
								<div class="col-2">
									<h2>Services</h2>	
                                     <hr>
                                
									<div class="d-grid">
										<a href="applyBusinessPermit.php" class="btn btn-primary">Apply Business Permit</a>
									</div>
									<hr>
								</div>
									<div class="col-10">
										 <div id="map" style="width: 100%; height: 25vh;"></div>
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