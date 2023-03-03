<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

$idPregunta = $_POST["idPregunta"];
$idUsuario = $_POST["idUsuario"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryDudaError = "INSERT INTO tabDudaErrorPreguntaUsuario
(`idPregunta`, `idUsuario`)
VALUES (".$idPregunta.",".$idUsuario.")";     

mysqli_query($link, $queryDudaError);  

exit;

?>