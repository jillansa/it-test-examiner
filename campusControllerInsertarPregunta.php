<?php

//ini_set("display_errors",1);
error_reporting(E_ERROR);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
if(!isset($_SESSION)) {session_start();}

$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];
$temaSelect = $_POST["temaSelect"];
$ofertaSelect = $_POST["ofertaSelect"];
$examenSelect = $_POST["examenSelect"];
$preguntaTexto = $_POST["preguntaTexto"];
$repuestaA = $_POST["repuestaA"];
$respuestaCheckedA = $_POST["respuestaCheckedA"];
$repuestaB = $_POST["repuestaB"];
$respuestaCheckedB = $_POST["respuestaCheckedB"];
$repuestaC = $_POST["repuestaC"];
$respuestaCheckedC = $_POST["respuestaCheckedC"];
$repuestaD = $_POST["repuestaD"];
$respuestaCheckedD = $_POST["respuestaCheckedD"];

require_once "campusConfig.php";

if ($respuestaCheckedA == "true") {$respuestaCheckedA = 1;} else {$respuestaCheckedA=0;}
if ($respuestaCheckedB == "true") {$respuestaCheckedB = 1;} else {$respuestaCheckedB=0;}
if ($respuestaCheckedC == "true") {$respuestaCheckedC = 1;} else {$respuestaCheckedC=0;}
if ($respuestaCheckedD == "true") {$respuestaCheckedD = 1;} else {$respuestaCheckedD=0;}

/*printf($_POST["respuestaCheckedA"] . "=" . $respuestaCheckedA . " / " .
$_POST["respuestaCheckedB"]. "=" . $respuestaCheckedB . " / " .
$_POST["respuestaCheckedC"]. "=" . $respuestaCheckedC . " / " .
$_POST["respuestaCheckedD"]. "=" . $respuestaCheckedD);
exit;*/

$query = "INSERT INTO `tabPreguntas`(`idExamen`, `texto`) 
VALUES ('".$examenSelect."','".$preguntaTexto."')";

echo $query; 
exit;

$sql=$query;

//echo '1 - '; exit;

if (mysqli_query($link, $query)) {

   //echo '2 - '; exit;

   // Insert ok
   $query = "SELECT LAST_INSERT_ID()";
   $result = mysqli_query($link, $query);
   $row = mysqli_fetch_row($result);
   $preguntaId = $row[0];

   //echo '3 - '; exit;

   $query = "INSERT INTO `tabPreguntasClasificacion`(`idPregunta`, `idClasificacion`) 
   VALUES ('".$preguntaId."','".$temaSelect."')";
   $sql+="  " . $query;
   mysqli_query($link, $query);

   echo '4 - '; exit;
   
   $query = "INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) 
   VALUES ('".$preguntaId."','".$repuestaA."','".$respuestaCheckedA."')";
   $sql+="  " . $query;
   mysqli_query($link, $query);
   
   $query = "INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) 
   VALUES ('".$preguntaId."','".$repuestaB."','".$respuestaCheckedB."')";
   $sql+="  " . $query;
   mysqli_query($link, $query);

   $query = "INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) 
   VALUES ('".$preguntaId."','".$repuestaC."','".$respuestaCheckedC."')";
   $sql+="  " . $query;
   mysqli_query($link, $query);

   $query = "INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) 
   VALUES ('".$preguntaId."','".$repuestaD."','".$respuestaCheckedD."')";
   $sql+="  " . $query;
   mysqli_query($link, $query);

   echo $sql;
   exit;

 } else {
    // Insert Error
    echo "Llegamos al final con ERROR: " . $idPregunta . " | " . $idUsuario . " | " . $acierto . " | " . $query ;
    echo $sql;
    exit;
 }

 echo $sql;
 exit;

?>