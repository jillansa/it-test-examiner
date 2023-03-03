<?php

session_start();
header('Content-Type: application/json');

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$contentType = isset($_SERVER["CONTENT_TYPE"]) ?trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    //Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);

    //If json_decode failed, the JSON is invalid.
    if(! is_array($decoded)) {
        echo '{"status":"ERROR"}'; 
    } else {
        $cuerpo = $decoded['cuerpo'];
        $nombre = $decoded['nombre'];
        $idUsuario = $decoded['idUsuario'];
        
        $query = "INSERT INTO tabTemarioUsuario
        (cuerpo, nombre, idUsuario) 
        VALUES ('".$cuerpo."',
                '".$nombre ."',
                ".$idUsuario.")";
        
        
        if (mysqli_query($link,$query)) {
            
            echo '{"status":"OK", 
                "cuerpo": "'.$cuerpo.'",
                "nombre": "'.$nombre.'",
                "idUsuario": "'.$idUsuario.'"
            }'; 

        } else {
            echo '{"status":"ERROR"}'; 
        }

    }
} else {
    echo '{"status":"ERROR"}'; 
}

exit;

?>