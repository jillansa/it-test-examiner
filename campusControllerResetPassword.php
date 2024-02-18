<?php

error_reporting(E_ERROR);

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$session_username = $_POST["session_username"];
$session_password = $_POST["session_password"];
$session_password2 = $_POST["session_password2"];
$hash = $_POST["hash"];

// lo primero, comprobar que ambas pasword son iguales
if ($session_password == $session_password2) {

// actualiazr contraseña del username si el hash es correcto
$options = array("cost"=>4);
$newHashPassword = password_hash($session_password,PASSWORD_BCRYPT,$options);

$queryUsername = "UPDATE tabUsuario SET password = '".$newHashPassword."' WHERE username = '". $session_username ."' AND password='". $hash ."'"; 
//print ($queryUsername);
//exit;

mysqli_query($link, $queryUsername);

if (mysqli_affected_rows($link) > 0) {

      // si OK, renviar al controler de de login para que inicie sesion automaticamente
      $_SESSION["register_session_username"] = $session_username;
      $_SESSION["register_session_password"] = $session_password;

      header("location: campusControllerLoginUser.php");

} else {
      // en caso de error, mostrar la misma pantalla con el error capturado
      header("location: campusResetPassword.php??errorForm=identificacion y contraseña no son correctos.");
      exit;
   }

} else {
   //printf("ERROR: las constaseñas NO coinciden");
   header("location: campusResetPassword.php?username=".$session_username."&hash=" . $hash . "&errorlabelpassword2=Las contraseñas no coinciden");
   exit;
}
?>