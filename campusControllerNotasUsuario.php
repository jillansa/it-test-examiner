<?php

session_start();
header("Content-Type: text/html;charset=utf-8");
// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$notasUsuario = $_POST["notasUsuario"];

$queryUsername = "UPDATE tabUsuario SET notasUsuario =  '" . $notasUsuario . "' WHERE id = " . $_SESSION["session_id_username"] ; 
//print ($queryUsername);
//exit;

mysqli_query($link, $queryUsername);

if (mysqli_affected_rows($link) > 0) {
      $_SESSION["notasUsuario"] = $notasUsuario;
      printf ("OK");
      exit;
} else {
      printf ("Ha ocurrido un problema: " . $queryUsername);
      exit;
}

?>