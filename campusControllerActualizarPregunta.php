<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

$idPregunta = $_POST["idPregunta"];
$preguntaTxt = $_POST["preguntaTxt"];
$respuestaTxtA = $_POST["respuestaTxtA"];
$respuestaTxtB = $_POST["respuestaTxtB"];
$respuestaTxtC = $_POST["respuestaTxtC"];
$respuestaTxtD = $_POST["respuestaTxtD"];
$idRespuestaA = $_POST["idRespuestaA"];
$idRespuestaB = $_POST["idRespuestaB"];
$idRespuestaC = $_POST["idRespuestaC"];
$idRespuestaD = $_POST["idRespuestaD"];
$respuestaCorrecta = $_POST["respuestaCorrecta"];
$temaSelectPregunta = $_POST["temaSelectPregunta"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

// ACTUALIZAR TEXTOS PREGUNTA
$query = "UPDATE tabPreguntas SET texto='".$preguntaTxt."' WHERE id = ".$idPregunta; 
//printf($query);
//exit;
mysqli_query($link, $query);

// ACTUALIZAR TEXTOS RESPUESTAS
$query = "UPDATE tabRespuestas SET texto='".$respuestaTxtA."' WHERE id =".$idRespuestaA;
mysqli_query($link, $query);
$query = "UPDATE tabRespuestas SET texto='".$respuestaTxtB."' WHERE id =".$idRespuestaB;
mysqli_query($link, $query);
$query = "UPDATE tabRespuestas SET texto='".$respuestaTxtC."' WHERE id =".$idRespuestaC;
mysqli_query($link, $query);
$query = "UPDATE tabRespuestas SET texto='".$respuestaTxtD."' WHERE id =".$idRespuestaD;
mysqli_query($link, $query);

// ACTUALIZAR RESPUESTA CORRECTA
if ($respuestaCorrecta == "A") {
    $queryCorrecta = "UPDATE tabRespuestas SET correcta= 1 WHERE id = ".$idRespuestaA;
}
if ($respuestaCorrecta == "B") {
    $queryCorrecta = "UPDATE tabRespuestas SET correcta= 1 WHERE id = ".$idRespuestaB;
}
if ($respuestaCorrecta == "C") {
    $queryCorrecta = "UPDATE tabRespuestas SET correcta= 1 WHERE id = ".$idRespuestaC;
}
if ($respuestaCorrecta == "D") {
    $queryCorrecta = "UPDATE tabRespuestas SET correcta= 1 WHERE id = ".$idRespuestaD;
}

//printf($queryCorrecta);
//exit;

if ($queryCorrecta <> "") {
    // Limpio las respuestas correctas que hay 
    $queryCleanCorrecta = "UPDATE tabRespuestas SET correcta= 0 WHERE idPregunta = ".$idPregunta; 
    mysqli_query($link, $queryCleanCorrecta);
    // Pongo la nueva respuestas correcta que hay 
    mysqli_query($link, $queryCorrecta);
}


// actualizar clasificaion de la pregunta 
$queryClasificacion = "DELETE FROM tabPreguntasClasificacion
WHERE idPregunta = " . $idPregunta;

//echo $queryClasificacion
//exit;

mysqli_query($link, $queryClasificacion);

$porciones = explode(",", $temaSelectPregunta);
foreach ($porciones as $valor) {

  $queryClasificacion = "INSERT INTO tabPreguntasClasificacion
  (idPregunta, idClasificacion) 
  VALUES (".$idPregunta.",".$valor.")";   

  //echo $queryClasificacion
  //exit;

  mysqli_query($link, $queryClasificacion);
  
}


// BORRAR MARCAS DEBUG
$queryDeleteDebug = "DELETE FROM tabDudaErrorPreguntaUsuario WHERE idPregunta = ".$idPregunta; 
mysqli_query($link, $queryDeleteDebug);


?>