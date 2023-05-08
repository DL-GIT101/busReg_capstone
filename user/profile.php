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

    <div id="content">
        <div class="container">
            <div class="intro">
                <p class="title">Profile</p>
                <p class="sentence">Enter your informations to make a profile</p>
            </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="frame">
                    <p class="title">Business</p>
                    <label for="bus_name">Name</label>
                    <input type="text" id="bus_name" name="bus_name" placeholder="Business Name" value="">
                    <div class="error"></div>

                    <label for="activity">Activity</label>
                    <input type="text" id="activity" name="activity" placeholder="Business Activity" value="">
                    <div class="error"></div>

                    <label for="entity">Entity</label>
                    <input type="text" id="entity" name="entity" placeholder="Business Entity" value="">
                    <div class="error"></div>

                    <label for="contact">Contact Number</label>
                    <input type="text" id="contact" name="contact" placeholder="Contact Number" value="">
                    <div class="error"></div>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Business Email Address" value=<?php echo $email; ?>>
                    <div class="error"><?php echo $email_err; ?></div>

                    <label for="address_1">House No./Unit No./Building/Street</label>
                    <input type="text" id="address_1" name="address_1" placeholder="House No./Unit No./Building/Street" value="">
                    <div class="error"></div>

                    <label for="address_2">Barangay</label> <!-- Gawing select -->
                    <input type="text" id="address_2" name="address_2" placeholder="Barangay" value="">
                    <div class="error"></div>
                </div>
                    <input type="submit" value="Create">
                </form>
        </div>
    </div>
</body>
</html>

<!-- manager  - [name - gender - nationality - bus_entity - position] 
    business_profile - [name - activity - logo -  contact_no - email - add_line_1 - add_line_2 - pin]
     ->