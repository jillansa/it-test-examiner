<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");
// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$session_username = $_POST["session_username"];

$queryUsername = "SELECT username, email, password
FROM tabUsuario 
WHERE (username = '" . $session_username . "'  OR email = '" . $session_username . " ' OR dni = '" . $session_username . "')" ;

//printf($queryUsername);
//exit;

 $resultUsername = mysqli_query($link, $queryUsername);
//printf("Select returned resultUsername (".$session_username . "/" . $session_password.") = %d rows. " . $queryUsername ." \n", mysqli_num_rows($resultUsername));


if (mysqli_num_rows($resultUsername) > 0) {
   
   $row = mysqli_fetch_array($resultUsername);
   

      // Store data in session variables

      $username = $row['username'];   
      $email = $row['email'];   
      $hash = $row['password'];        

      $subject = 'Restablecer contraseña de su cuenta OpositandoBien.es';
      
      /*$message = 'Hola para confirmar la cuenta debes continuar el siguiente enlace: 
      https://40014108.servicio-online.net/campusControllerValidarEmail.php?hash=' . $hashPassword;*/

      $message = "
      <html>
      <head>
      <title>Restablecer contraseña de cuenta OpositandoBien.es</title>
      </head>
      <body>
      <p>Hola, de parte del equipo de OpositandoBien.es!! </p>

      <p>Para restablecer la contraseña de su cuenta OpositandoBien.es debe pinchar en el siguiente enlace y confirmar una nueva contraseña: </p>

      https://opositandobien.es/campusResetPassword.php?username=".$username."&hash=" . $hash . "
      
      <p>Atentamente, el equipo de OpositandoBien.es.</p>

            <a href='campus.php'><img src='https://opositandobien.es/static/images/campus_head.jpg' 
            alt='Campus FORMA TIC - Preparacion de oposiciones y certificaciones'></a>
      
      </body>
      </html>";

      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

      // More headers
      $headers .= 'From: <soporte@opositandobien.es>' . "\r\n";
      
      mail($email, $subject, $message, $headers);

      printf("Se ha enviado un correo para restablecer su contraseña al email asociado a su cuenta. Acceda a su buzon y siga las intrucciones. ");
      exit;

} else {
      // Password isn't correct, so start a new session    
      header("location: campusLinkResetPassword.php?errorForm=Las datos de identificacion no son correctos. No existe ese usuario/email/dni.");
      exit;
}

?>