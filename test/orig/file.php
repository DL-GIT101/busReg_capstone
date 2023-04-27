<?php
$con1 = new PDO("mysql:host=localhost;dbname=database_capstone","root","");
$id= isset($_GET['formID'])? $_GET['formID'] : "";
$stat = $con1->prepare("Select * from forms where formID=?");
$stat->bindParam(1,$id);
$stat->execute();
$row = $stat->fetch();
header('Content-Type:'.$row['mime']);
echo $row['data'];
?>