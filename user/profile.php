<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
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
            <a href="welcome.php">Dashboard</a>
            <a href="../php/logout.php">Logout</a>
       </div>
    </nav>
<!-- make a form profle + sql table. this for just creating profile. then create conditional for the dashboard either blur or just put some default values -->
    <div id="content">
        <div class="container">
        <div class="intro">
                <p class="title">Profile</p>
                <p class="sentence">Enter your informations to make a profile</p>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="name">Name</label>
                <input type="text" id="email" name="email" placeholder="Email Address" value=<?php echo $email; ?>>
                <div class="error"></div>

                <label for="password">Password</label>
                <input type="text" id="password" name="password" placeholder="Password" value=<?php echo $password; ?>>
                <div class="error"></div>

                <label for="cPassword">Confirm Password</label>
                <input type="text" id="cPassword" name="cPassword" placeholder="Confirm Password" value=<?php echo $cPassword; ?>>
                <div class="error"></div>

                <input type="submit" value="Create">
            </form>
        </div>
    </div>
</body>
</html>