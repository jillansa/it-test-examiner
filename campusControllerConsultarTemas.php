<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];
$ofertaSelect = $_POST["ofertaSelect"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryClasificacion = "SELECT distinct c.id as id, c.tema, c.bloque, c.tipo
FROM tabCuerpo c2
JOIN tabOfertaCuerpo oc ON oc.idCuerpo = c2.id
JOIN tabOfertaCuerpo_Clasificacion occ ON occ.idOfertaCuerpo = oc.id
JOIN tabClasificacion c ON c.id = occ.idClasificacion
WHERE c2.id = " . $cursoCuerpoSelect . 
" AND oc.idOferta = " .$ofertaSelect. " AND oc.activa = 'S'
ORDER BY bloque ASC, tipo DESC, tema ASC";


$resultClasificacion = mysqli_query($link, $queryClasificacion);
$myArray = array();

while ($row = mysqli_fetch_array($resultClasificacion))
{
    $myArray[] = $row;
}

echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);

?>