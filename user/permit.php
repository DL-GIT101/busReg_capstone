<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>New Permit</title>
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
        <p class="title">New Business</p>
        <p class="sentence">Please upload the photos of the following requirements</p>
    </div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label for="logo">Logo</label>
        <input type="file" id="logo" name="logo">
        <div class="error"><?= $logo_err; ?></div>

        <input type="submit" value="Upload Files">
    </form>       

    </div>
</div>

<!--
    Create a  new style for images upload as form will be in table
    1. Barangay Clearance for business
    2. DTI Certificate of Registration
    3. On the Place of Business 	 
        - Building/Occupancy Certificate, if owned	
        - Lease of Contract, if rented	 
        - Notice of Award/Award Sheet, if inside a Mall
        - Homeowner’s/Neighborhood Certification of No Objection, if inside a subdivision or housing facility
    4. Community Tax Certificate
    5. Certificate of Zoning Compliance
    6. Business Inspection Clearance
    7. Valid Fire Safety Inspection Certificate/Official Receipt
    8. Sanitary Permit
    9. Environmental Compliance Clearance
    10. Latest 2×2 picture
    11. Tax Order of Payment
    12. Tax Order of Payment Official Receipt
 -->
</body>
</html>