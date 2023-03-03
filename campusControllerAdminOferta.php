<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$idCuerpo = $_POST["idCuerpo"];
$idOfertaForm = $_POST["idOfertaForm"];
$idOfertaCuerpoForm = $_POST["idOfertaCuerpoForm"];
$mode = $_POST["mode"];
$idAdministracionSelect = $_POST["idAdministracionSelect"];
$descripcionOfertaInput = $_POST["descripcionOfertaInput"];
$anioOfertaInput = $_POST["anioOfertaInput"];
$ofertaPlazasInput = $_POST["ofertaPlazasInput"];
$ofertaActivaInput = $_POST["ofertaActivaInput"];
$selected = $_POST["selected"];
$nombreTema = $_POST["nombreTema"];
$nombreBloque = $_POST["nombreBloque"];
$tipoBloque = $_POST["tipoBloque"];

require_once "campusConfig.php";


if ($mode == "UPDATE") {
   //UPDATE tabOferta SET id='[value-1]',descripcion='[value-2]',anio='[value-3]',idAdministracion='[value-4]' WHERE 1

   $query = "UPDATE tabOferta 
   SET descripcion = '".$descripcionOfertaInput."', 
      anio='".$anioOfertaInput."', 
      idAdministracion='".$idAdministracionSelect."'  
   WHERE id = ".$idOfertaForm;

   mysqli_query($link, $query);

   // UPDATE tabOfertaCuerpo SET id='[value-1]',idOferta='[value-2]',idCuerpo='[value-3]',
   // numPlazas='[value-4]',activa='[value-5]' WHERE 1
   $query = "UPDATE tabOfertaCuerpo 
   SET idCuerpo = '".$idCuerpo."', 
      numPlazas='".$ofertaPlazasInput."',
      activa='".$ofertaActivaInput."' 
   WHERE id = ".$idOfertaCuerpoForm;

   mysqli_query($link, $query);

   echo "OK";
   exit;

 } 
 
 if ($mode == 'INSERTOFERTA') {
   // INSERT INTO tabOferta(id, descripcion, anio, idAdministracion) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]')
   echo "Llegamos al final con: " . $idCuerpo . " | " . $idOfertaForm . " | " . $idOfertaCuerpoForm . " | " . $descripcionOfertaInput ;

  exit;

}

 if ($mode == 'INSERTOFERTACUERPO') {
    
   // INSERT INTO tabOfertaCuerpo(idOferta, idCuerpo, numPlazas, activa) 
   // VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]')
   $query = "INSERT INTO tabOfertaCuerpo(idOferta, idCuerpo, numPlazas, activa) 
   VALUES (".$idOfertaForm.",".$idCuerpo.",".$ofertaPlazasInput.",'".$ofertaActivaInput."')";
   mysqli_query($link, $query);
   //printf($query);
   exit;

 }

 if ($mode == 'DELETEOFERTACUERPO') {
    
   $query = "DELETE FROM tabOfertaCuerpo WHERE id = " .$idOfertaCuerpoForm;
   mysqli_query($link, $query);
   //printf($query);
   exit;

 }


 if ($mode == 'ANIADIRCLASIFICACION') {    

  $porciones = explode(",", $selected);
  foreach ($porciones as $valor) {

    $queryClasificacion = "INSERT INTO tabOfertaCuerpo_Clasificacion
    (idOfertaCuerpo, idClasificacion) 
    VALUES (".$idOfertaCuerpoForm.",".$valor.")";   

    mysqli_query($link, $queryClasificacion);
  }
 }


 if ($mode == 'ELIMINARCLASIFICACION') {
   
  $porciones = explode(",", $selected);
  foreach ($porciones as $valor) {

    $queryClasificacion = "DELETE FROM tabOfertaCuerpo_Clasificacion 
    WHERE idOfertaCuerpo=".$idOfertaCuerpoForm." AND idClasificacion = " .$valor ;    
    //printf($queryClasificacion);
    //exit; 
    mysqli_query($link, $queryClasificacion);
  }
 }
 

 if ($mode == 'CREARCLASIFICACION') {
  
  if ($tipoBloque == 1) {
    $queryClasificacion = "INSERT INTO tabClasificacion
    (`bloque`, `tema`, `tipo`, `idBloque`)
    VALUES ('".$nombreBloque."','".$nombreTema."',".$tipoBloque.",null)";     
  } else {
    
    $queryClasificacion = "SELECT id from tabClasificacion 
    where bloque = '".$nombreBloque."' and tipo=1";
    $result = mysqli_query($link, $queryClasificacion);
    //printf($queryClasificacion);
    //exit; 
    if (mysqli_num_rows($result) > 0) {
   
      $row = mysqli_fetch_array($result);
      
      $queryClasificacion = "INSERT INTO tabClasificacion
      (`bloque`, `tema`, `tipo`, `idBloque`)
      VALUES ('".$nombreBloque."','".$nombreTema."',".$tipoBloque.",".$row['id'].")"; 

    }   
  }
  //printf($queryClasificacion);
  //exit; 
  mysqli_query($link, $queryClasificacion);

 }

 if ($mode == 'CONSULTATEMARIO' || $mode == 'ANIADIRCLASIFICACION' || $mode == 'ELIMINARCLASIFICACION' || $mode == 'CREARCLASIFICACION') {
    
   $queryClasificacion = "SELECT distinct c.id as id, c.tema, c.bloque, c.tipo, c.idBloque, c.observaciones, 'S' as asignado
   FROM tabOfertaCuerpo_Clasificacion occ 
   JOIN tabClasificacion c ON c.id = occ.idClasificacion
   WHERE occ.idOfertaCuerpo = " .$idOfertaCuerpoForm. "
   UNION 
   SELECT distinct c.id as id, c.tema, c.bloque, c.tipo, c.idBloque, c.observaciones, 'N' as asignado
   FROM tabClasificacion c
   WHERE c.id not in (SELECT occ.idClasificacion 
      FROM tabOfertaCuerpo_Clasificacion occ 
      WHERE occ.idOfertaCuerpo = " .$idOfertaCuerpoForm. ")
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