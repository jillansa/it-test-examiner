<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
if(!isset($_SESSION)) {session_start();}

if (isset($_POST["cursoCuerpoSelect"])) {$cursoCuerpoSelect = $_POST["cursoCuerpoSelect"];}

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$queryExamenes = "SELECT distinct e.id, e.descripcion, e.modalidad, e.fecha_examen as fechaExamen
    FROM tabExamen e    
    WHERE 1 = 1 ";

if ($cursoCuerpoSelect <> "") {
    $queryExamenes = $queryExamenes . " AND e.idCuerpo = " . $cursoCuerpoSelect . " "; 
}

//printf($queryExamenes);
//exit;

$queryExamenes = $queryExamenes . " ORDER BY e.fecha_examen desc, e.descripcion, e.modalidad";

$resultExamenes = mysqli_query($link, $queryExamenes);
$myArray = array();

while ($row = mysqli_fetch_array($resultExamenes))
{
    $myArray[] = $row;
}

echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);

?>