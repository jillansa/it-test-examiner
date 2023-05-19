<?php

header("Content-Type: text/html;charset=utf-8");
session_start();


require_once "campusConfig.php";

$lista = $_SESSION['listaErroresSession'];

echo $lista. '<br>';
//exit;

echo "<h1>LISTADO DE ERRORES EN LA SESION:</h1>";

$listaRespuesta = explode("|", $lista);
foreach ($listaRespuesta as $respuesta)
{
    //echo $respuesta . '<br>';
    $param = explode(";", $respuesta);
    $idPregunta = $param[0];
    $respuestaCorrecta=$param[1];
    $respuestaSeleccionada=$param[2];
    
    if ($idPregunta != null) {

        $sql = "SELECT texto FROM `tabPreguntas` WHERE id = " . $idPregunta;
        $resultPregunta = mysqli_query($link, $sql);
        
        $row = mysqli_fetch_array($resultPregunta);
        $txt = $row['texto'];

        echo  '<br><b>' . "PREGUNTA:" . '</b><br>';
        echo $txt . '<br>';

        echo " RespuestaSeleccionada: " .    $respuestaSeleccionada . ' | ';
        echo " RespuestaCorrecta: " .    $respuestaCorrecta . '<br>';

        //exit;

        $sql = "SELECT texto, correcta FROM `tabRespuestas` WHERE idPregunta = " . $idPregunta . " ORDER BY ID ASC LIMIT 0,6";
        $resultRespuestas = mysqli_query($link, $sql);

        //exit;
            
        $i=0;

        while ($row = mysqli_fetch_array($resultRespuestas))
        {      
            
            // Recorro las respuestas
            // Si es la correcta, va de verde siempre. 
            if (boolval($row['correcta']) == true ){
                echo "<div style='color: #339800;'>";
                echo "(*): ";
                echo $row['texto'] . '</div>';
            } else {
                // si no es la correcta, puede ir de rojo (si ha habido fallo) o negra si no se ha seleccionado. 
                switch ($i) {
            
                    // cada respuesta evaluamos si es la marcada por usario. 
                    case 0:
                        if ($respuestaSeleccionada == "A" ){
                            echo "<div style='color: #FF0000;'>";
                        } else {echo "<div style='color: #0a0a0a;'>";}
                        break;
                    case 1:
                        if ($respuestaSeleccionada == "B" ){
                            echo "<div style='color: #FF0000;'>";
                        } else {echo "<div style='color: #0a0a0a;'>";}
                        break;
                    case 2:
                        if ($respuestaSeleccionada == "C" ){
                            echo "<div style='color: #FF0000;'>";
                        } else {echo "<div style='color: #0a0a0a;'>";}
                        break;
                    case 3:
                        if ($respuestaSeleccionada == "D" ){
                            echo "<div style='color: #FF0000;'>";
                        } else {echo "<div style='color: #0a0a0a;'>";}
                        break;
                    /*default:
                        echo "<div style='color: #0a0a0a;'>";
                        break;*/
                }
                echo trim($row['texto']) . '</div>';
            }            
            $i++;    
        }
    }
} 

?>