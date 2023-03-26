<?php


require("conexion.php");

$nombre = 'l3250';
// $sql = "WEB_Select_Productos_Cartimex_Like '".strtoupper($nombre)."', '' ";
// $result = mssql_query(utf8_decode($sql));
// // echo 'Registros encontrados: ' . mssql_num_rows($result) . '<br>';
// $count=mssql_num_rows($result);
// print_r(mssql_fetch_array($result));


$sql = "WEB_Select_Producto_2 '" . $nombre . "' ";
$result = mssql_query(utf8_decode($sql));
// echo 'Registros encontrados: ' . mssql_num_rows($result) . '<br>';
echo "<pre>";
var_dump(mssql_fetch_array($result));
echo "</pre>";