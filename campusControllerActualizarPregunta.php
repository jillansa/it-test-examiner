<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

if (isset($_POST["idPregunta"])) {$idPregunta = $_POST["idPregunta"];}
if (isset($_POST["preguntaTxt"])) {$preguntaTxt = $_POST["preguntaTxt"];}
if (isset($_POST["respuestaTxtA"])) {$respuestaTxtA = $_POST["respuestaTxtA"];}
if (isset($_POST["respuestaTxtB"])) {$respuestaTxtB = $_POST["respuestaTxtB"];}
if (isset($_POST["respuestaTxtC"])) {$respuestaTxtC = $_POST["respuestaTxtC"];}
if (isset($_POST["respuestaTxtD"])) {$respuestaTxtD = $_POST["respuestaTxtD"];}
if (isset($_POST["idRespuestaA"])) {$idRespuestaA = $_POST["idRespuestaA"];}
if (isset($_POST["idRespuestaB"])) {$idRespuestaB = $_POST["idRespuestaB"];}
if (isset($_POST["idRespuestaC"])) {$idRespuestaC = $_POST["idRespuestaC"];}
if (isset($_POST["idRespuestaD"])) {$idRespuestaD = $_POST["idRespuestaD"];}
if (isset($_POST["respuestaCorrecta"])) {$respuestaCorrecta = $_POST["respuestaCorrecta"];}
if (isset($_POST["temaSelectPregunta"])) {$temaSelectPregunta = $_POST["temaSelectPregunta"];}

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