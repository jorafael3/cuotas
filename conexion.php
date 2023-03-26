<?php

$DB_HOST = "10.5.1.245";
$DB_USER = "root";
$DB_PASS = "Bruno2001";
$DB_NAME = "sisco";

$min_link2pay = 800;

/// Para MYSQL
$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME) or die(mysqli_error());
if (mysqli_connect_errno()) {
    echo "No se ha podido conectar a MySQL: " . mysqli_connect_error();
}

?>