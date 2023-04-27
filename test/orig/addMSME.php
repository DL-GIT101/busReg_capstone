<?php 
  include('adminSession.php');
  if(!isset($_SESSION['login_user'])){
	  header("location: index.php");
  }
  if(isset($_POST['add'])){
	$email = $con->real_escape_string($_POST['email']);
	$password = $con->real_escape_string($_POST['password']);
	$fname = $con->real_escape_string($_POST['fname']);
	$mname = $con->real_escape_string($_POST['mname']);
	$lname = $con->real_escape_string($_POST['lname']);
	$add1 = $con->real_escape_string($_POST['add1']);
	$add2 = $con->real_escape_string($_POST['add2']);
	$add3 =$con->real_escape_string($_POST['add3']);
	$phone =$con->real_escape_string($_POST['phone']);
	$business = $con->real_escape_string($_POST['business']);
	$nature = $con->real_escape_string($_POST['nature']);
	$lat =$con->real_escape_string($_POST['lat']);
	$lng =$con->real_escape_string($_POST['lng']);
	$image = $_FILES['image']['name'];
	$target = "img/".basename($image);
	$hashedPassword =password_hash($password, PASSWORD_DEFAULT);
	$con->query("INSERT INTO userdata (Email,Password,userRole) VALUES ('$email','$hashedPassword','Business')");
    $query = "SELECT * from userdata where Email='$email'";
    $ses_sql = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($ses_sql);
    $login_session3 = $row['userID'];
    $login_session4 = $row['Email'];
	$con->query("INSERT INTO profile (userID,firstName,middleName,lastName,add1,add2,add3,phone) VALUES ('$login_session3','$fname','$mname','$lname','$add1','$add2','$add3','$phone')");
	$query2= "SELECT * FROM profile WHERE userID='$login_session3'";
	$ses_sql2 = mysqli_query($con,$query2);
	$row2 = mysqli_fetch_assoc($ses_sql2);
	$ID = $row2['ID'];
	$con->query("INSERT INTO businessProfile (ID,businessName,natureOfBusiness,logo) VALUES ('$ID','$business','$nature','$image')");
	$query3 = "SELECT * FROM profile WHERE userID='$login_session3'";
	$ses_sql3 = mysqli_query($con,$query3);
	$row3 = mysqli_fetch_assoc($ses_sql3);
	$sql3 =$row3['ID'];
	$query4 = "SELECT * FROM businessProfile WHERE ID='$sql3'";
	$ses_sql4 = mysqli_query($con,$query4);
	$row4 = mysqli_fetch_assoc($ses_sql4);
	$sql4 =$row4['businessID'];
	$con->query("UPDATE userdata SET`verification`= '2' WHERE `userID`='".$login_session3."'");
	$con->query("INSERT INTO location (businessID,latitude,longitude) VALUES ('$sql4','$lat','$lng')");
	$number = 'QWERTZUIOPASDFGHJKLYXCVBNM0123456789';
$number = str_shuffle($number);
$number = substr($number, 0, 10);
	$con->query("INSERT INTO application (businessID,status,applicationNumber,applicationType) VALUES ('$sql4','0','BP$number','Business Permit')");
	move_uploaded_file($_FILES['image']['tmp_name'], $target);


	
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
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>
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
                    <li class="sidebar-item">
						<a class="sidebar-link" href="MSME.php">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">MSME</span>
            </a>
					</li>

					<li class="sidebar-item active">
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
				<div class="col-12">
					<div class="row">
						<div class="col-12 col-lg-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0 p-0">Add Business</h5>
								</div>
                                <div class="card-body">
								<div class="card-header">
									<h5 class="card-title mb-0 mt-0">Owner's Profile</h5>
								</div>
								<form action="" method="post" enctype="multipart/form-data">
									<div class="row mb-4">
										<div class="col-6">
										<input class="form-control form-control-lg" type="text" name="email" value="default@email.com"/>
							
										</div>
										<div class="col-6">
									
								<input class="form-control form-control-lg" type="text" name="password" value="defaultpassword" readonly=""/>
										</div>

									</div>
                                <div class="row">
								
                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="lname" placeholder="First Name" />
                                                </div>
                                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="mname" placeholder="Middle Name" />
                                                </div>
                                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="fname" placeholder="Last Name" />
                                                </div>
								</div>
                              
								<div class="row mt-4">
								
                                <div class="col-3">
                                                <input class="form-control form-control-lg" type="text" name="add1" placeholder="Unit No./House No." />
                                                </div>
                                                <div class="col-3">
                                                <input class="form-control form-control-lg" type="text" name="add2" placeholder="Street/Barangay" />
                                                </div>
                                                <div class="col-3">
                                                <input class="form-control form-control-lg" type="text" name="add3" placeholder="City/Municipal" />
                                                </div>
												<div class="col-3">
                                                <input class="form-control form-control-lg" type="text" name="phone" placeholder="Mobile/Phone Number" />
                                                </div>
								</div>
                                
								<div class="row mt-4">
								<div class="card-header">
									<h5 class="card-title mb-0 mt-0">Business Profile</h5>
								</div>
                                <div class="col-6">
                                                <input class="form-control form-control-lg" type="text" name="business" placeholder="Business Name" />
                                                </div>
                                                <div class="col-6">
                                                <input class="form-control form-control-lg" type="text" name="nature" placeholder="Nature Of Business" />
                                                </div>
                                             
								</div>
								<div class="col-7 mt-4">
									<label class="p-2">Logo</label>
                                                <input class="form-control form-control-lg" type="file" name="image" value="Logo" />
                                                </div>

												<div class="mb-3 mt-2">
											<label class="form-label">Pin Location of the Business</label>
											<input type="hidden" class="form-control" placeholder="lat" name="lat" id="lat">
                                            <input type="hidden" class="form-control" placeholder="lng" name="lng" id="lng">
                                           
										  	<div id="map" style="width: 100%; height: 50vh;"></div>
										</div>
                                </div>
                                
                             
								<div class="card-body">
								<input type="submit"  class="btn btn-lg btn-primary" Value="Add" name="add">
								
							</div>
							</form>
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
	<script src="js/app.js"></script>
	<script>
                    let map;
                    function initMap() {
                        map = new google.maps.Map(document.getElementById("map"), {
                            center: { lat: 15.4755, lng: 120.5963 },
                            zoom: 12,
                            scrollwheel: true,
                        });

                        const center = { lat: 15.4755, lng: 120.5963 };
                        let marker = new google.maps.Marker({
                            position: center,
                            map: map,
                            draggable: true
                        });

                        google.maps.event.addListener(marker,'position_changed',
                            function (){
                                let lat = marker.position.lat()
                                let lng = marker.position.lng()
                                $('#lat').val(lat)
                                $('#lng').val(lng)
                            })

                        google.maps.event.addListener(map,'click',
                        function (event){
                            pos = event.latLng
                            marker.setPosition(pos)
                        })
                    }
                </script>
 
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARTNDN7evBJcxi0INQSBxIQ_Ui_7ncIPc&callback=initMap&v=weekly"
      defer
    ></script>
</body>

</html>