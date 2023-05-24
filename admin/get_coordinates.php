<?php 
require_once "../php/config.php";
$sql = "SELECT longitude, latitude FROM user_profile";
if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $stmt->bind_result($longitude, $latitude);

        $coordinates = array();
        while ($stmt->fetch()) {
            $coordinates[] = array('longitude' => $longitude, 'latitude' => $latitude);
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