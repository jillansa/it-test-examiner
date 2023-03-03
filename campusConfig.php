<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'PMYSQL148.dns-servicio.com');
define('DB_USERNAME', 'campus');
define('DB_PASSWORD', 'P@$s=947533');
define('DB_NAME', '8870450_campus');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_set_charset($link,"utf8");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$baseDir = 'https://opositandobien.es/';
?>