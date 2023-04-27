<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
define('ROOT', 'C:\xampp\htdocs\\');
require_once(ROOT. "connect.php");


$IDuser = $_SESSION["IDuser"];

$document = array(
    array('name' => 'Locational Sketch', 'status' => 'None', 'input' => 'sketch'),
    array('name' => 'Location/Zoning/Land Use Clearance', 'status' => 'None', 'input' => 'location'),
    array('name' => 'Business Inspection Clearance', 'status' => 'None', 'input' => 'inspection'),
    array('name' => 'Fire Safety Inspection Certificate', 'status' => 'None', 'input' => 'fire'),
    array('name' => 'FSIC Official Receipt', 'status' => 'None', 'input' => 'fireOR'),
    array('name' => 'Sanitary Permit/Health Certificate', 'status' => 'None', 'input' => 'sanitary'),
    array('name' => 'Philhealth Certificate of Membership', 'status' => 'None', 'input' => 'philhealth'),
    array('name' => 'Pag-Ibig Certificate of Membership', 'status' => 'None', 'input' => 'pagibig'),
    array('name' => '2x2 Picture', 'status' => 'None', 'input' => 'twobytwo'),
    array('name' => 'Barangay Clearance of Business', 'status' => 'None', 'input' => 'barangay'),
    array('name' => 'DTI Certificate of registration', 'status' => 'None', 'input' => 'dti'),
    array('name' => 'Community tax Certificate', 'status' => 'None', 'input' => 'name')
);

// $document[] = array('name' => 'document', 'status' => 'None', 'input' => 'input');



    if($_SERVER["REQUEST_METHOD"] == "POST"){
          
        
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
    <script src="https://kit.fontawesome.com/e7aa253b6e.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title>Upload Documents</title>
    <link rel="icon" href="/images/tarlacCitySeal.ico" type="image/x-icon">
    <script>
        var application = "<?php echo $upload_stat; ?>";

        $(document).ready(function(){
            if(application=="Success"){
                $('#noticeModal').modal('show')
            }     
        }); 
    </script>
</head>
<body class="bg-light">
    <div class="modal fade" id="noticeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">NOTICE</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert"><?php echo $upload_success; ?></div>
            </div>
            <div class="modal-footer">
                <a href="user-BNR.php" class="btn btn-secondary">OK</a>
            </div>
            </div>
        </div>
    </div>
  <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
    <a class="navbar-brand" href="/aaa/index.php">
        <img src="/images/TarlacCitySeal.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Tarlac City BPLS
    </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/user/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/user/permit/form.php">Acquire Permit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Create Profile</a>
                </li>
            </ul>
            <?php if(!empty($_SESSION["name"])){
                echo    '<div class="mr-2">
                        <a href="/user/dashboard.php" class="btn btn-light"> Hi, '.$_SESSION["name"]  .'</a>
                        </div>';
                }   
            ?>
                <div class="my-2 my-sm-0">
                    <a href="/logout.php" class="btn btn-danger">Logout</a>
                </div>
        </div>
        
  </nav>
  <div class="container-fluid">
  <div class="row">
  <div class="col-sm-3"></div>

  <div class="col-sm-6">
    <div class="card mt-5 d-none" id="rejectedCard">
        <div class="card-header text-center"><h5 class="card-title m-0">UPLOADED REQUIREMENT</h5></div>
            <div class="card-body">
                <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Name</th>
                    <th class="text-center" scope="col">View</th>
                    <th class="text-center" scope="col">Download</th>
                    <th scope="col">Annotation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row"><?php echo $row_files["document_type"];?></th>
                    <td><?php echo $row_files["file_name"];?></td>
        <?php echo' <td class="text-center"><a href="uploads/'.$userID.'/'.$row_files['file_name'].'" target="_blank"><i class="bi bi-eye-fill"></i></a></td>
                    <td class="text-center"><a href="uploads/'.$userID.'/'.$row_files['file_name'].'" download><i class="bi bi-download"></i></td>';?>
                    <td><button type="button" id="annotationBTN" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#annotation">Annotation</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
    </div>

    <div class="card mt-5" id="formbody">
            <div class="card-header text-center" id="formHead"><h5 class="card-title m-0" id="formTitle">BUSINESS PERMIT REQUIREMENTS </h5></div>

            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <?php
                    echo '<table class="table table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Document</th>
                                <th scope="col">Status</th>
                                <th scope="col">Upload</th>
                            </tr>
                            </thead>
                            <tbody>';
                            foreach ($document as $row) {
                                echo '  <tr>
                                        <td>' . $row['name'] . '</td>
                                        <td>' . $row['status'] . '</td>
                                        <td>
                                            <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="' . $row['input'] . '" name="' . $row['input'] . '">
                                            <label class="custom-file-label" for="' . $row['input'] . '">Choose file...</label>
                                        </td>
                                        </tr>';
                              }
                    echo '  </tbody>
                        </table>';
                ?>
                <!-- <div class="custom-file">
                    <input type="file" class="custom-file-input <?php echo(!empty($uploadID_error))? 'is-invalid' : '' ;?>" id="uploadID" name="uploadID">
                    <label class="custom-file-label" for="uploadID">Choose file...</label>
                    <div class="invalid-feedback"><?php echo $uploadID_error; ?></div>
                </div> -->
            </div>

            <div class="card-footer text-center" id="formFoot">
                    <input type="submit" class="btn btn-warning"  value="Submit">
            </div>
                </form>
    </div>
    </div>
  <div class="col-sm-3">
  

       
        
  </div>
  
  </div>
  <div class="modal fade" id="annotation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Annotations</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert"><?php echo $row_files["annotation"]; ?></div>
            </div>

            </div>
        </div>
        </div>
<script>
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<script>
    var bnstatus = "<?php echo $row_files["file_status"];?>";
    var rejectedCard = document.getElementById("rejectedCard");
    if(bnstatus=="REJECTED"){
        rejectedCard.classList.remove("d-none");
    }
</script>
</body>
</html>