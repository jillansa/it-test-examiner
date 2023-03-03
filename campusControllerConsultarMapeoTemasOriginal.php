<?php

//ini_set("display_errors",1);
//error_reporting(E_ALL);
 //code goes here
header("Content-Type: text/html;charset=utf-8");
session_start();

$temarioCuerpoOferta = $_POST["temarioCuerpoOferta"];
$clasificacionOptions = $_POST["clasificacionOptions"];
$mode = $_POST["mode"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

if ($mode == "SELECT") {
    $queryClasificacion = "SELECT distinct id, tema, bloque, tipo, 
        (SELECT 'S' FROM tabMapeoClasificacionOriginal m 
        WHERE 
        m.idClasificacion = " . $temarioCuerpoOferta .
        " AND m.idClasificacionOriginal = co.id) as selected
    FROM  tabClasificacionOriginal co
    ORDER BY bloque ASC, tipo DESC, tema ASC";

    $resultClasificacion = mysqli_query($link, $queryClasificacion);
    $myArray = array();

    while ($row = mysqli_fetch_array($resultClasificacion))
    {
        $myArray[] = $row;
    }
    echo json_encode($myArray, JSON_INVALID_UTF8_SUBSTITUTE);
}

if ($mode == "UPDATE_ALL") {

    // actualizar clasificaion del tema
    $queryClasificacion = "DELETE FROM tabMapeoClasificacionOriginal
    WHERE idClasificacion = " . $temarioCuerpoOferta;
    
    mysqli_query($link, $queryClasificacion);

    $porciones = explode(",", $clasificacionOptions);
    foreach ($porciones as $valor) {

        $queryClasificacion = "INSERT INTO tabMapeoClasificacionOriginal
        (idClasificacion, idClasificacionOriginal) 
        VALUES (".$temarioCuerpoOferta.",".$valor.")";   
        
        mysqli_query($link, $queryClasificacion);
        
    }
    
    printf("Datos Actualizados correctamente");
    exit;


}


?>