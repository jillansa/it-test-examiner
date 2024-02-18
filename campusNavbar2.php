

<div class="container-fluid">
	
	<div class="topnav" id="myTopnav">
		
		<a href="campus.php">Campus OpositandoBien.es</a>		
		<a href="campusRealizarTest.php">Tests</a>		
		
		<?php			
			error_reporting(E_ERROR); 
			if(!isset($_SESSION)) {session_start();}

			if(isset($_SESSION["session_username"]) && $_SESSION["session_username"] == "jillansa") {
		?>				
			<a href="campusAulaVirtual.php">Aula Virtual</a>
			<a href="campusCargarTest.php">Cargar Preguntas</a>
			<a href="campusAdminTemarioCuerpos.php">Temario-Cursos</a>
		<?php
			}
		?>
		
		<?php
		
			// Check if the user is already logged in
			if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
		?>

		<a href="campusRegisterAccount.php"> <i class="fa fa-user-plus"></i> Register</a>
		<a href="campusLogin.php"> <i class="fa fa-sign-in-alt"></i> Login</a>
		
		<?php 
			} else {
		?>

		<a href="campusProfile.php"> <i class="fa fa-user"></i> <?php echo " " . $_SESSION["session_username"] ?> </a>
		<a href="campusControllerLogout.php"> <i class="fa fa-sign-out-alt"></i> Logout</a>

		<?php 
			}
		?>		

		<a class="icon" href="javascript:void(0);" onclick="myFunction()">
			<i class="fa fa-bars"></i>
		</a>

	</div>
</div>

<script>
function myFunction() {

  var y = document.getElementById("myTopnav");
  if (y.className.includes(" responsive")) {    
	y.className = y.className.replace(" responsive", "");
  } else {
    y.className += " responsive";
  }

}
</script>
