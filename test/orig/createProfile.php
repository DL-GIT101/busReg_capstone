<?php
  include('loginSession.php');
  if(!isset($_SESSION['login_user'])){
	  header("location: index.php");
  }
 if(isset($_POST['create'])){
        $fname = $con->real_escape_string($_POST['fname']);
		$mname = $con->real_escape_string($_POST['mname']);
		$lname = $con->real_escape_string($_POST['lname']);
        $add1 = $con->real_escape_string($_POST['add1']);
		$add2 = $con->real_escape_string($_POST['add2']);
		$add3 =$con->real_escape_string($_POST['add3']);
		$phone =$con->real_escape_string($_POST['phone']);
        $con->query("INSERT INTO profile (userID,firstName,middleName,lastName,add1,add2,add3,phone) VALUES ('$id','$fname','$mname','$lname','$add1','$add2','$add3','$phone')");
		$query = "SELECT * FROM profile WHERE userID='$id'";
		$ses_sql = mysqli_query($con,$query);
		$row = mysqli_fetch_assoc($ses_sql);
		$sql2 =$row['ID'];
		$con->query("UPDATE userdata SET`verification`= '1' WHERE `userID`='".$id."'");
		header("location: createBusinessProfile.php");
	
		
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

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />

	<title>Sign Up</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>

	<style>
    #map {
  height: 100%;
}
</style>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Owner's Profile</h1>
							<p class="lead">
								Please, provide all details needed below.
							</p>
						</div>

						<div class="card">
						<input class="form-control form-control-lg" type="hidden"  value="<?php echo $id;?>" readonly=""/>
							<div class="card-body">
								<div class="m-sm-4">
									<form action="" method="post" enctype="multipart/form-data">
									<div class="mb-3">
											<label class="form-label">Owner's Name</label>
                                            <div class="row">
                                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="fname" placeholder="First Name" />
                                                </div>
                                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="mname" placeholder="Middle Name" />
                                                </div>
                                                <div class="col-4">
                                                <input class="form-control form-control-lg" type="text" name="lname" placeholder="Last Name" />
                                                </div>
										
                                            </div>
											</div>
										
									<div class="mb-3">
											<label class="form-label">Owner's Address</label>
											<input class="form-control form-control-lg mb-1" type="text" name="add1" placeholder="House No./Unit No." />
											<label for="cars"  class="form-label">Barangay</label>
  <select name="add2" id="cars" class="form-control form-control-lg mb-3">
   <?php
   	$sql = "SELECT * FROM city";
	   $result = $con->query($sql);
 	if($result->num_rows > 0) {
		//output data
		while($row = $result->fetch_assoc()) {

			echo '<option value="'.$row["city"].'">'.$row["city"].'</option>';

			}
			
	}
 	//Close   
   ?>

  </select>
											<input class="form-control form-control-lg" type="text" name="add3" Value="Tarlac City" readonly	/>
										</div>
										
										<div class="mb-3">
											<label class="form-label">Telephone/Mobile Number</label>
											<input class="form-control form-control-lg" type="text" name="phone"  />
										</div>
										
										<div class="text-center mt-3">
											<button type="submit" name="create" class="btn btn-lg btn-primary">Submit</button> 
										</div>
									</form>
								
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

        
	</main>
    

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