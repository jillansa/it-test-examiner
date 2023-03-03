<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$idCuerpo = $_POST["cursoCuerpoSelect"];
$selected = $_POST["selected"];
$mode = $_POST["mode"];

require_once "campusConfig.php";
 
 if ($mode == 'UPDATE') {    
  $queryClasificacion = "DELETE FROM tabClasificacionOriginalCuerpo 
    WHERE idCuerpo=".$idCuerpo;  
  mysqli_query($link, $queryClasificacion);

  // PROCESAMOS LAS NUEVAS RELACIONES INTRODUCIDAS
  $porciones = explode(",", $selected);
  foreach ($porciones as $valor) {

    $queryClasificacion = "INSERT INTO tabClasificacionOriginalCuerpo
    (idCuerpo, idClasificacion) 
    VALUES (".$idCuerpo.",".$valor.")"; 

    mysqli_query($link, $queryClasificacion);
  }
  $mode = 'CONSULTA';
 }

 if ($mode == 'CONSULTA' ) {
    
   $queryClasificacion = "SELECT 
   distinct c.id as id, c.tema, c.bloque, c.tipo, c.idBloque, 'S' as asignado
   FROM tabClasificacionOriginalCuerpo cco 
   JOIN tabClasificacionOriginal c ON c.id = cco.idClasificacion
   WHERE cco.idCuerpo = " .$idCuerpo. "
   UNION 
   SELECT 
   distinct c.id as id, c.tema, c.bloque, c.tipo, c.idBloque, 'N' as asignado
   FROM tabClasificacionOriginal c
   WHERE c.id not in (SELECT cco.idClasificacion 
      FROM tabClasificacionOriginalCuerpo cco 
      WHERE cco.idCuerpo = " .$idCuerpo. ")
   ORDER BY 3 ASC, 4 DESC, 2 ASC";

   //printf($queryClasificacion);
   //exit; 

   $resultClasificacion = mysqli_query($link, $queryClasificacion);
   $myArray = array();
   
   while ($row = mysqli_fetch_array($resultClasificacion))
   {
       $myArray[] = $row;
   }
   
   echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);
   exit;

 }

 exit;

?>