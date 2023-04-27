<?php 
  include('adminSession.php');
  if(!isset($_SESSION['login_user'])){
	  header("location: index.php");
  }
	
	if(isset($_POST['post'])){
		$title = $con->real_escape_string($_POST['title']);
		$content= $con->real_escape_string($_POST['content']);
		$image = $_FILES['image']['name'];
		$target = "img/".basename($image);
		$con->query("INSERT INTO newsblog (title,content,image) VALUES ('$title', '$content','$image')");
		move_uploaded_file($_FILES['image']['tmp_name'], $target);
	
		}

		$query="SELECT * FROM businessprofile";
		$result=mysqli_query($con,$query);
		if($result->num_rows > 0) {
			$totalno = $result->num_rows;
		  } else {
			$totalno = 0;
		  }

		  
		$query1="SELECT * FROM userdata";
		$result1=mysqli_query($con,$query1);
		if($result1->num_rows > 0) {
			$totalno1 = $result1->num_rows;
		  } else {
			$totalno1 = 0;
		  }

		  $query2="SELECT * FROM application";
		$result2=mysqli_query($con,$query2);
		if($result2->num_rows > 0) {
			$totalno2 = $result2->num_rows;
		  } else {
			$totalno2 = 0;
		  }
		  $query3="SELECT * FROM newsblog";
		  $result3=mysqli_query($con,$query3);
		  if($result3->num_rows > 0) {
			  $totalno3 = $result3->num_rows;
			} else {
			  $totalno3 = 0;
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
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

	<title>Administrator</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="admin.php">
          <span class="align-middle">Administrator</span>
        </a>

				<ul class="sidebar-nav">

					<li class="sidebar-item active">
						<a class="sidebar-link" href="admin.php">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="MSME.php">
              <i class="align-middle" data-feather="users"></i> <span class="align-middle">MSME</span>
            </a>
					</li>
					<li class="sidebar-item">
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
<div class="row">
<div class="col-3">
<div class="card" >
 
  <div class="card-body">
   <h2> <span class="align-middle">Businesses</span> <i class="fa fa-building"></i></h2>
    <h1 class="card-text p-2"><?php echo $totalno;?></h1>

  </div>
</div>
</div>
<div class="col-3">
<div class="card" >

  <div class="card-body">
  <h2> <span class="align-middle">Users</span> <i class="fa fa-users"></i></h2>
  <h1 class="card-text p-2"><?php echo $totalno1;?></h1>
   
  </div>
</div>
</div>
<div class="col-3">
<div class="card" >

  <div class="card-body">
  <h2> <span class="align-middle">Application</span> <i class="fa fa-sticky-note"></i></h2>
    <h1 class="card-text p-2"><?php echo $totalno2;?></h1>

  </div>
</div>
</div>
<div class="col-3">
<div class="card" >

  <div class="card-body">
  <h2> <span class="align-middle">News/Blog</span> <i class="fa fa-sticky-note"></i></h2>
    <h1 class="card-text p-2"><?php echo $totalno3;?></h1>

  </div>
</div>
</div>
</div>
			
				<div class="col-12">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">All Businesses</h5>
								</div>
								<div class="card-body px-4">
								<div id="map" style="height:400px;"></div>
								</div>
							
					
						
					<div class="card">
			
					<div class="m-sm-4">
						<div class="text-center">
						<h1>Post News or Blogs</h1>
						</div>
					 
						<form class="widget-form" id="login-form" action="" method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label class="form-label">TITLE</label>
								<input class="form-control form-control-lg" type="text" name="title" required=""  />
							</div>
							<div class="mb-3">
								<label class="form-label">CONTENT</label>
								<textarea class="form-control form-control-lg"  name="content" required=""></textarea>
									 
									<label class="form-label mt-2">IMAGE</label>
									<input class="form-control form-control-lg" type="file" name="image"  />
							
							 
								</div>

								<div class="text-center mt-3">
								<input type="submit"  class="btn btn-lg btn-primary" Value="Post" name="post"></br>
							
							</div>
							</form>
							<div class="row">
<?php 
if(isset($_POST['delete'])){
	$id=$_POST['id'];
	mysqli_query($con,"DELETE FROM `newsblog` WHERE `postID`=$id");
	}
                    $qry5 = "SELECT * from newsblog";
					$ses_sql5 = mysqli_query($con,$qry5);
				   while ($row5 = mysqli_fetch_array($ses_sql5)) {
					
				   
				 
?>
								<div class="col-12 col-md-6 mt-3">
							<div class="card">
								<img  width="50%" height="50%" src="img/<?php echo $row5['image']?>" alt="Unsplash">
								<div class="card-header">
									<h5 class="card-title mb-0"><?php echo $row5['title']?></h5>
								</div>
								<div class="card-body">
									<p class="card-text"><?php echo $row5['content']?></p>
									<form method="post" action="" >
									<input type="hidden" name="id" value=" <?php echo $row5["postID"]; ?> ">
									<input type="submit"  class="btn btn-lg btn-danger" Value="Delete Post" name="delete"></br>
									</form>
								</div>
							</div>
						</div>
						<?php
				   }
						
						?>
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

	
	<script type="text/javascript">
function initMap() {

 
	var locations = [<?php 
    $sql = "SELECT * FROM location ";
	$result = $con->query($sql);
	
				  while ($row = mysqli_fetch_array($result)) {
                  $id2 = $row['businessID'];
				  $sql1 = "SELECT * FROM `businessprofile` where `businessId` =$id2";
				  $result1 = $con->query($sql1);
				  $row1= mysqli_fetch_array($result1);
				  $name = $row1['businessName'];
				  $sql11 = "SELECT * FROM `application` where `businessId` =$id2";
				  $result11 = $con->query($sql11);
				  $row11= mysqli_fetch_array($result11);
				  $status = $row11['status'];
				  echo '["'.$status.'","'.$name.'",'.$row['latitude'].','.$row['longitude'].'],';
				  }	?>];
  

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: new google.maps.LatLng(15.4754786,120.5963492),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

 

    var marker, i;
	var red_icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
	var green_icon = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
    
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][2], locations[i][3]),
        map: map,
		title : locations[i][1],
		icon : locations[i][0] === '2' ? green_icon : red_icon,
	
		
      });

   
    }}
  </script>
 
 <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfd6TjfdIszUwc1Wlb7qmFO8mmy2-Ekvo&callback=initMap"></script>
</body>

</html>