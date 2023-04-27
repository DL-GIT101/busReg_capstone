<?php 
define('ROOT', 'C:\xampp\htdocs\\');
include_once(ROOT. "connect.php");

if(!empty($_POST["status"])){ 
    $status = strtoupper($_POST["status"]);
    if($status=="ALL"){
    $query = "  SELECT user.first, user.last, user.suffix, user.first, business.name, permit.ID, permit.status
                FROM user
                JOIN owner ON user.ID = owner.userID
                JOIN business ON owner.ID = business.ownerID
                JOIN permit ON business.ID = permit.businessID;"; 
    $result = $conn->query($query); 
    }else{
        $query = "  SELECT user.first, user.last, user.suffix, user.first, business.name, permit.ID, permit.status
        FROM user
        JOIN owner ON user.ID = owner.userID
        JOIN business ON owner.ID = business.ownerID
        JOIN permit ON business.ID = permit.businessID WHERE permit.status = '$status';"; 
$result = $conn->query($query); 
    }
    if($result->num_rows > 0){ 
        while($row = $result->fetch_assoc()){  
            echo '  <tr>  
                        <td>'.$row["first"].' '.$row["last"].' '.$row["suffix"].'</td>
                        <td>'.$row["name"].'</td>
                        <td>'.$row["ID"].'</td>
                        <td>'.$row["status"].'</td>
                        <td class="text-center"><button name="idPermit" class="btn btn-sm btn-outline-dark py-0" type="submit" value="'.$row["ID"].'">Review</button></td>
                    </tr>'; 
        } 
    }else{ 
        echo '0 Results'; 
    } 
}
?>