<?php ini_set('display_errors', 1);
session_start();
require_once "connection.php";
require_once "functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../index.php");
    exit;
}

if(isset($_GET['user'])){

    $id = urldecode($_GET['user']);
    $sql = "DELETE FROM User WHERE UserID = ?";
    $page = "management";
    $link = "../admin/management/users.php";
    $ownerID = hasOwnerProfile($id);
    $businessID = hasBusinessProfile($ownerID);

   if(checkPermit($businessID) === "Issued"){
        $message = '<modal>
                        <div class="content warning">
                            <p class="title">User cannot be deleted</p>
                            <p class="sentence">The permit has already been issued</p>
                            <div class="button-group">
                                <button class="close">Close</button>
                            </div>
                        </div>
                    </modal>
        ';
        header('location: '.$link.'?message='. urlencode($message));
        exit;
    }else if(checkPermit($businessID) === "Error") {
        $message = '<modal>
                    <div class="content error">
                        <p class="title">Something went wrong</p>
                        <p class="sentence">Try again later</p>
                        <div class="button-group">
                            <button class="close">Close</button>
                        </div>
                    </div>
                </modal>
                ';
        header('location: '.$link.'?message='. urlencode($message));
        exit;
    }
    
}else if(isset($_GET['admin'])){

    $id = validate($_GET['admin']);
    $sql = "DELETE FROM User WHERE UserID = ?";
    $page = "admin";
    $link = "../admin/Superadmin/admins.php";

}else if(isset($_GET['permit'])){

    $id = validate($_GET['permit']);
    $sql = "DELETE FROM Permit WHERE PermitID = ?";
    $page = "permit";
    $link = "../admin/permit/approve.php";

}else{
    $link = "../admin/dashboard.php";
}

$error = false;

if($_SESSION["AdminRole"] !== "Superadmin"){
    $message = '<modal>
                    <div class="content warning">
                        <p class="title">Delete Data</p>
                        <p class="sentence">Only Superadmin can delete data</p>
                        <div class="button-group">
                            <button class="close">Close</button>
                        </div>
                    </div>
                </modal>
            ';
        header('location: '.$link.'?message='. urlencode($message));
    exit;
}

if ($stmt = $mysqli->prepare($sql)) {

    $stmt->bind_param("s", $param_id);
    $param_id = $id;

    if ($stmt->execute()) {

        if($page === "management"){

            deleteDirectory($businessID,$page);
            $title = "User has been deleted";
    
        }else if($page === "admin"){
            
            $title = "The admin account has been deleted";

        }else if($page === "permit"){
            
            $title = "The Permit has been deleted";
        }
    
        $message = '<modal>
                        <div class="content success">
                            <p class="title">'.$title.'</p>
                            <div class="button-group">
                                <button class="close">Close</button>
                            </div>
                        </div>
                    </modal>
        ';
        header('location: '.$link.'?message='. urlencode($message));
    } else {
        $error = true;
    }
}else {
    $error = true;
}

$stmt->close();

if($error === true){

    $message = '<modal>
                    <div class="content error">
                        <p class="title">Something went wrong</p>
                        <p class="sentence">Try again later</p>
                        <div class="button-group">
                            <button class="close">Close</button>
                        </div>
                    </div>
                </modal>
    ';
    header('location: '.$link.'?message='. urlencode($message));
    exit;
}

$mysqli->close(); 

function deleteDirectory($id,$page) {
    $directory = '../user/Business/upload/' . $id;

    if (!is_dir($directory)) {
        return;
    }

    $files = glob($directory . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDirectory($file,$page);
        } else {
            $filename = basename($file);
            if (strpos($filename, 'LOGO_') === 0) {
                continue; 
            }
            unlink($file);
        }
    }

    if($page === "management"){
        rmdir($directory);
    }
}
?>