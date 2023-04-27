<?php
  include("connection.php");
 if(isset($_POST['create'])){
        $email = $con->real_escape_string($_POST['email']);
        $password = $con->real_escape_string($_POST['password']);
		$cpassword = $con->real_escape_string($_POST['cpassword']);
		 if ($password != $cpassword){
			echo '<script type="text/javascript">alert("Password mismatch, Please try again");</script>';
		 }else{
            $sql = $con->query("SELECT Email FROM userdata WHERE Email='$email'");
            if ($sql->num_rows >0){
                echo '<script type="text/javascript">alert("Email is already in use");</script>';
            }else{
                $hashedPassword =password_hash($password, PASSWORD_DEFAULT);
                $con->query("INSERT INTO userdata (Email,Password,userRole) VALUES ('$email','$hashedPassword','Business')");
                $query = "SELECT userID FROM userdata WHERE Email='$email'";
                echo '<script type="text/javascript">alert("Register Successful");</script>';
			
            } 
			
        }
		
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
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Get started</h1>
							<p class="lead">
								Please provide all necessary details
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-4">
									<form action="" method="post">
										
										
									
										<div class="mb-3">
											<label class="form-label">Email</label>
											<input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email"required=""data-validation="email" data-validation-has-keyup-event="true />
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" minlength="8" maxlength="128" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
											title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters" required="" />
										</div>
										<div class="mb-3">
											<label class="form-label">Confirm Password</label>
											<input class="form-control form-control-lg" type="password" name="cpassword" placeholder="Confirm password" />
										</div>
										<div class="text-center mt-3">
											<button type="submit" name="create" class="btn btn-lg btn-primary">Sign up</button> 
										</div>
									</form>
									<div class="text-center mt-3">
											<a href="index.php">Already Have an Account.</a>
										</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

        
	</main>
    

	<script src="js/app.js"></script>

</body>

</html>