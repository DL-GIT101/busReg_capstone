<?php 
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /user/dashboard.php");
    exit;
}else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === "admin"){
    header("location: adminDashboard.php");
}

define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

    $email = $password = "";
    $email_error = $password_error = $login_error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //EMAIL
            if(empty(validate($_POST["email"]))){
                $email_error = "Please enter your email";
            }
            else{
                $email = validate($_POST["email"]);
            }
        //PASSWORD
            if(empty(validate($_POST["password"]))){
                $password_error = "Please enter your password";
            }
            else{
                $password = validate($_POST["password"]);
            }

        if(empty($email_error) && empty($password_error)){
            $loginSQL = "SELECT * FROM user WHERE email = '$email'";
            $loginRESULT = $conn->query($loginSQL);
            $loginROW = $loginRESULT->fetch_assoc();

            
            
            if($loginRESULT->num_rows==1){
                if(password_verify($password, $loginROW["password"])){
                        session_start();

                        $_SESSION["name"] = $loginROW["first"];
                        $_SESSION["IDuser"] = $loginROW["ID"];

                        if($loginROW["type"]=="admin"){
                            $_SESSION["loggedin"] = $loginROW["type"];
                            header("location: /admin/dashboard.php");
                        }else{
                            $_SESSION["loggedin"] = true;
                            header("location: /user/dashboard.php");
                        }    
                }else{
                    $login_error = "Incorrect email or password";
                }
            }else{
                $login_error = "Incorrect email or password";
            }
        }else{
            $login_error = "Enter both email and password";
        }
    $conn->close();
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <title>LOGIN</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">

</head>
<body class="bg-light">
    <!--Navigation Bar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="index.php"><img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">Tarlac City BPLS</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="/user/permit/panel.php">Business Permit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                </ul>
                        <div>
                            <a href="register.php" class="btn btn-warning">Register</a>
                        </div>
            </div>
    </nav>
  
<div class="container-fluid">
    <div class="row">
        <div class="col"></div>
        <!--FORM -->
        <div class="col-md py-5">
            <div class="card bg-light shadow mt-5">
                <div class="card-header"><h5 class="card-title text-center">Login</h5></div>
                    <div class="card-body">
                        <?php if(!empty($login_error)){
                                echo '<div class="alert alert-danger">' . $login_error . '</div>';
                            }?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!--EMAIL -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control <?php echo(!empty($email_error))? 'is-invalid' : '' ;?>" value="<?php echo $email;?>" name="email"> 
                            <div class="invalid-feedback"><?php echo $email_error; ?></div>
                        </div>
                    <!--PASSWORD -->
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control <?php echo(!empty($password_error))? 'is-invalid' : '' ;?>" name="password" id="password"> 
                            <div class="invalid-feedback"><?php echo ($password_error); ?></div>
                            <input type="checkbox" onclick="hidePassword()"> Show Password
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary"  value="Login">
                        </div></form> 
                    </div>
                        <div class="card-footer text-center">Don't have an account?<a class="text-warning" href="register.php"> Register</a></div>
            </div>
        </div>

        <div class="col"></div>
    </div>
</div>

<script>
    function hidePassword() {
        const x = document.getElementById("password");
        if(x.type === "password"){
            x.type = "text";
        }else{
            x.type = "password";
        }
    }
</script>
</body>
</html>