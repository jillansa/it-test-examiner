<?php
 
if(!isset($_SESSION)) {session_start();}
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
    header("location: campus.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<?php include 'campusHeadIncludes.php';?>

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

		<div class="center">
		
			<div class="row">
				<div class="col-sm-6">
					<div>
						<h1>Perfil de usuario</h1>
					</div>
				</div>
				<div class="col-sm-6">	
					<div>
						<h1>Datos de suscripcion</h1>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
									
					<div class="">
						<label class="labelLogin" id="labelusername" for="username">USERNAME: </label>
						<input id="username" name="username" type="text" readonly value="<?php echo htmlspecialchars($_SESSION["session_username"]);?>">                   
					</div>

					<div class="">
						<label class="labelLogin" id="labelemail" for="email">E-MAIL: </label>
						<input id="email" name="email" type="text" readonly value="<?php echo htmlspecialchars($_SESSION["session_email"]);?>">
					</div>

					<div class="">
						<label class="labelLogin" id="labelname" for="name">NOMBRE: </label>
						<input id="name" name="name" type="text" readonly value="<?php echo htmlspecialchars($_SESSION["session_name"]);?>">
					</div>

					<div class="">
						<label class="labelLogin" id="labelsurname" for="surname">APELLIDOS: </label>
						<input id="surname" name="surname" type="text" readonly value="<?php echo htmlspecialchars($_SESSION["session_surname"]);?>">
					</div>

					<div class="">
						<label class="labelLogin" id="labeldocument" for="document">DNI/NIE/NIF: </label>
						<input id="document" name="document" type="text" readonly value="<?php echo htmlspecialchars($_SESSION["session_document"]);?>">
					</div>
				</div>

				<div class="col-sm-6 panelMiddle">	

					<?php 
						// Check if the user is already logged in, if yes then redirect him to welcome page
						if(isset($_SESSION["active"]) && $_SESSION["active"] === 'S') {
					?>
							
							TU SUSCRIPCION ESTA ACTIVA.

					<?php }	else { 

						if($_SESSION["trial_days"] < 2) {
						?>
							
							TU PERIODO DE PRUEBA ESTA ACTIVO.
								
						<?php } else { ?>

							TU PERIODO DE PRUEBA ESTA CADUCADO.
							<br><br>
							SUCRIBETE PARA CONTINUAR REALIZANDO TEST DE AUTOEVALUACIÓN. 
						
						<?php }	?>

						<br>						
						<br>

						<div id="paypal-button-container-P-3S492140AM7276430MF5S3QI"></div>
						<script src="https://www.paypal.com/sdk/js?client-id=AZGHSlnjgDd58a2BhUil3UJsezZAuOm_EJjl4D_DBiYOmsN1cqQrsA2pY8txN21S7pTz7OS_rHRx4_J-&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
						<script>
							paypal.Buttons({
								style: {
									shape: 'pill',
									color: 'gold',
									layout: 'vertical',
									label: 'subscribe'
								},
								createSubscription: function(data, actions) {
									return actions.subscription.create({
									/* Creates the subscription */
									plan_id: 'P-3S492140AM7276430MF5S3QI'
									});
								},
								onApprove: function(data, actions) {
									alert(data.subscriptionID); // You can add optional success message for the subscriber here
								}
							}).render('#paypal-button-container-P-3S492140AM7276430MF5S3QI'); // Renders the PayPal button
						</script>
					<?php
					}
					?>
					<br><br><br><br><br><br>
				</div>
			</div>
		
			

		</div>
	</div>		
	
	<?php include 'campusFooter.php';?>
	
  </body>
</html>