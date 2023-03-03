<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];
$tabOfertaCuerpoActiva = $_POST["tabOfertaCuerpoActiva"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryClasificacion = "SELECT distinct o.id, o.descripcion, o.anio, a.nombre as administracion, a.id as idAdministracion
FROM tabCuerpo c2
JOIN tabOfertaCuerpo oc ON oc.idCuerpo = c2.id
JOIN tabOferta o ON oc.idOferta = o.id
JOIN tabAdministracion a ON o.idAdministracion = a.id
WHERE c2.id = " . $cursoCuerpoSelect . " AND oc.activa " . $tabOfertaCuerpoActiva .
" ORDER BY o.anio desc, a.id, o.descripcion";


$resultClasificacion = mysqli_query($link, $queryClasificacion);
$myArray = array();

while ($row = mysqli_fetch_array($resultClasificacion))
{
    $myArray[] = $row;
}

echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);

?>