<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
if(!isset($_SESSION)) {session_start();}

$cursoCuerpoSelect2 = $_POST["cursoCuerpoSelect2"];
$descripcionExamen = $_POST["descripcionExamen"];
$fechaExamen = $_POST["fechaExamen"];
$modalidadExamen = $_POST["modalidadExamen"];
$anioOfertaExamen = $_POST["anioOfertaExamen"];
$ofertaExamen = $_POST["ofertaExamen"];
$entidadExamen = $_POST["entidadExamen"];

$modo = $_POST["modo"];

require_once "campusConfig.php";

if ($modo == 'ALTA') {  
           
    $query = "INSERT INTO tabExamen(descripcion, idCuerpo, fecha_examen, modalidad, anioConvocatoria, administracion, tipoAdministracion) 
    VALUES ('".$descripcionExamen."',";

IF ($cursoCuerpoSelect2  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$cursoCuerpoSelect2 ."',";}
IF ($fechaExamen  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$fechaExamen ."',";}
IF ($modalidadExamen  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$modalidadExamen ."',";}
IF ($anioOfertaExamen  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$anioOfertaExamen ."',";}
IF ($ofertaExamen  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$ofertaExamen ."',";}
IF ($entidadExamen  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$entidadExamen ."'";}

    $query = $query . ")";

    //echo $query;
    //exit;

    mysqli_query($link, $query);

    exit;
}

?>