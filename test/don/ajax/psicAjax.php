<?php 
define('ROOT', 'C:\xampp\htdocs\\');
include_once(ROOT. "connect.php");

if(!empty($_POST["section"])){ 
    $query = "SELECT DISTINCT PSIC_DIV_DESC FROM psic_descriptor WHERE PSIC_SEC_DESC = '".$_POST["section"]."' ORDER BY PSIC_DIV_DESC ASC"; 
    $result = $conn->query($query); 
     
    if($result->num_rows > 0){ 
        echo '<option value="" disabled selected>Select Division</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['PSIC_DIV_DESC'].'">'.$row['PSIC_DIV_DESC'].'</option>'; 
        } 
    }else{ 
        echo '<option value="" disabled selected>Division not available</option>'; 
    } 
}elseif(!empty($_POST["division"])){ 
    $query = "SELECT DISTINCT PSIC_GRP_DESC FROM psic_descriptor WHERE PSIC_DIV_DESC = '".$_POST['division']."' ORDER BY PSIC_GRP_DESC ASC"; 
    $result = $conn->query($query); 
     
    if($result->num_rows > 0){ 
        echo '<option value="" disabled selected>Select Group</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['PSIC_GRP_DESC'].'">'.$row['PSIC_GRP_DESC'].'</option>'; 
        } 
    }else{ 
        echo '<option value="" disabled selected>Group not available</option>'; 
    } 
}elseif(!empty($_POST["group"])){ 
    $query = "SELECT DISTINCT PSIC_CLS_DESC FROM psic_descriptor WHERE PSIC_GRP_DESC = '".$_POST['group']."' ORDER BY PSIC_CLS_DESC ASC"; 
    $result = $conn->query($query); 
     
    if($result->num_rows > 0){ 
        echo '<option value="" disabled selected>Select Class</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['PSIC_CLS_DESC'].'">'.$row['PSIC_CLS_DESC'].'</option>'; 
        } 
    }else{ 
        echo '<option value="" disabled selected>Class not available</option>'; 
    } 
}elseif(!empty($_POST["class"])){  
    $query = "SELECT BND_VALUE FROM psic_descriptor WHERE PSIC_CLS_DESC = '".$_POST['class']."' ORDER BY BND_VALUE ASC"; 
    $result = $conn->query($query); 
     
    if($result->num_rows > 0){ 
        echo '<option value="" disabled selected>Select Subclass</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['BND_VALUE'].'">'.$row['BND_VALUE'].'</option>'; 
        } 
    }else{ 
        echo '<option value="" disabled selected>Subclass not available</option>'; 
    } 
} 
?>