<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

$idPregunta = $_POST["idPregunta"];
$idUsuario = $_POST["idUsuario"];
$bFavorita = $_POST["bFavorita"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

if ($bFavorita == 'S') {
    $queryFavorita = "INSERT INTO tabMarcaPreguntaUsuario
    (`marca`, `idPregunta`, `idUsuario`)
    VALUES ('S',".$idPregunta.",".$idUsuario.")";     
} else {
    $queryFavorita = "DELETE FROM tabMarcaPreguntaUsuario
    WHERE idPregunta = " . $idPregunta .
    " AND idUsuario = " . $idUsuario ;
}

//printf($queryFavorita);
//exit;

mysqli_query($link, $queryFavorita);  

// Comprobar si hay favorita
$queryFavorita = "SELECT count(1) FROM tabMarcaPreguntaUsuario 
WHERE idPregunta = " . $idPregunta .
" AND idUsuario = " . $idUsuario . " AND marca = 'S'"; 

$result = mysqli_query($link, $queryFavorita);
$row = mysqli_fetch_row($result);
echo $row[0];
exit;

?>