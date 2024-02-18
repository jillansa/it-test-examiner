<?php

error_reporting(E_ERROR);

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

$register_username = $_POST["register_username"];
$register_password = $_POST["register_password"];
$register_password2 = $_POST["register_password2"];
$register_email = $_POST["register_email"];
$register_name = $_POST["register_name"];
$register_surname = $_POST["register_surname"];
$register_document = $_POST["register_document"];

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

//printf("Llegamos al final: " . $register_username . "/" . $register_password . "/" . $register_password2 );
//exit;

if ($register_password == $register_password2) {

   $options = array("cost"=>4);
   $hashPassword = password_hash($register_password,PASSWORD_BCRYPT,$options);

   $query = "SELECT MAX(id)+1 FROM tabUsuario";
   //printf ($query);
   //exit;
   
   $result = mysqli_query($link, $query);
   $row = mysqli_fetch_row($result);
   $highest_id = $row[0];
   
   // Comprobamos que: loginname, email y dni no existen ya en el sistema
   $query = "SELECT count(1) FROM tabUsuario WHERE dni = '" . $register_document . "' OR username = '" . $register_username . "' OR email = '" . $register_email . "'";
   $result = mysqli_query($link, $query);
   $row = mysqli_fetch_row($result);
   if ($row[0] > 0) {
   
      header("location: campusRegisterAccount.php?errorForm=Ya existe una cuenta con el mismo username, dni o email. Trate de acceder o solicitar recuperar tu contraseña.");
      exit;
   
   } else {
         
      //printf ($highest_id);
      //exit;
   
      $queryUsername = "INSERT INTO tabUsuario (`id`, `DNI`, `username`, `password`, `active`, `name`, `surname`, `email`, `date_register`, `email_validado`) 
      VALUES (".$highest_id.",'".$register_document."','".$register_username."','".$hashPassword."','N', '".$register_name."','".$register_surname."','".$register_email."', now(), '0')";
   
      //printf ($queryUsername);
      //exit;
   
      //printf("Select returned resultUsername (".$register_username . "/" . $session_password.") = %d rows. " . $queryUsername ." \n", mysqli_num_rows($resultUsername));
      if (mysqli_query($link, $queryUsername)) {
         // OK

         $subject = 'Confirmación de cuenta OpositandoBien.es';
         
         /*$message = 'Hola para confirmar la cuenta debes continuar el siguiente enlace: https://40014108.servicio-online.net/campusControllerValidarEmail.php?hash=' . $hashPassword;*/
         $message = "
            <html>
            <head>
            <title>Confirmación de cuenta OpositandoBien.es</title>
            </head>
            <body>
            <p>Hola, bienvenido a OpositandoBien.es </p>

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
            $headers .= 'From: <opositandobien@gmail.com>' . "\r\n";
         
         mail($register_email .',opositandobien@gmail.com', $subject, $message, $headers);

         $_SESSION["register_session_username"] = $register_username;
         $_SESSION["register_session_password"] = $register_password;

         header("location: campusControllerLoginUser.php");
         exit;
      } else {
         // KO
         header("location: campusRegisterAccount.php?errorForm=Ha ocrurrido un error en la creacion del usuario. Si el problema persiste, pongase en contacto con soporte.");
         exit;
      }
   
   }
} else {
   //printf("ERROR: las constaseñas NO coinciden");
   header("location: campusRegisterAccount.php?errorlabelpassword2=Las contraseñas no coinciden");
   exit;
}

?>