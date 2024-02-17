<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
if(!isset($_SESSION)) {session_start();}

if (isset($_POST["cursoCuerpoSelect"])) {$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];}
if (isset($_POST["bloqueSelect"])) {$bloqueSelect = $_POST["bloqueSelect"];}

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryClasificacion = "SELECT distinct 
tco.bloque, tco2.id, tco2.tema, tco2.tipo
FROM tabClasificacion tco, tabClasificacion tco2 ";

if ($cursoCuerpoSelect <> "") {
    $queryClasificacion = $queryClasificacion . " , tabClasificacionCuerpo tcoc 
    WHERE 1 = 1
    AND tcoc.idClasificacion = tco2.id 
    AND tcoc.idCuerpo = " . $cursoCuerpoSelect . " "; 
} else {
    $queryClasificacion = $queryClasificacion . " WHERE 1 = 1 ";
}

$queryClasificacion = $queryClasificacion . "
AND tco.tipo = 1
AND tco2.idBloque = tco.id ";


if ($bloqueSelect <> "") {
    $queryClasificacion = $queryClasificacion . " AND tco2.idBloque = " . $bloqueSelect . " "; 
} 

$queryClasificacion = $queryClasificacion . "ORDER BY tco.orden ASC, tco2.tema ASC";

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