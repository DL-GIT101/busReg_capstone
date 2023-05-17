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
        <table>
            <tr>
                <th>Requirement</th>
                <th>Uploaded File</th>
                <th>Delete</th>
                <th>Status</th>
                <th>File Upload</th>
            </tr>
            <tr>
                <td>Barangay Clearance for business</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="barangay_clearance" name="barangay_clearance">
                </td>
            </tr>
            <tr>
                <td>DTI Certificate of Registration</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="dti" name="dti">
                </td>
            </tr>
            <tr>
                <td>On the Place of Business</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="place" name="place">
                </td>
            </tr>
            <tr>
                <td>Community Tax Certificate</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="community_tax" name="community_tax">
                </td>
            </tr>
            <tr>
                <td>Certificate of Zoning Compliance</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="zoning_compliance" name="zoning_compliance">
                </td>
            </tr>
            <tr>
                <td>Valid Fire Safety Inspection Certificate/Official Receipt</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="fire_saafety" name="fire_saafety">
                </td>
            </tr>
            <tr>
                <td>Sanitary Permit</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="sanitary_permit" name="sanitary_permit">
                </td>
            </tr>
            <tr>
                <td>Environmental Compliance Clearance</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="environment_compliance" name="environment_compliance">
                </td>
            </tr>
            <tr>
                <td>Latest 2X2 picture</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="twoByTwo" name="twoByTwo">
                </td>
            </tr>
            <tr>
                <td>Barangay Clearance for business</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="barangay_clearance" name="barangay_clearance">
                </td>
            </tr>
            <tr>
                <td>Tax Order of Payment</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="tax_order" name="tax_order">
                </td>
            </tr>
            <tr>
                <td>Tax Order of Payment Official Receipt</td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <input type="file" id="tax_order_or" name="tax_order_or">
                </td>
            </tr>
        </table>
        <input type="submit" value="Upload">
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