<?php


require("conexion.php");

$sql = "SELECT * FROM inv_productos where Código == 'l3250'";
//echo "Sql:".$sql."<br>";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
echo "<pre>";
print_r($row);
echo "</pre>";
?>