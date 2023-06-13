<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== "admin"){
    header("location: ../../login.php");
    exit;
}

require_once "../../php/connection.php";
require_once "../../php/validate.php";

$hidden = "hidden";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// email
$email = validate($_POST["email"]);
    if(empty($email)) {
            $email_err = "Enter Email";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid Email";
    } else {
        $sql = "SELECT email FROM users WHERE email = ?";
        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = validate($_POST["email"]);

            if($stmt->execute()) {
                $stmt->store_result();

                    if($stmt->num_rows() == 1) {
                        $email_err = "Email was already taken";
                    } else {
                        $email = validate($_POST["email"]);
                    }

            } else {
                echo "Error Getting Email";
            }
        $stmt->close();
        }
    }

//password
$password = validate($_POST["password"]);
    if(empty($password)) {
        $pword_error = "Enter Password";
    } else {
        if(!preg_match('@[A-Z]@', $password)) {
            $pword_error .= "Password must include at least one uppercase letter <br>";
        }
        if(!preg_match('@[a-z]@', $password)) {
            $pword_error .= "Password must include at least one lowercase letter  <br>";
        }
        if(!preg_match('@[0-9]@', $password)) {
            $pword_error .= "Password must include at least one number  <br>";
        }
        if(!preg_match('@[^\w]@', $password)) {
            $pword_error .= "Password must include at least one special character  <br>";
        }
        if(strlen($password)<8) {
            $pword_error .= "Password must be at least 8 characters in length  <br>";
        }
        if(empty($pword_error)) {
            $pwordHash = password_hash($password, PASSWORD_DEFAULT);
        }
    }
//confirm password
$cPassword = validate($_POST["cPassword"]);
    if(empty($cPassword)) {
        $cPassword_error = "Enter confirm password";
        if(empty($pword_error)) {
            $pword_error = "Confirm password is empty";
        }
    } else {
        if(!empty($pword_error)) {
            $cPassword_error = "Invalid Password";
        } else {
            if($password!==$cPassword){ 
                $cPassword_error = "Password and Confirm Password do not match";
            }
        }
    }

//ID  ex. US20230503001
if(empty($email_err) && empty($pword_error) && empty($cPassword_error)) {
    $sql = "SELECT id as max_id FROM users ORDER BY id DESC LIMIT 1";

    if($stmt = $mysqli->prepare($sql)) {
        if($stmt->execute()){  
            $stmt->bind_result($max_id);

            if($stmt->fetch()) {
                $last_id = $max_id;
            }
        }
    $stmt->close();
    }

    if($last_id) {
        $date = substr($last_id, 2, -3);
        $today = date('Ymd');

        if($date === $today) {
            $id_suffix = substr($last_id, 10) + 1;
        }else {
            $id_suffix = 0;
        }
    }

    $id_suffix = str_pad($id_suffix, 3, '0', STR_PAD_LEFT);
    $id_prefix = 'US' . $today;
    $id = $id_prefix . $id_suffix; 
}    

if(!empty($id)) {
    $sql = "INSERT INTO users (id, email, password) VALUES (?, ?, ?)";

    if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("sss",$param_id, $param_email, $param_pword);

            $param_id = $id;
            $param_email = $email;
            $param_pword = password_hash($password, PASSWORD_DEFAULT);

            if($stmt->execute()) {
                $directory = '../user/upload/'. $id;
                mkdir($directory, 0777, true);

                $title = "Registration Successful";
                $message = "Account has been successfully created <br>";
                $button = '<a href="users.php">Go to Management</a>';

                $status = "success";
                $hidden = "";
            } else {
                $title = "Registration Fail";
                $message = "Try again later <br>";
                $button = '<a href="users.php">OK</a>';

                $status = "fail";
                $hidden = "";
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
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/script.js" defer></script>
    <script src="../../js/form.js" defer></script>
    <title>Add MSME</title>
</head>
<body>
<modal class="<?= $hidden ?>">
        <div class="content <?= $status ?>">
            <p class="title"><?= $title ?></p>
            <p class="sentence"><?= $message ?></p>
            <?= $button ?>
        </div>
</modal>
<nav>
        <div id="nav_logo">
                <img src="../../img/Tarlac_City_Seal.png" alt="Tarlac City Seal">
                <p>Tarlac City BPLO - ADMIN</p>  
        </div>
        <div id="account">
             <a href="../../php/logout.php">Logout</a>
        </div>
</nav>

<div class="flex">

    <nav id="sidebar">
        <ul>
            <li ><img src="../../img/dashboard.png" alt=""><a href="../dashboard.php">Dashboard</a></li>
            <li class="current"><img src="../../img/register.png" alt=""><a href="users.php">MSME Management</a></li>
            <li><img src="../../img/list.png" alt=""><a href="../permit/msme.php">MSME Permit</a></li>
        </ul>
    </nav>

    <main class="flex-grow-1 flex-wrap content-center"> 
    <div class="actions space-between">
            <p id="page" class="title">Add Account</p>
            <div class="buttons">
                <a href="users.php" class="back">Management</a>
            </div>
        </div>

        <div class="column_container">      
            <div class="text-center">
                <p class="title">Create an Account</p>
                <p class="sentence">Enter email and password to create an account.</p>
            </div>

            <form autocomplete="off" method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address" value=<?= $email; ?>>
                <div class="error_msg"><?= $email_err; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?= $password; ?>>
                <div class="error_msg"><?= $pword_error; ?></div>

                <label for="cPassword">Confirm Password</label>
                <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password" value=<?= $cPassword; ?>>
                <div class="error_msg"><?= $cPassword_error; ?></div>

                <input type="submit" value="Create">
            </form>
        </div>
     </main>
</div>

</body>
</html>