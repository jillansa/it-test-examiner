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

$queryExamenes = "SELECT distinct e.id, e.descripcion, o.descripcion as descripcionOferta, a.nombre as nombreAdm, e.modalidad, e.fecha_examen as fechaExamen
    FROM tabExamen e 
    LEFT JOIN tabOferta o ON e.idOferta = o.id
    LEFT JOIN tabAdministracion a ON o.idAdministracion = a.id 
    WHERE 1 = 1 ";

if ($cursoCuerpoSelect <> "") {
    $queryExamenes = $queryExamenes . " AND e.idCuerpo = " . $cursoCuerpoSelect . " "; 
}

if ($ofertaSelect <> "") {
    $queryExamenes = $queryExamenes . " AND ((e.idOferta is null and e.descripcion like '%TEST%') or o.id = " . $ofertaSelect . ") "; 
}

//printf($queryExamenes);
//exit;

$queryExamenes = $queryExamenes . " ORDER BY e.fecha_examen desc, a.nombre, e.modalidad, e.descripcion";

$resultExamenes = mysqli_query($link, $queryExamenes);
$myArray = array();

while ($row = mysqli_fetch_array($resultExamenes))
{
    $myArray[] = $row;
}

echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);

?>