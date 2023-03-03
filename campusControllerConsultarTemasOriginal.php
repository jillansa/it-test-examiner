<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];
$bloqueSelect = $_POST["bloqueSelect"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryClasificacion = "SELECT distinct tco.id as id, tco.tema, tco.bloque, tco.tipo
FROM tabClasificacionOriginal tco ";

if ($cursoCuerpoSelect <> "") {
    $queryClasificacion = $queryClasificacion . " , tabClasificacionOriginalCuerpo tcoc 
    WHERE tcoc.idClasificacion = tco.id 
    AND tcoc.idCuerpo = " . $cursoCuerpoSelect . " "; 
} else {
    $queryClasificacion = $queryClasificacion . " WHERE 1 = 1 ";
}

if ($bloqueSelect <> "") {
    $queryClasificacion = $queryClasificacion . " AND tco.idBloque = " . $bloqueSelect . " "; 
} 

$queryClasificacion = $queryClasificacion . "ORDER BY tco.bloque ASC, tco.tipo DESC, tco.tema ASC";

//printf($queryExamenes);
//exit;

$resultClasificacion = mysqli_query($link, $queryClasificacion);
$myArray = array();

while ($row = mysqli_fetch_array($resultClasificacion))
{
    $myArray[] = $row;
}

echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);

?>