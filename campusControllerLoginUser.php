<?php

error_reporting(E_ERROR);

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");
// consulta BBDD
// Include config environment file
require_once "campusConfig.php";

$session_username = $_SESSION["register_session_username"];

if (isset($session_username)) {
      
   $session_password = $_SESSION["register_session_password"];
   $_SESSION["register_session_username"] = null;
   $_SESSION["register_session_password"] = null;

} else {

   $session_username = $_POST["session_username"];
   $session_password = $_POST["session_password"];
   
}

//printf("Llegamos al controller" . $session_username . "/" . $session_password . "/" . $_SESSION["register_session_username"] . "/". $_SESSION["register_session_password"]);
//exit;
//$options = array("cost"=>4);
//$hashPassword = password_hash($session_password,PASSWORD_BCRYPT,$options);


$queryUsername = "SELECT id, dni, username, active, name, surname, email, date_register, DATEDIFF(now(), date_register) as trial_days, password, email_validado, notasUsuario as notasUsuario FROM tabUsuario 
WHERE (username = '" . $session_username . "'  OR email = '" . $session_username . " ' OR dni = '" . $session_username . "')" ;
//AND password = '" . $hashPassword . "'";

//printf($queryUsername);
//exit;

 $resultUsername = mysqli_query($link, $queryUsername);
//printf("Select returned resultUsername (".$session_username . "/" . $session_password.") = %d rows. " . $queryUsername ." \n", mysqli_num_rows($resultUsername));


if (mysqli_num_rows($resultUsername) > 0) {
   
   $row = mysqli_fetch_array($resultUsername);
   
   // o es password directa (adminsitrador) o cuenta hash 
   if (password_verify($session_password, $row['password']) || $session_password == $row['password']) {
      // Password is correct, so start a new session

      // Store data in session variables
      $_SESSION["loggedin"] = true;
      $_SESSION["active"] = $row['active'];
      $_SESSION["date_register"] = $row['date_register'];
      $_SESSION["session_username"] = $row['username']; 
      $_SESSION["session_id_username"] = $row['id']; 
      $_SESSION["session_email"] = $row['email'];   
      $_SESSION["session_name"] = $row['name'];  
      $_SESSION["session_surname"] = $row['surname'];  
      $_SESSION["session_document"] = $row['dni']; 
      $_SESSION["trial_days"] = $row['trial_days'];  
      $_SESSION["email_validado"] = $row['email_validado'];        

      $_SESSION['aciertosSession'] = 0;       // calculo total de la sesion
      $_SESSION['erroresSession'] = 0;
      $_SESSION['sinContestarSession'] = 0;
      $_SESSION['listaErroresSession']  = "";

      $_SESSION["notasUsuario"] = $row['notasUsuario'];

      header("location: campus.php");
      exit;

   } else {
      // Password isn't correct, so start a new session    
      header("location: campusLogin.php?errorForm=Las datos de identificacion y contraseña no son correctos.");
      exit;
   }

} else {
   
   // Password isn't correct, so start a new session    
   header("location: campusLogin.php?errorForm=Las datos de identificacion y contraseña no son correctos.");
   exit;
}

?>