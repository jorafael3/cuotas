<?php


require("conexion.php");

$sql = "SELECT * FROM inv_productos where Código = 'l3250'";
//echo "Sql:".$sql."<br>";
$result = mysqli_query($con, $sql);
if(mysqli_fetch_array($result)){
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}else{

    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
