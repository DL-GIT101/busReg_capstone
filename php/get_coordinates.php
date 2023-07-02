<?php 
require_once "connection.php";
$sql = "SELECT Name, Longitude, Latitude FROM Business";
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