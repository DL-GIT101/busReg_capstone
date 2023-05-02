<?php

$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// email
    $email = validate($_POST["email"]);
        if(empty($email)){
            $email_error = "Enter Email";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $email_error = "Invalid Email";
            }else{ 
               /* $emailSQL = "SELECT * FROM user WHERE email = '$email'";
                $emailRESULT = $conn->query($emailSQL);
                    if($emailRESULT->num_rows==1){
                        $email_error = "Email was already taken";
                    } */
            }
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/form.js" defer></script>
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
                <input type="email" id="email" name="email" placeholder="Email Address" value="asds" <?php echo $email; ?>>
                <div class="error"></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password">
                <div class="error"></div>

                <label for="cPassword">Confirm Password</label>
                <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password">
                <div class="error"></div>

                <input type="submit" value="Sign up">
                <a href="login.php">Have an account? Click Here</a>
            </form>
        </div>
     </div>
</body>
</html>