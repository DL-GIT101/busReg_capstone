<?php 
session_start();
require_once "php/connection.php";
require_once "php/functions.php";

if($_SESSION["role"] === "Owner") {
    header("location: user/dashboard.php");
    exit;
}elseif($_SESSION["role"] === "Admin") {
    header("location: admin/dashboard.php");
    exit;
}

$modal_display = "hidden";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
    
//email
    $email = validate($_POST["email"]);
    if(empty($email)) {
        $errors["email"] = "Please enter your email"; 
    } else {
        $email = validate($_POST["email"]);
    }
//password
    $password = validate($_POST["password"]);
    if(empty($password)) {
        $errors["password"] = "Please enter your password";
    }
    else {
        $password = validate($_POST["password"]);
    }

    if(empty($errors)) {

        $sql = "SELECT * FROM User WHERE Email = ?";

        if($stmt = $mysqli->prepare($sql)){

            $stmt->bind_param("s", $param_email);

            $param_email = $email;

            if($stmt->execute()) {

                $stmt->store_result();

                if($stmt->num_rows === 1) {

                    $stmt->bind_result($UserID,$email,$hashed_pword,$role);

                    if($stmt->fetch()){

                        if(password_verify($password, $hashed_pword)){

                            $_SESSION["UserID"] = $UserID;
                            $_SESSION["role"] = $role;

    if($role === "Owner"){
        if(hasOwnerProfile($UserID) === true){
            if(hasBusinessProfile($_SESSION["OwnerID"]) === true){
            header("location: user/dashboard.php");
            }else{
            header("location: user/Business/edit_profile.php");
            }
            exit;
        }else{
            header("location: user/Owner/edit_profile.php");
        }
    }else if($role === "Admin"){
        header("location: admin/dashboard.php");
        exit;
    }

    exit;
                        }else{

                            $login_err = "Invalid email or password";
                        }
                    }
                }else{

                    $login_err = "Invalid email or password";
                }
            }else{

                $modal_title = "Login Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="index.php">OK</a>';

                $modal_status = "error";
                $modal_display = "";

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
    <link rel="shortcut icon" href="img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <!-- Javascript -->
    <script src="js/script.js" defer></script>
    <script src="js/form.js" defer></script>
    <title>Login</title>
</head>
<body>

    <modal class="<?= $modal_display ?>">
        <div class="content <?= $modal_status ?>">
            <p class="title"><?= $modal_title ?></p>
            <p class="sentence"><?= $modal_message ?></p>
            <div class="button-group">
                <?= $modal_button ?>
            </div>
        </div>
    </modal>

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
                <div class="error-msg"><?= $errors["email"]; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?= $password; ?>>
                <div class="error-msg"><?= $errors["password"]; ?></div>

                <input type="submit" value="Login">
                <a href="user/register.php">Don't have an account? Click Here</a>
            </form>

        </div>
    </main>
</body>
</html>