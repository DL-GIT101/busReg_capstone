<?php
session_start();
require_once "../php/connection.php";
require_once "../php/functions.php";

if($_SESSION["role"] === "user") {
    header("location: dashboard.php");
    exit;
}elseif($_SESSION["role"] === "admin") {
    header("location: ../admin/dashboard.php");
    exit;
}

$modal_display = "hidden";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];
// email
    $email = validate(($_POST["email"]));

    if(empty($email)) {
        $errors["email"] = "Please enter your email"; 
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid Email"; 
    } else {

        $sql = "SELECT Email FROM User WHERE Email = ?";
        
        if($stmt = $mysqli->prepare($sql)) {

            $stmt->bind_param("s", $param_email);
            $param_email = validate($_POST["email"]);

            if($stmt->execute()) {
                $stmt->store_result();

                    if($stmt->num_rows() == 1) {
                        $errors["email"] = "Email was already taken";
                    } else {
                        $email = validate($_POST["email"]);
                    }

            } else {
                $modal_title = "Registration Error";
                $modal_message = "Try again later";
                $modal_button = '<a href="../index.php">OK</a>';

                $modal_status = "error";
                $modal_display = "";
            }
        $stmt->close();
        }
    }

//password
$password = validate($_POST["password"]);

    if(empty($password)) {
        $errors["password"] = "Enter Password";
    } else {
        if(!preg_match('@[A-Z]@', $password)) {
            $errors["password"] .= "Password must include at least one uppercase letter <br>";
        }
        if(!preg_match('@[a-z]@', $password)) {
            $errors["password"] .= "Password must include at least one lowercase letter  <br>";
        }
        if(!preg_match('@[0-9]@', $password)) {
            $errors["password"] .= "Password must include at least one number  <br>";
        }
        if(!preg_match('@[^\w]@', $password)) {
            $errors["password"] .= "Password must include at least one special character  <br>";
        }
        if(strlen($password)<8) {
            $errors["password"] .= "Password must be at least 8 characters in length  <br>";
        }
        if(empty($errors["password"])) {
            $pwordHash = password_hash($password, PASSWORD_DEFAULT);
        }
    }
//confirm password
$cPassword = validate($_POST["cPassword"]);
    if(empty($cPassword)) {
        $errors["confirmPassword"] = "Enter confirm password";
    } else {
        if(!empty($pword_error)) {
            $errors["confirmPassword"] = "Invalid Password";
        } else {
            if($password!==$cPassword){ 
                $errors["confirmPassword"] = "Password and Confirm Password do not match";
            }
        }
    }

//ID  ex. U-2023-000-000
if(empty($errors)) {
    $sql = "SELECT UserID as maxID FROM User ORDER BY UserID DESC LIMIT 1";

    if($stmt = $mysqli->prepare($sql)) {

        if($stmt->execute()){  

            $stmt->bind_result($maxID);

            if($stmt->fetch()) {
                $lastID = $maxID;
            }
        }

    $stmt->close();

    }

    $currentYear = date('Y');

    if($lastID) {

        $year = substr($lastID, 2, 4);
        
        $countDash = substr($lastID, 7);
        $count = str_replace("-","",$countDash);

        if($year === $currentYear) {
            $count += 1;
        }else {
            $count = 0;
        }
    }else {
        $count = 0;
    }

    $count = str_pad($count, 6, '0', STR_PAD_LEFT);
    $countDash = substr_replace($count, "-", 3, 0);

    $id = "U-" . $currentYear . "-" . $countDash; 
}    

if(!empty($id)) {

    $sql = "INSERT INTO User (UserID, Email, Password, Role) VALUES (?, ?, ?, ?)";

    if($stmt = $mysqli->prepare($sql)){

    $stmt->bind_param("ssss",$param_id, $param_email, $param_pword,$param_role);

            $param_id = $id;
            $param_email = $email;
            $param_pword = password_hash($password, PASSWORD_DEFAULT);
            $param_role = "Owner";

            if($stmt->execute()) {

                $modal_title = "Registration Successful";
                $modal_message = "Your account has been successfully created <br>";
                $modal_message .= "You can now log in using your credentials";
                $modal_button = '<a href="login.php">Login</a>';

                $modal_status = "success";
                $modal_display = "";
            } else {
                $modal_title = "Registration Fail";
                $modal_message = "Try again later <br>";
                $modal_button = '<a href="../index.php">OK</a>';

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
    <link rel="shortcut icon" href="../img/tarlac-seal.ico" type="image/x-icon">
    <link rel="icon" href="img/tarlac-seal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Javascript -->
    <script src="../js/script.js" defer></script>
    <script src="../js/form.js" defer></script>
    <title>Register</title>
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
            <img src="../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
            <p>Tarlac City Business Permit & Licensing Office</p>  
        </div>
        <img id="toggle" src="../img/navbar-toggle.svg" alt="Navbar Toggle">
        <div class="button-group">
            <ul>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>
     
     <main> 
        <div class="column-container">   

            <div class="text-center">
                <p class="title">Create an Account</p>
                <p class="sentence">Enter your email and password to create an account.</p>
            </div>

            <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address" value=<?= $email; ?>>
                <div class="error-msg"><?= $errors["email"]; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?= $password; ?>>
                <div class="error-msg"><?= $errors["password"]; ?></div>

                <label for="cPassword">Confirm Password</label>
                <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password" value=<?= $cPassword; ?>>
                <div class="error-msg"><?= $errors["confirmPassword"]; ?></div>

                <input type="submit" value="Sign up">
                <a href="login.php">Have an account? Click Here</a>
            </form>

        </div>
     </main>

</body>
</html>