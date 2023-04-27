<?php 
session_start();
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
    <title>WELCOME</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">
</head>

<body class="bg-light">
    <!--Navigation Bar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <a class="navbar-brand" href="index.php"><img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt=""> Tarlac City BPLS</a>
        
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
                    <?php
                    if(isset($_SESSION["loggedin"])){echo '
                        <div class="mr-2">
                            <a href="userdashboard.php" class="btn btn-light"> Hi, '.$_SESSION["name"]  .'</a>
                        </div>
                        <div class="my-2 my-sm-0">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>';
                    }else{ echo  '
                        <div class="mr-2">
                            <a href="login.php" class="btn btn-light">Login</a>
                        </div>
                        <div class="my-2 my-sm-0">
                            <a href="register.php" class="btn btn-warning">Register</a>
                        </div>';
                    }
                    ?>
            </div>            
        </nav>
  
    <h1 class="text-center">WELCOME</h1>

  </body>