<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");
// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$user = $_GET["username"];
$hash = $_GET["hash"];

//printf ($user . " / " . $hash);
//exit;

$queryUsername = "UPDATE tabUsuario SET email_validado = 1 WHERE username = '". $user ."' AND password='". $hash ."'"; 
//print ($queryUsername);
//exit;

mysqli_query($link, $queryUsername);

if (mysqli_affected_rows($link) > 0) {
      $_SESSION["email_validado"] = 1;
      header("location: campus.php");
      exit;
} else {
      printf ("Ha ocurrido un problema, el link de validacion esta caducado o es incorrecto.");
      exit;
}

?>