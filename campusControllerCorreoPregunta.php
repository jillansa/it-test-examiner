<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$idPregunta = $_POST["idPregunta"];
$idUsuario = $_POST["idUsuario"];
$examenPregunta = $_POST["examenPregunta"];
$fechaExamenPregunta = $_POST["fechaExamenPregunta"];
$modalidadExamenPregunta = $_POST["modalidadExamenPregunta"];
$ofertaExamenPregunta = $_POST["ofertaExamenPregunta"];
$temaPregunta = $_POST["temaPregunta"];
$preguntaTexto = $_POST["preguntaTexto"];
$respuestaTxtA = $_POST["respuestaTxtA"];
$respuestaTxtB = $_POST["respuestaTxtB"];
$respuestaTxtC = $_POST["respuestaTxtC"];
$respuestaTxtD = $_POST["respuestaTxtD"];
$estadisticaAciertosPregunta = $_POST["estadisticaAciertosPregunta"];
$estadisticaErroresPregunta = $_POST["estadisticaErroresPregunta"];


$queryUsermail = "SELECT email FROM tabUsuario WHERE id = " . $idUsuario . "";
$resultUsermail = mysqli_query($link, $queryUsermail);

if (mysqli_num_rows($resultUsermail) > 0) {
   
   $row = mysqli_fetch_array($resultUsermail);
   
   header("Content-Type: text/html;charset=utf-8");

            $register_email = $row['email'] ;
            $subject = 'Pregunta enviada desde OpositandoBien.es';
            
            /*$message = 'Hola para confirmar la cuenta debes continuar el siguiente enlace: https://40014108.servicio-online.net/campusControllerValidarEmail.php?hash=' . $hashPassword;*/
            $message = "
               <html>
               <head>
               <title>Pregunta enviada desde OpositandoBien.es</title>
               </head>
               <body>
               <p>Pregunta enviada desde OpositandoBien.es</p>

               ".$idPregunta." <br>
               " .$temaPregunta. " <br>
               " .$examenPregunta. " " .$fechaExamenPregunta. " " .$modalidadExamenPregunta. " " .$ofertaExamenPregunta. " <br><br>
               <b>PREGUNTA: " .$preguntaTexto. " </b><br>
               > " .$respuestaTxtA. " <br>
               > " .$respuestaTxtB. " <br>
               > " .$respuestaTxtC. " <br>
               > " .$respuestaTxtD. " <br>
               
               <br>

               " .$estadisticaAciertosPregunta. "
               " .$estadisticaErroresPregunta. "
               
               <br><br>
               
               <p>Atentamente, el equipo de OpositandoBien.es.</p>

                     <a href='campus.php'><img src='https://opositandobien.es/static/images/campus_head.jpg' 
                     alt='Campus FORMA TIC - Preparacion de oposiciones y certificaciones IT'></a>
               
               </body>
               </html>";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <no-reply@opositandobien.es>' . "\r\n";
            
            mail($register_email, $subject, $message, $headers);

}

?>