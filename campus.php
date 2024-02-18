<!DOCTYPE html>
<html lang="es">
	
<?php 
error_reporting(E_ERROR); 
include 'campusHeadIncludes.php';
?>


  <body>
   	
	<div class="container-fluid">	  
		<div class="row">
			<div class="col-sm-12">
				<a href="campus.php"><img class="headImage" src="static/images/campus_head.jpg" alt="Campus FORMA TIC - Preparacion de oposiciones y certificaciones"></a>
			</div>
		</div>
	</div>
	
	<?php include 'campusNavbar2.php';?>
	
	<div class="container-fluid">
		<br><br><br><br><br>
				
			<?php
				if(!isset($_SESSION)) {session_start();}

				// Check if the user is already logged in
				if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true) {
			?>
				<div class="col-sm-12 mensajeCentral">			
					Registrese / Inicie sesion para comenzar a realizar tests de autoevaluacion. 
				</div>
			<?php 
				} else {
			?>
				<div class="col-sm-12 mensajeCentral">
					Bienvenido/a	<?php echo $_SESSION["session_username"] ?>	!
				</div>
				
				<?php 
					
					if(!isset($_SESSION["email_validado"]) || $_SESSION["email_validado"] <> '1') {

				?>							
						<div class="col-sm-12 mensajeCentral">
							Debes validar tu email con el mensaje que hemos enviado a tu direccion de correo.							
							<br>
							En caso de no haberlo recibido, revisa el spam o contacte con nuestro servicio de soporte. 
						</div>
				
				<?php }	else { 		
					// Check if the user is already logged in, if yes then redirect him to welcome page
					if(isset($_SESSION["active"]) && $_SESSION["active"] === 'S') {
				?>
							
					<div class="col-sm-12 mensajeCentral">
						TU SUSCRIPCION ESTA ACTIVA. Puede realizar tests.
					</div>

				<?php }	else { 

						if($_SESSION["trial_days"] < 2) {
						?>
							
							<div class="col-sm-12 mensajeCentral">
								TU PERIODO DE PRUEBA ESTA ACTIVO. Puede realizar tests.
							</div>	
						<?php } else { ?>

							<div class="col-sm-12 mensajeCentral">
								TU PERIODO DE PRUEBA ESTA CADUCADO. Acceda a su perfil y active la suscipcion. 
								
								<br><br>

								<a class="" href="campusProfile.php"> > Ir al perfil: <?php echo $_SESSION["session_username"] ?> </a>
							</div>

							

						<?php }	?>

						<?php
					}
				?>

			<?php 
				}

			}

			?>

		<br><br><br><br><br><br>
	</div>		
	
	<div class="container-fluid">
		<div class="col-sm-12">
			<div class="" id="statusConsole">
			<br><br><br>
			</div>
		</div>
	</div>

	<?php include 'campusFooter.php';?>
	
  </body>
</html>