<?php 

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: user/welcome.php");
    exit;
}

require_once "php/config.php";

$email = $email_err = $password = $pword_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
//email
    if(empty(validate($_POST["email"]))){
        $email_err = "Please enter your email"; 
    } else{
        $email = validate($_POST["email"]);
    }
//password
    if(empty(validate($_POST["password"]))){
        $pword_error = "Please enter your password";
    }
    else{
        $password = validate($_POST["password"]);
    }

    if(empty($email_err) && empty($pword_error)){

        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){

            $stmt->bind_param("s", $param_email);

            $param_email = $email;

            if($stmt->execute()){

                $stmt->store_result();

                if($stmt->num_rows == 1){

                    $stmt->bind_result($id,$email,$hashed_pword);

                    if($stmt->fetch()){

                        if(password_verify($password, $hashed_pword)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            // the name before @ as a substitute for username
                            $_SESSION["email"] = substr($email, 0, strpos($email, '@'));

                            header("location: user/welcome.php");
                        }else{
                            $login_err = "Invalid email or password";
                        }
                    }
                }else{
                    $login_err = "Invalid email or password";
                }
            }else{
                echo "error in sql";
            }

            $stmt->close();
        }
    }
        $mysqli->close();
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
    <link rel="stylesheet" href="css/style.css">
    <script src="js/form.js" defer></script>
    <title>Login</title>
</head>
<body>
<nav id="navbar">
       <div id="logo">
        <a href="../index.php">
            <img src="img/Tarlac_City_Seal.png" alt="Tarlac_City_Seal">
            <p>Business Permit & Licensing</p>  
        </a>
       </div>

       <div id="user">
            <a href="user/register.php">Register</a>
       </div>
    </nav>

    <div id="content">
        <div class="container">      
            <div class="intro">
                <p class="title">Welcome</p>
                <p class="sentence">Sign in to your account to continue</p>
            </div>
            <form autocomplete="off" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                <div class="alert"><?php echo $login_err; ?></div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address" value=<?php echo $email; ?>>
                <div class="error"><?php echo $email_err; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?php echo $password; ?>>
                <div class="error"><?php echo $pword_error; ?></div>

                <input type="submit" value="Login">
                <a href="user/register.php">Don't have an account? Click Here</a>
            </form>
        </div>
    </div>
</body>
</html>