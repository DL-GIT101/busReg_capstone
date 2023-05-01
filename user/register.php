

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Register</title>
</head>
<body>
    <nav id="navbar">
        <div id="logo">
         <a href="../index.php">
             <img src="../img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
             <p>Business Permit & Licensing</p>  
         </a>
        </div>
 
        <div id="user">
             <a href="">Login</a>
        </div>
     </nav>
    
     <div id="content">
        
        <div class="container">
            <div class="intro">
                <p class="title">Create an Account</p>
                <p class="sentence">Please enter your email and password to create an account.</p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address">

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password">

                <label for="cPassword">Confirm Password</label>
                <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password">

                <input type="submit" value="Sign up">
                <a href="login.php">Have an account? Click Here</a>
            </form>
        </div>
     </div>

</body>
</html>