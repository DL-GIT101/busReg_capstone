<?php ini_set('display_errors', 1);
session_start();
require_once "connection.php";
require_once "functions.php";

if($_SESSION["role"] !== "Admin"){
    header("location: ../index.php");
    exit;
}

if(isset($_GET['user'])){

    $user_id = urldecode($_GET['user']);
    $sql = "DELETE FROM User WHERE UserID = ?";
    $page = "management";
    $link = "../admin/management/users.php";
}else if(isset($_GET['profile'])){

    $user_id = validate($_GET['profile']);
    $sql = "DELETE user_profile, new_documents
        FROM user_profile
        LEFT JOIN new_documents ON user_profile.user_id = new_documents.user_id
        WHERE user_profile.user_id = ?";
    $page = "profile";
    $link = "../admin/management/users.php";
}else if(isset($_GET['documents'])){

    $user_id = validate($_GET['documents']);
    $sql = "DELETE FROM new_documents WHERE user_id = ?";
    $page = "documents";
    $link = "../admin/management/users.php";
}else if(isset($_GET['admin'])){

    $user_id = validate($_GET['admin']);
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


$error = "";

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

if(checkPermit($id) === "None"){

    if ($stmt = $mysqli->prepare($sql)) {

        $stmt->bind_param("s", $param_id);
        $param_id = $id;

        if ($stmt->execute()) {
        
            deleteDirectory($id,$page);

            if($page === "management"){

                $title = "User has been deleted";
        
            }else if($page === "profile") {
                
                $title = "Profile has been deleted";
        
            } else if($page === "documents"){
                
                $title = "All Docuements has been deleted";

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
    }
$mysqli->close(); 
}else if(checkPermit($id) === "Issued"){

    if($page === "management"){

        $title = "User cannot be deleted";

    }else if($page === "profile") {

        $title = "Profile cannot be deleted";

    } else if($page === "documents"){

        $title = "Documents cannot be deleted";

    }

    $message = '<modal>
                    <div class="content warning">
                        <p class="title">'.$title.'</p>
                        <p class="sentence">The permit has already been approved</p>
                        <div class="button-group">
                            <button class="close">Close</button>
                        </div>
                    </div>
                </modal>
    ';
    header('location: '.$link.'?message='. urlencode($message));
}else {
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
}

function deleteDirectory($id,$page) {
    $directory = '../user/upload/' . $id;

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