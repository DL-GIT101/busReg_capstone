<?php 
session_start();
require_once "php/connection.php";
require_once "php/validate.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: user/dashboard.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
//email
    if(empty(validate($_POST["email"]))) {
        $email_err = "Please enter your email"; 
    } else {
        $email = validate($_POST["email"]);
    }
//password
    if(empty(validate($_POST["password"]))) {
        $pword_error = "Please enter your password";
    }
    else {
        $password = validate($_POST["password"]);
    }

    if(empty($email_err) && empty($pword_error)) {

        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_email);

            $param_email = $email;

            if($stmt->execute()) {
                $stmt->store_result();

                if($stmt->num_rows === 1) {
                    $stmt->bind_result($id,$email,$hashed_pword);

                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_pword)){
                            session_start();

                            $_SESSION["id"] = $id;

                            if($_SESSION["id"] === "ADMIN"){
                                $_SESSION["role"] = "admin";
                                header("location: admin/dashboard.php");
                            }else{
                                $_SESSION["role"] = "user";
                                header("location: user/dashboard.php");
                            }

                        }else{
                            $login_err = "Invalid email or password";
                        }
                    }
                }else{
                    $login_err = "Invalid email or password";
                }
            }else{
                echo "Error executing query";
            }
        $stmt->close();
        }
    }
    
}

$mysqli->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- Javascript -->
    <script src="js/script.js" defer></script>
    <script src="js/form.js" defer></script>
    <title>Login</title>
</head>
<body>

    <nav>
        <div class="logo">
            <img src="img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="user/register.php">Register</a></li>
            </ul>
        </div>
    </nav>

    <main>
        <div class="column-container">   

            <div>
                <p class="title text-center">Welcome</p>
                <p class="sentence">Sign in to your account to continue</p>
            </div>

            <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                <div class="error-alert hidden"><?= $login_err; ?></div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address" value=<?= $email; ?>>
                <div class="error_msg"><?= $email_err; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?= $password; ?>>
                <div class="error_msg"><?= $pword_error; ?></div>

                <input type="submit" value="Login">
                <a href="user/register.php">Don't have an account? Click Here</a>
            </form>

        </div>
    </main>
</body>
</html>