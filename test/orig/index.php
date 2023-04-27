<?php
 include('connection.php');
 $qry5 = "SELECT * from newsblog";
$ses_sql5 = mysqli_query($con,$qry5);
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

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

	<title>Sign In</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="img/icons/icon-48x48.png" width="30" height="30" alt="">
          </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
</li>
          </ul>
        </div>
      </nav>
      </nav>
	<main class="d-flex w-100">
		
	<div class="container-fluid p-0">
				<div class="col-12">
							<div class="card flex-fill w-100">
					
								<div class="card-body px-4">
								<div id="map" style="height:500px;"></div>
					

                <div class="row">
<?php 
                   
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
								
								</div>
							</div>
						</div>
						<?php
				   }
						
						?>
								</div>
							</div>
						</div>
                 </div>
	</main>

	
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
		animation: google.maps.Animation.BOUNCE,
		
      });

   
    }}
  </script>
 
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARTNDN7evBJcxi0INQSBxIQ_Ui_7ncIPc&callback=initMap&v=weekly"
      defer
    ></script>

</body>

</html>
