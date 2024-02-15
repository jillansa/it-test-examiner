<meta charset="UTF-8">

<script src="https://www.paypal.com/sdk/js?client-id=Ad8w138P47eq6QUZW2cOZYkk-ArbugD-f1dRPaH3Bdrm1jD_JbswkpmH-VCyo2vPZUY2XHigoPx-fDV2&currency=EUR"></script>

<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){

    header("location: campus.php");
    exit;
}

// check si no esta validado el email, volver a la pagina principal
if($_SESSION["email_validado"] != '1'){

    header("location: campus.php");
    exit;	
}

// check si no esta activa la suscipcion, no haya superado el periodo de prueba
if($_SESSION["active"] != 'S' && $_SESSION["trial_days"] > 1){

    header("location: campus.php");
    exit;	
}

// Include config environment file
require_once "campusConfig.php";

//$queryCuerpo = "SELECT id, descripcion, nivel FROM tabCuerpo WHERE activa= 'S' ORDER BY nivel, descripcion ASC";
//$resultCuerpo = mysqli_query($link, $queryCuerpo);
//printf("Select returned resultCuerpo %d rows.\n", mysqli_num_rows($resultCuerpo));


// TODO, la lista de temas, deberia consultarse tras seleccionar el cuerpo (campo obligatorio)
//$queryTema = "SELECT id, tema, bloque, tipo FROM tabClasificacion ORDER BY bloque ASC, tipo DESC, tema ASC";
//$resultTema = mysqli_query($link, $queryTema);
//printf("Select returned resultTema %d rows.\n", mysqli_num_rows($resultTema));

//mysql_close($link);
?>
 
<!DOCTYPE html>
<html lang="es">

