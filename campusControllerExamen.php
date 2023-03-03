<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$cursoCuerpoSelect2 = $_POST["cursoCuerpoSelect2"];
$descripcionExamen = $_POST["descripcionExamen"];
$fechaExamen = $_POST["fechaExamen"];
$modalidadExamen = $_POST["modalidadExamen"];
$ofertaModalSelect = $_POST["ofertaModalSelect"];
$modo = $_POST["modo"];

require_once "campusConfig.php";

if ($modo == 'ALTA') {  
           
    $query = "INSERT INTO tabExamen(descripcion, idCuerpo, idOferta, fecha_examen, modalidad) 
    VALUES ('".$descripcionExamen."',";

IF ($cursoCuerpoSelect2  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$cursoCuerpoSelect2 ."',";}
IF ($ofertaModalSelect  == '' ) {$query = $query ."null,";} else {$query = $query ."'".$ofertaModalSelect ."',";}

    $query = $query . "       
            '".$fechaExamen."',
            '".$modalidadExamen."')";

    //echo $query;
    //exit;

    mysqli_query($link, $query);

    exit;
}

?>