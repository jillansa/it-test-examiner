<?php
// Initialize the session
if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");
// Unset all of the session variables
// CLEAR THE INFORMATION FROM THE $_SESSION ARRAY
$_SESSION = array();
 

// IF THE SESSION IS KEPT IN COOKIE, FORCE SESSION COOKIE TO EXPIRE
if (isset($_COOKIE[session_name()]))
{
   setcookie(session_name(), '', $cookie_expires, '/');
}

// Destroy the session.
// TELL PHP TO ELIMINATE THE SESSION
session_destroy();

// SAY GOODBYE...
// echo "YOU ARE LOGGED OUT$uid.  GOODBYE.";

// Redirect to login page
header("location: campus.php");
exit;
?>