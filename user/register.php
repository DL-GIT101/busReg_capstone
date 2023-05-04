<?php

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

require_once "../php/config.php";

$email = $email_err = $password = $pword_error = $cPassword = $cPassword_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// email
    $email = validate($_POST["email"]);
        if(empty($email)){
            $email_err = "Enter Email";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email_err = "Invalid Email";
        } else{

                $sql = "SELECT email FROM users WHERE email = ?";

                if($stmt = $mysqli->prepare($sql)){
                    $stmt->bind_param("s", $param_email);

                    $param_email = validate($_POST["email"]);

                    if($stmt->execute()){

                        $stmt->store_result();

                        if($stmt->num_rows() == 1){
                            $email_err = "Email was already taken";
                        }else {
                            $email = validate($_POST["email"]);
                        }
                    }else {
                        echo "email error";
                    }

                    $stmt->close();
                }
            }

//password
    $password = validate($_POST["password"]);
    if(empty($password)){
        $pword_error = "Enter Password";
    }else{
        if(!preg_match('@[A-Z]@', $password)){
            $pword_error .= "Password must include at least one uppercase letter <br>";
        }
        if(!preg_match('@[a-z]@', $password)){
            $pword_error .= "Password must include at least one lowercase letter  <br>";
        }
        if(!preg_match('@[0-9]@', $password)){
            $pword_error .= "Password must include at least one number  <br>";
        }
        if(!preg_match('@[^\w]@', $password)){
            $pword_error .= "Password must include at least one special character  <br>";
        }
        if(strlen($password)<8){
            $pword_error .= "Password must be at least 8 characters in length  <br>";
        }
        if(empty($pword_error)){
            $pwordHash = password_hash($password, PASSWORD_DEFAULT);
        }
    }
//confirm password
    $cPassword = validate($_POST["cPassword"]);
    if(empty($cPassword)){
        $cPassword_error = "Enter confirm password";
        if(empty($pword_error)){
            $pword_error = "Confirm password is empty";
        }
    }else{
        if(!empty($pword_error)){
            $cPassword_error = "Invalid Password";
        }else{
            if($password!==$cPassword){
                $cPassword_error = "Password and Confirm Password do not match";
            }
        }
    }

//id  ex. US20230503001

    if(empty($email_err) && empty($pword_error) && empty($cPassword_error)){

        $sql = "SELECT MAX(id) as max_id FROM users";
        
        $stmt = $mysqli->prepare($sql);

        if($stmt){

            $stmt->execute();
            $stmt->bind_result($max_id);

            if($stmt->fetch()){
                $last_id = $max_id;
            }

            $stmt->close();
        }

        if($last_id){
            $id_suffix = substr($last_id, 10) + 1;
        }

        $id_suffix = str_pad($id_suffix, 3, '0', STR_PAD_LEFT);
        $id_prefix = 'US' . date('Ymd');

        $id = $id_prefix . $id_suffix; 
    }    

    if(!empty($id)){

        $sql = "INSERT INTO users (id, email, password) VALUES (?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){

            $stmt->bind_param("sss",$param_id, $param_email, $param_pword);

            $param_id = $id;
            $param_email = $email;
            $param_pword = password_hash($password, PASSWORD_DEFAULT);

            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "error in insertion";
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
             <a href="login.php">Login</a>

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
                <input type="email" id="email" name="email" placeholder="Email Address" value=<?php echo $email; ?>>
                <div class="error"><?php echo $email_err; ?></div>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" value=<?php echo $password; ?>>
                <div class="error"><?php echo $pword_error; ?></div>

                <label for="cPassword">Confirm Password</label>
                <input type="password" id="cPassword" name="cPassword" placeholder="Confirm Password" value=<?php echo $cPassword; ?>>
                <div class="error"><?php echo $cPassword_error; ?></div>

                <input type="submit" value="Sign up">
                <a href="login.php">Have an account? Click Here</a>
            </form>
        </div>
     </div>

</body>
</html>