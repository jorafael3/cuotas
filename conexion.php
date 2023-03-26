<?php

$link = mssql_connect('10.5.1.3:1433', 'jairo', 'qwertys3gur0');

if (!$link) {
    echo ('Imposible conectar con el DOBRA!');
} else {
    echo ('onectar con el DOBRA!');
}
//     echo mssql_get_last_message();
//    echo '<br>';

if (!mssql_select_db('CARTIMEX', $link)) {
    echo ('No se puede seleccionar la base!');
} else {
    echo ('BASE CPNECTADA');
}
//        echo mssql_get_last_message();
//       echo '<br>';

echo '<br>';

// $DB_HOST = "10.5.1.3";
// $DB_USER = "mike";
// $DB_PASS = "Princes@";
// $DB_NAME = "CARTIMEX";

// $min_link2pay = 800;

// /// Para MYSQL
// $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME) or die(mysqli_error());
// if (mysqli_connect_errno()) {
//     echo "Failed to connect to MySQL: " . mysqli_connect_error();
//     exit();
// }

// $sql_serverName = "tcp:10.5.1.3,1433";
// $sql_database = "CARTIMEX";
// $sql_user = "mike";
// $sql_pwd = "Princes@";

// try{
//     $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
// } catch (PDOException $e){
//   die('Connected failed:'.$e-> getMessage());
// }
