<?php 
require_once "connection.php";
$sql = "SELECT Name, Activity, ContactNumber, Longitude, Latitude FROM Business";
if ($stmt = $mysqli->prepare($sql)) {
    if ($stmt->execute()) {
        $stmt->bind_result($name,$activity, $contact, $longitude, $latitude);

        $coordinates = array();
        while ($stmt->fetch()) {
            $coordinates[] = 
            array(
                'name' => $name,
                'activity' => $activity,
                'contact' => $contact,    
                'longitude' => $longitude, 
                'latitude' => $latitude);
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