<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
if(!isset($_SESSION)) {session_start();}

//data: { cursoOfertaSelect: oferta, temaSelect: tema, nivelSelect: nivel},
//data: { cursoOfertaSelect: oferta, temaSelect: tema, nivelSelect: nivel},

$idPregunta = $_POST["idPregunta"];
$idUsuario = $_POST["idUsuario"];
$acierto = $_POST["acierto"];
$respuesta = $_POST["respuesta"];
$respuestaCorrecta = $_POST["respuestaCorrecta"];


//echo "Llegamos al final" . $idPregunta . " | " . $idUsuario . " | " . $acierto ;
//exit;

// ACIERTO
if ($acierto==1) {
   $_SESSION['aciertosSession'] = $_SESSION['aciertosSession'] + 1;
}

// SIGUIENTE Y NO SE HA CONTESTADO YA LA PREGUNTA
if($acierto==99){
   $_SESSION['listaErroresSession'] = $_SESSION['listaErroresSession'] .
      $idPregunta . ';' . $respuestaCorrecta . ';' . $respuesta .'|';
} 

// ERROR
if ($acierto==0) {
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

// solo si es una comprobacion, insertamos la estadistica. 
if ($acierto==1 || $acierto==0) {
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
}
exit;
?>