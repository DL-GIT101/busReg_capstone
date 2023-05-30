<?php 
require_once "../php/connection.php";
$sql = "SELECT business_name, longitude, latitude FROM user_profile";
if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $stmt->bind_result($name,$longitude, $latitude);

        $coordinates = array();
        while ($stmt->fetch()) {
            $coordinates[] = array('name' => $name, 'longitude' => $longitude, 'latitude' => $latitude);
        }

        // Convert the coordinates array to JSON format
        $coordinatesJson = json_encode($coordinates);

        // Return the JSON-encoded coordinates
        echo $coordinatesJson;
    } else {
        echo "Error retrieving data";
    }
    $stmt->close();
}
$mysqli->close();
?>