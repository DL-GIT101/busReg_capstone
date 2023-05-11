<?php 
$filePath = 'upload/github.png';

if (file_exists($filePath)) {
    if (unlink($filePath)) {
        echo "File deleted successfully.";
    } else {
        echo "Error deleting file.";
    }
} else {
    echo "File not found.";
}

?>