<?php include 'campusHeadIncludes.php';?>


  <body>
	  
   
	<script type="text/javascript">
		
		$(function () {
			$("input[type='radio'][name='temario']").click(function(evt){
				$('#paypal-button-container').show();	
				// jump anchor 
				/*var top = 0;
				var element = document.getElementById('paypalPanel');

				do {
					top += element.offsetTop  || 0;
					element = element.offsetParent;
				} while(element);
				
				window.scrollTo(0, top-25);	*/	
			});
		});			

	</script>

	<div class="container-fluid">	  
		<div class="row">
			<div class="col-sm-12">
				<a href="campus.php"><img class="headImage" src="static/images/campus_head.jpg" alt="Campus FORMA TIC - Preparacion de oposiciones y certificaciones"></a>
			</div>
		</div>
	</div>
	
	<?php include 'campusNavbar2.php';?>

	<div class="container-fuild">
        <div class="col-sm-12">
			
			<h3>TEMARIO:</h3>
			
			<!--<div class="container-fluid">	-->	
			<!--<div class="row">-->

			<div class="buscadorPanel col-sm-12">

				<br>

				<div class="row">
				
					<div class="col-sm-3">	

						<script type="text/javascript">
			
							$(function () {
								$(".linkTable").click(function(evt){
									$('#comunesTable').hide();	
									$('#admElectronicaTable').hide();	
									$('#infoSistemasTable').hide();
									$('#infoRedesTable').hide();
									$('#infoSoftwareTable').hide();
									$('#infoSeguridadTable').hide();
									$('#infoOrganizacionTable').hide();
									$('#paypal-button-container').hide();	

									if (this.id == "comunesLink") {$('#comunesTable').show();} 
									if (this.id == "admElectronicaLink") {$('#admElectronicaTable').show();} 
									if (this.id == "sistemasLink") {$('#infoSistemasTable').show();} 
									if (this.id == "redesLink") {$('#infoRedesTable').show();} 
									if (this.id == "ingSoftLink") {$('#infoSoftwareTable').show();} 
									if (this.id == "seguridadLink") {$('#infoSeguridadTable').show();} 
									if (this.id == "organizacionLink") {$('#infoOrganizacionTable').show();} 

								});
							});	
						</script>

						<h3>ADMINISTRACION</h3>

						<style type="text/css">							
							#comunesLink:hover, #admElectronicaLink:hover,
							#sistemasLink:hover, #redesLink:hover, 
							#ingSoftLink:hover, #seguridadLink:hover,
							#organizacionLink:hover {
								background-color: skyblue;
							} 
						</style>

						<div>
							<ul>
								<li><a class="linkTable" id="comunesLink" >COMUNES</a></li>
								<li><a class="linkTable" id="admElectronicaLink" >ADM.ELECTRONICA</a></li>
							</ul>
				
							<h3>INFORMATICA</h3>			
							<ul>
								<li><a class="linkTable" id="sistemasLink" >SISTEMAS</a></li>
								<li><a class="linkTable" id="redesLink" >REDES</a></li>
								<li><a class="linkTable" id="ingSoftLink" >ING.SOFTWARE</a></li>
								<li><a class="linkTable" id="seguridadLink" >SEGURIDAD</a></li>
								<li><a class="linkTable" id="organizacionLink" >ORGANIZACION</a></li>
							</ul>
						</div>				
					</div>
					
					<div class="col-sm-9">						

						<!--<h5>ADMINISTRACIÓN AGE (C1)</h5>-->	
						<table id="comunesTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php

							$array = array(
								'1a - COMUNES - LEYES - GENERAL#Contenido: Constitucion española, Adm. General del Estado, Org. Territorial, Regimen Juridico Adm. Publica, Estatuto Basico Trabajadores, Union Euroea.',
								'1b - COMUNES - PAC - LOPD#Contenido: Proc. Administrativo Comun, LOPD.', 
								'1c - COMUNES - OTRAS LEYES#Contenido: Presupuestos.');

							foreach ($array as &$valor ) {
								$tema = explode("#", $valor);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'ADM' 
								AND nombre = '" . $tema[0] . "' 
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];

								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
						
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="ADM_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> 20€ </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 									
								
								<?php
								}						
							}					
							?>
						</table>

						<table id="admElectronicaTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php

							$array = array(								
								'9 - ADM ELECTRONICA, LFE, CARM, TICs, RED CORPORATIVA#ADM ELECTRONICA, LFE, CARM, TICs, RED CORPORATIVA'							
							);

							foreach ($array as &$valor ) {
								$tema = explode("#", $valor);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'ADM' 
								AND nombre = '" . $tema[0] . "' 
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];

								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
						
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="ADM_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> 20€ </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 									
								
								<?php
								}						
							}					
							?>
						</table>

						
						<!--<h5>TÉCNICOS AUX. DE INFORMÁTICA AGE (C1)</h5>-->
						<table id="infoSistemasTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php
							$array3 = array(
								'2a - SISTEMAS INFORMATICOS - HARDWARE#HARDWARE',
								'2b - SISTEMAS INFORMATICOS - SISTEMAS#SISTEMAS',
								'2c - SISTEMAS INFORMATICOS - BBDD#BBDD',
								'2d - SISTEMAS INFORMATICOS - VIRTUALIZACION#VIRTUALIZACION',
								'2e - SISTEMAS INFORMATICOS - SERVIDOR DE APLICACIONES#SERVIDOR DE APLICACIONES',
								'2f - SISTEMAS INFORMATICOS - MOVILES#MOVILES'							
							);

							foreach ($array3 as &$valor3) {
								$tema = explode("#", $valor3);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'INFO' 
								AND nombre = '" . $tema[0] . "'
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];
								
								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="INFO_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 
								<?php
								}						
							}					
						?>
						</table>

						<table id="infoRedesTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php
							$array3 = array(								
								'3 - REDES Y COMUNICACIONES#REDES Y COMUNICACIONES'						);

							foreach ($array3 as &$valor3) {
								$tema = explode("#", $valor3);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'INFO' 
								AND nombre = '" . $tema[0] . "'
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];
								
								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="INFO_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 
								<?php
								}						
							}					
						?>
						</table>

						<table id="infoSoftwareTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php
							$array3 = array(								
								'4a - INGENIERIA DEL SOFTWARE - MODELADO#MODELADO',
								'4b - INGENIERIA DEL SOFTWARE - DESARROLLO BBDD#DESARROLLO BBDD',
								'4b - INGENIERIA DEL SOFTWARE - DESARROLLO JAVA#DESARROLLO JAVA',
								'4b - INGENIERIA DEL SOFTWARE - DESARROLLO NET y otros#DESARROLLO NET y otros',
								'4c - INGENIERIA DEL SOFTWARE - METODOLOGIA#METODOLOGIA'
							);

							foreach ($array3 as &$valor3) {
								$tema = explode("#", $valor3);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'INFO' 
								AND nombre = '" . $tema[0] . "'
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];
								
								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="INFO_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 
								<?php
								}						
							}					
						?>
						</table>

						<table id="infoSeguridadTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php
							$array3 = array(								
								'5 - SEGURIDAD IT#SEGURIDAD IT'
							);

							foreach ($array3 as &$valor3) {
								$tema = explode("#", $valor3);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'INFO' 
								AND nombre = '" . $tema[0] . "'
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];
								
								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="INFO_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 
								<?php
								}						
							}					
						?>
						</table>

						<table id="infoOrganizacionTable" class="table table-striped table-responsive" style="display:none">
							<tr>
								<th>DESCARGAR</th>
								<th>DOCUMENTO</th>
								<th>CONTENIDO</th>
							</tr>
							<?php
							$array3 = array(
								'7a - ITIL - SERVICIOS – ORG#ITIL - SERVICIOS – ORG',
								'7b - GESTION DOC, LDAP, DATAWORKFLOW#GESTION DOC, LDAP, DATAWORKFLOW',
							);

							foreach ($array3 as &$valor3) {
								$tema = explode("#", $valor3);
								
								$queryTemario = "SELECT count(1) as num
								FROM tabTemarioUsuario 
								WHERE cuerpo = 'INFO' 
								AND nombre = '" . $tema[0] . "'
								AND idUsuario = " . $_SESSION["session_id_username"];
								$resultTemario = mysqli_query($link, $queryTemario);
								$row = mysqli_fetch_array($resultTemario);
								//echo $row['num'];
								
								if ($row['num'] > 0) {
							?>
								<tr>
									<td style="text-align:center;"><a href="/static/docs/<?= $tema[0] ?>.pdf" target="_blank" title="<?= $tema[0] ?>"> <i class="fa fa-download" aria-hidden="true"></i></a></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?></p></td>
								</tr>
							<?php
			
								} else {
							?>
								<tr>
									<td style="text-align:center;"><input type="radio" id="INFO_<?= $tema[0] ?>" name="temario" value="<?= $tema[0] ?>"> <i class="fa fa-shopping-cart" aria-hidden="true"></i> </input></td>
									<td><?= $tema[0] ?></td>
									<td><p style="background: #f7f5f5; padding: 15px; border-left: 5px solid #ffa200;"><?= $tema[1]?><br><a href="/static/docs/<?= $tema[0] ?>_PREVIEW.pdf" target="_blank" title="<?= $tema[0] ?>">Ver PREVIEW <i class="fa fa-search" aria-hidden="true"></i></a></p></td>
								</tr> 
								<?php
								}						
							}					
						?>
						</table>
					</div>
										
				</div>

				<hr>

			</div>	

			<div class="col-sm-12"  id="paypalPanel">				

				<div class="row">
					<div class="col-sm-8">
					</div>
					<div class="col-sm-4">
					
						<!-- https://developer.paypal.com/sdk/js/reference/ -->

						<div id="paypal-button-container"  style="display: none">
							<script>

							function getItemDescripcion() {
								var getSelectedValue = document.querySelector( 'input[name="temario"]:checked');  
								return getSelectedValue.value;
							};

							function getCuerpoItem() {
								var getSelectedValue = document.querySelector( 'input[name="temario"]:checked');  
								return getSelectedValue.id.split("_")[0];
							};
									
							paypal.Buttons({
								// Sets up the transaction when a payment button is clicked
								style:{
									layout: 'horizontal',
									shape: 'pill',
									size:'mini'
								},
								createOrder: (data, actions) => {
									return actions.order.create({
										
										purchase_units: [{
											description: 'Temario: ' + getItemDescripcion(),
											amount: {
												currency_code: 'EUR',
												value: '20' // Can also reference a variable or function
											}/*,
											items: [{
												name: 'Temario TAI',
												description: 'Temario TAI',
												unit_amount:{
													currency_code: 'EUR',
													value: '20'
												},
												quantity:'1'
											}]*/
										}]
									});
								},
								// Finalize the transaction after payer approval
								onApprove: (data, actions) => {
									return actions.order.capture().then(function(orderData) {
										// Successful capture! For dev/demo purposes:
										console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
										const transaction = orderData.purchase_units[0].payments.captures[0];
										
										// When ready to go live, remove the alert and show a success message within this page. For example:
										// const element = document.getElementById('paypal-button-container');
										// element.innerHTML = '<h3>Thank you for your payment!</h3>';
										// Or go to another URL:  actions.redirect('thank_you.html');

										//alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
										//actions.redirect("<?php echo $baseDir ?>campusControllerOrderPaymentCapture.php");		

										return fetch("<?= $baseDir ?>campusControllerOrderPaymentCapture.php", {
											method: 'post',
											headers: {
												'content-type': 'application/json'
											},
											body: JSON.stringify({
												paymentID: orderData.id,
												payerID:   orderData.payer.payer_id,
												status:    orderData.status,
												cuerpo     : getCuerpoItem(),
												nombre		: getItemDescripcion(),
												idUsuario	: <?= $_SESSION["session_id_username"] ?>
											})	
										}).then((response) => response.json())
											.then((responseData) => {	
												//console.log("A");
												if(responseData.status == "OK"){
													// redirect to the completed page if paid
													//alert("it worked: " + responseData.cuerpo);	
													//alert("it worked: " + responseData.nombre);	
													//alert("it worked: " + responseData.idUsuario);	
													window.location.href = "<?= $baseDir ?>campusAulaVirtual.php";
												}else{
													alert("La transaccion no ha sido procesada correctamente");
												}
										})
									});
								},
								
								onCancel: function(data, actions) {
									alert("Transaccion cancelada");
								},

								onError: function(err) {
									alert("La transaccion no ha finalizado correctamente");
								}

							}).render('#paypal-button-container'); 
							</script>
						</div>

					</div>
				</div>
			</div>
						

			<div class="container-fluid" style="display:none">
				<div class="col-sm-12">
					<div class="" id="statusConsole">				
					</div>
				</div>
			</div>
		</div>
	</div>	

	<?php include 'campusFooter.php';?>

  </body>
</html>