<?php

session_start();
header("Content-Type: text/html;charset=utf-8");

         $register_email = 'hypercodegeek@gmail.com';
         $subject = 'Confirmación de cuenta OpositandoBien.es';
         
         /*$message = 'Hola para confirmar la cuenta debes continuar el siguiente enlace: https://40014108.servicio-online.net/campusControllerValidarEmail.php?hash=' . $hashPassword;*/
         $message = "
            <html>
            <head>
            <title>Confirmación de cuenta OpositandoBien.es</title>
            </head>
            <body>
            <p>Hola, bienvenido a OpositandoBien.es!! </p>

            <p>Para completar el proceso de registro y activar su cuenta en OpositandoBien.es debe pinchar en el siguiente enlace y confirmar su dirección de correo: </p>

            https://opositandobien.es/campusControllerValidarEmail.php?username=".$register_username."&hash=" . $hashPassword . "
            
            <p>Atentamente, el equipo de OpositandoBien.es.</p>

                  <a href='campus.php'><img src='https://opositandobien.es/static/images/campus_head.jpg' 
                  alt='Campus FORMA TIC - Preparacion de oposiciones y certificaciones'></a>
            
            </body>
            </html>";

         $headers = "MIME-Version: 1.0" . "\r\n";
         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

         // More headers
         $headers .= 'From: <soporte@opositandobien.es>' . "\r\n";
         
         mail($register_email, $subject, $message, $headers);


?>