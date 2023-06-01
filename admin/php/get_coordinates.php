<?php 
require_once "/opt/lampp/htdocs/busReg_capstone/php/connection.php";
$sql = "SELECT business_name, longitude, latitude FROM user_profile";
if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $stmt->bind_result($name,$longitude, $latitude);

        $coordinates = array();
        while ($stmt->fetch()) {
            $coordinates[] = array('name' => $name, 'longitude' => $longitude, 'latitude' => $latitude);
        }
        $coordinatesJson = json_encode($coordinates);
        echo $coordinatesJson;
    } else {
        echo "Error retrieving data";
    }
    $stmt->close();
}
$mysqli->close();
?>