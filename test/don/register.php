<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /user/dashboard.php");
    exit;
}
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");

$email = $fname = $mname = $lname = $suffix = $password = $confirmpassword = $id = "";
$email_error = $fname_error = $mname_error = $lname_error = $password_error = $confirmpassword_error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //EMAIL
        $email = validate($_POST["email"]);
        if(empty($email)){
            $email_error = "Enter Email";
        }else{
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $email_error = "Invalid Email";
            }else{
                $emailSQL = "SELECT * FROM user WHERE email = '$email'";
                $emailRESULT = $conn->query($emailSQL);
                    if($emailRESULT->num_rows==1){
                        $email_error = "Email was already taken";
                    }
            }
        }
    //FIRST NAME
        $fname = validate($_POST["fname"]);
        if(empty($fname)){
            $fname_error = "Enter First Name";
        }else{
            if(!preg_match("/^[a-zA-Z- ]*$/", $fname)){
                $fname_error = "Only Letters and Spaces are allowed";
            }else{
                $fnameUP = strtoupper($fname);
            }
        }
    //MIDDLE NAME
        $mname = validate($_POST["mname"]);
        if(empty($mname)){
            $mnameUP = " ";
        }else{
            if(!preg_match("/^[a-zA-Z- ]*$/", $mname)){
                $mname_error = "Only Letters and Spaces are allowed";
            }else{
                $mnameUP = strtoupper($mname);
            }
        }
    //LAST NAME
        $lname = validate($_POST["lname"]);
        if(empty($lname)){
            $lname_error = "Enter Last Name";
        }else{
            if(!preg_match("/^[a-zA-Z- ]*$/", $lname)){
                $lname_error = "Only Letters and Spaces are allowed";
                
            }else{
                $lnameUP = strtoupper($lname);
            }
        }
    //SUFFIX
        if(empty($_POST["suffix"])){
            $suffix_error = "Choose your Suffix";
        }else{
            $suffix = $_POST["suffix"];
        }
   
    //PASSWORD
        $password = validate($_POST["password"]);
        if(empty($password)){
            $password_error = "Enter Password";
        }else{
            if(!preg_match('@[A-Z]@', $password)){
                $password_error .= "Password must include at least one uppercase letter \n";
            }
            if(!preg_match('@[a-z]@', $password)){
                $password_error .= "Password must include at least one lowercase letter \n";
            }
            if(!preg_match('@[0-9]@', $password)){
                $password_error .= "Password must include at least one number \n";
            }
            if(!preg_match('@[^\w]@', $password)){
                $password_error .= "Password must include at least one special character \n";
            }
            if(strlen($password)<8){
                $password_error .= "Password must be at least 8 characters in length \n";
            }
            if(empty($password_error)){
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            }
        }
    //CONFIRM PASSWORD
        $confirmpassword = validate($_POST["confirmpassword"]);
        if(empty($confirmpassword)){
            $confirmpassword_error = "Enter confirm password";
            if(empty($password_error)){
                $password_error = "Confirm password is empty";
            }
        }else{
            if(!empty($password_error)){
                $confirmpassword_error = "Invalid Password";
            }else{
                if($password!==$confirmpassword){
                    $confirmpassword_error = "Password and Confirm Password do not match";
                }
            }
        }
    //ID
        $idSQL = "SELECT * FROM user ORDER BY ID DESC LIMIT 1";
        date_default_timezone_set("Asia/Manila");
        $idRESULT = $conn->query($idSQL);
        $idROW = $idRESULT->fetch_assoc();

        if(isset($idROW["ID"])){
            $lastUserID = $idROW["ID"];
        }else{
            $lastUserID = 0;
        }
        $idEND = substr($lastUserID,10,13);
        $idENDint = (int)$idEND;

        if(substr($lastUserID,2,8)==date("Ymd")){
            ++$idENDint;
            $idEnd = str_pad($idENDint,3,"0",STR_PAD_LEFT);
            $id = "US" . date("Ymd") . $idEnd;
        }else{
            $id = "US" . date("Ymd") . "000";
        }
        
    // INSERT USER INFORMATION
        if(empty($email_error) && empty($fname_error) && empty($mname_error) && empty($lname_error) && empty($suffix_error) && empty($password_error) && empty($confirmpassword_error)){
            $sqlUser = "INSERT INTO user (ID, first, middle, last, suffix, email, password, type) VALUES ('$id', '$fnameUP', '$mnameUP', '$lnameUP', '$suffix', '$email', '$passwordHash','user')";
            if($conn->query($sqlUser) === TRUE){ 
                $dirname = "uploads/".$id;
                mkdir($dirname);
                $register = "SUCCESS";
                $message = "Your account has been succesfully registered";
            }else{
                echo "Error registering account";
            } 
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
    <title>REGISTER</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">

    <script>
         var application = "<?php echo $register; ?>";
        $(document).ready(function(){
            if(application=="SUCCESS"){
                $('#noticeModal').modal('show')
            }    
        }); 
    </script>

</head>

<body class="bg-light">
    <!--NOTICE MODAL -->
        <div class="modal fade" id="noticeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">NOTICE</h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert"><?php echo $message; ?></div>
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-secondary">OK</a>
                </div>
                </div>
            </div>
        </div>

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
                        <a href="login.php" class="btn btn-light">Login</a>
                    </div>
            </div>
        </nav>
<div class="container-fluid">
 <div class="row">
    <div class="col-lg-3"></div>
    <!--FORM -->
        <div class="col-lg-6 py-4">
            <div class="card bg-light shadow mt-5">
                <div class="card-header"><h5 class="card-title text-center">Account Registration</h5></div>
                    <div class="card-body">
                        
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!--EMAIL -->
                    <div class="form-group">
                        <label for="email">Email<small class="text-danger font-weight-bold"> *</small></label>
                        <input type="text" class="form-control <?php echo(!empty($email_error))? 'is-invalid' : '' ;?>" value="<?php echo $email;?>" name="email"> 
                        <div class="invalid-feedback"><?php echo $email_error; ?></div>
                    </div>
                    <div class="form-row">
                <!--FIRST NAME -->
                    <div class="form-group col-sm">
                        <label for="fname">First Name<small class="text-danger font-weight-bold"> *</small></label>
                        <input type="text" class="form-control <?php echo(!empty($fname_error))? 'is-invalid' : '' ;?>" value="<?php echo $fname;?>"  name="fname"> 
                        <div class="invalid-feedback"><?php echo $fname_error; ?></div>
                    </div>
                <!--MIDDLE NAME -->
                    <div class="form-group col-sm">
                        <label for="mname">Middle Name</label>
                        <input type="text" class="form-control col <?php echo(!empty($mname_error))? 'is-invalid' : '' ;?>" value="<?php echo $mname;?>" name="mname"> 
                        <div class="invalid-feedback"><?php echo $mname_error; ?></div>
                    </div>
                <!--LAST NAME -->
                    <div class="form-group col-sm">
                        <label for="lname">Last Name<small class="text-danger font-weight-bold"> *</small></label>
                        <input type="text" class="form-control <?php echo(!empty($lname_error))? 'is-invalid' : '' ;?>" value="<?php echo $lname;?>" name="lname"> 
                        <div class="invalid-feedback"><?php echo $lname_error; ?></div>
                    </div>
                <!--SUFFIX -->
                    <div class="form-group col-sm">
                        <label for="labelsuffix">Suffix<small class="text-danger font-weight-bold"> *</small></label>
                        <select class="form-control <?php echo(!empty($suffix_error))? 'is-invalid' : '' ;?>" name="suffix"> 
                            <option value="" selected disabled>Choose</option>
                            <option value=" " <?php if($suffix=="N/A"){ echo ' selected="selected"';} ?>>N/A</option>
                            <option value="Jr." <?php if($suffix=="JUNIOR"){ echo ' selected="selected"';} ?>>Jr.</option>
                            <option value="Sr." <?php if($suffix=="SENIOR"){ echo ' selected="selected"';} ?>>Sr.</option>
                            <option value="II" <?php if($suffix=="II"){ echo ' selected="selected"';} ?>>II</option>
                            <option value="III" <?php if($suffix=="III"){ echo ' selected="selected"';} ?>>III</option>
                            <option value="IV" <?php if($suffix=="IV"){ echo ' selected="selected"';} ?>>IV</option>
                            <option value="V" <?php if($suffix=="V"){ echo ' selected="selected"';} ?>>V</option>
                        </select>
                        <div class="invalid-feedback"><?php echo $suffix_error; ?></div>
                    </div>
                    </div>
                <!--PASSWORD -->
                    <div class="form-group">
                        <label for="labelpasssword">Password</label><small class="text-danger font-weight-bold"> *</small>
                        <input type="password" class="form-control <?php echo(!empty($password_error))? 'is-invalid' : '' ;?>" name="password" id="password"> 
                        <div class="invalid-feedback"><?php echo nl2br($password_error); ?></div>
                    </div>
                <!--CONFIRM PASSWORD -->
                    <div class="form-group">
                        <label for="labelconfirmpassword">Confirm Password</label><small class="text-danger font-weight-bold"> *</small>
                        <input type="password" class="form-control  <?php echo(!empty($confirmpassword_error))? 'is-invalid' : '' ;?>" name="confirmpassword" id="confirmpassword"> 
                        <div class="invalid-feedback"><?php echo $confirmpassword_error; ?></div>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" onclick="hidePassword()" type="checkbox" id="showpass" value="option1">
                            <label class="form-check-label" for="showpass">Show Password</label>
                        </div>
                <!--SUBMIT -->
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary"  value="Submit">
                        </div>
                </form> 
                </div>
                <div class="card-footer text-center">Already have an account?<a class="text-warning" href="login.php"> Login</a></div>
            </div>
        </div>

    <div class="col-lg-3"></div>
 </div>
</div>
<script>
    function hidePassword() {
        const x = document.getElementById("password");
        const y = document.getElementById("confirmpassword");
            if(x.type === "password"){
                x.type = "text";
                y.type = "text";
            }else{
                x.type = "password";
                y.type = "password";
            }
    }
</script>
</body>
</html>