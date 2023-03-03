<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

//data: { cursoOfertaSelect: oferta, temaSelect: tema, nivelSelect: nivel},
//data: { cursoOfertaSelect: oferta, temaSelect: tema, nivelSelect: nivel},

$idPregunta = $_POST["idPregunta"];
$idUsuario = $_POST["idUsuario"];
//$acierto = $_POST["acierto"];
$respuesta = $_POST["respuesta"];
$respuestaCorrecta = $_POST["respuestaCorrecta"];


//echo "Llegamos al final" . $idPregunta . " | " . $idUsuario . " | " . $acierto ;
//exit;

if ($acierto = $_POST["acierto"]) {
   $_SESSION['aciertosSession'] = $_SESSION['aciertosSession'] + 1;
} else {
   $_SESSION['erroresSession'] = $_SESSION['erroresSession'] + 1;
   $_SESSION['listaErroresSession'] = $_SESSION['listaErroresSession'] .
       $idPregunta . ';' . $respuestaCorrecta . ';' . $respuesta .'|';
}

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

/*$query = "SELECT MAX(id)+1 FROM tabRespuestasUsuario";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
$highest_id = $row[0];*/

$queryUsername = "INSERT INTO tabRespuestasUsuario (`idPregunta`, `idUsuario`, `acierto`) VALUES  (".$idPregunta.",".$idUsuario.",".$_POST["acierto"].")";

if (mysqli_query($link, $queryUsername)) {
    // Insert ok
    //echo "Llegamos al final OK: " . $idPregunta . " | " . $idUsuario . " | " . $acierto . " | " . $queryUsername ;
    exit;
 } else {
    // Insert Error
    //echo "Llegamos al final con ERROR: " . $idPregunta . " | " . $idUsuario . " | " . $acierto . " | " . $queryUsername ;
    exit;
 }

?>