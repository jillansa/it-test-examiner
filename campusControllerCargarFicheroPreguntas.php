<?php

header("Content-Type: text/html;charset=utf-8");
session_start();


require_once "campusConfig.php";

echo "Filename: " . $_FILES['fichero_usuario']['name']."<br>";
echo "Type : " . $_FILES['fichero_usuario']['type'] ."<br>";
echo "Size : " . $_FILES['fichero_usuario']['size'] ."<br>";
echo "Temp name: " . $_FILES['fichero_usuario']['tmp_name'] ."<br>";
echo "Error : " . $_FILES['fichero_usuario']['error'] . "<br>";
//exit;

$fichero_usuario = $_FILES['fichero_usuario']['tmp_name'];

if ($_FILES['fichero_usuario']['size'] > 0){
    //exit;
    $file = @fopen($fichero_usuario, 'r'); 

    //echo "The file is open"; 
    //exit;

    $queryPregunta = "SELECT max(id)+1 as id from tabPreguntas";
    $result = mysqli_query($link, $queryPregunta);
    $line = 0;

    if (mysqli_num_rows($result) > 0) {   

      $row = mysqli_fetch_array($result);

      //echo "Fecth row"; 
      $id = $row['id'];
      $nline = 0;

      echo "<pre>";
      
      while (($line = fgets($file, 1000000)) !== FALSE) {         
        $line = trim($line);
        $nline++;
        //echo "Read line " . $nline . " : " . $line . "<br>";
        //EXAMEN
        if ($nline==1){            
            $valores = explode(":", $line);
            $idExamen = trim($valores[1]);
            //echo "IDEXAMEN: " . $idExamen . "<br>";
        }

        // PREGUNTAS
        if ($nline >10){
            // CLASIFICACION
            if (($nline-10) % 6 == 1){
                $id++;
                $lAux = explode("|", $line);
                $strBloque = trim($lAux[0]);
                $strClasificacion = trim($lAux[1]);
                $clasificacion = "(SELECT id FROM tabClasificacionOriginal WHERE bloque = '".$strBloque."' and tema = '".$strClasificacion."')";
                //echo "CLASIFICACION: " . $clasificacion . "<br>";
            }
            // PREGUNTA
            if (($nline-10) % 6 == 2){
                $pregunta = $line;
                //echo "PREGUNTA: " . $pregunta;
                echo "<code>INSERT INTO `tabPreguntas`(id,`idExamen`, `texto`) VALUES (" . $id . "," . $idExamen . ",'".$pregunta."');"."</code><br>";            
                echo "<code>INSERT INTO `tabPreguntasClasificacion`(`idPregunta`, `idClasificacion`) VALUES (". $id . ",".$clasificacion.");"."</code><br>"; 
            }
            // RESPUESTAS
            if (((($nline-10) % 6) == 3) or ((($nline-10) % 6) == 4) or ((($nline-10) % 6) == 5) or ((($nline-10) % 6) == 0)){
                $respuesta = $line;
                //echo "RESPUESTA: " . $respuesta;
                //echo "primer caracter:" . $respuesta[0] ."<br>";

                if ($respuesta[0]==='*') {
                    //echo "Respuesta correcta: "."<br>";
                    echo "<code>INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) VALUES (". $id . ",'".substr($respuesta,1)."',1);"."</code><br>";
                } else {
                    echo "<code>INSERT INTO `tabRespuestas`(`idPregunta`, `texto`, `correcta`) VALUES (". $id . ",'".$respuesta."',0);"."</code><br>";
                }
            }             
        }
      }    
      
      echo "</pre>";  
    }       
}

?>