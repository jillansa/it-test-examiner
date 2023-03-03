<!DOCTYPE html>
<html lang="es">
		
	<?php
		session_start();
		require_once "campusConfig.php";
		header("Content-Type: text/html;charset=utf-8");

		// Check if the user is already logged in, if yes then redirect him to welcome page
		if(!($_SESSION["session_username"] == "jillansa")) {
			header("location: campus.php");
			exit;
		}

		//Oferta configurada
		$queryOfertaCuerpo = "SELECT c.id idCuerpo, c.descripcion cuerpo, 
		c.nivel, c.activa cuerpoActiva, o.id idOferta, o.descripcion, o.anio, a.id idAdministracion,
		a.nombre administracion, a.activa administracionActiva,
		oc.id idOfertaCuerpo, oc.numPlazas, oc.activa ofertaCuerpoActiva, 
		(SELECT count(1) FROM tabOfertaCuerpo_Clasificacion occ 
		WHERE occ.idOfertaCuerpo = oc.id) as numTemas
		FROM tabCuerpo c, tabOferta o, tabOfertaCuerpo oc, tabAdministracion a
		WHERE oc.idCuerpo = c.id
		AND oc.idOferta = o.id
		AND o.idAdministracion = a.id
		ORDER BY a.id, o.anio DESC, c.nivel asc";

		$resultOfertaCuerpo = mysqli_query($link, $queryOfertaCuerpo);
		//printf($queryOfertaCuerpo);
		//exit;

		//Cuerpos disponibles
		$queryCuerpo = "SELECT id, descripcion, nivel, activa FROM tabCuerpo WHERE activa = 'S' ORDER BY nivel, descripcion ASC";
		$resultCuerpo = mysqli_query($link, $queryCuerpo);

		//Administracion disponibles
		$queryAdministracion = "SELECT id, nombre FROM tabAdministracion WHERE activa = 'S' ORDER BY ID ASC";
		$resultAdministracion = mysqli_query($link, $queryAdministracion);

		//Oferta disponibles
		$queryOferta = "SELECT o.id idOferta, o.descripcion, o.anio, 
			a.id idAdministracion, a.nombre nombreAdministracion 
			FROM tabOferta o, tabAdministracion a 
			WHERE o.idAdministracion = a.id ORDER BY o.descripcion ASC";
		$resultOferta = mysqli_query($link, $queryOferta);

		//Administracion clasificacion original disponibles
		/*$queryClasificacion = "SELECT id, tema, bloque, tipo, idBloque
			FROM tabClasificacionOriginal ORDER BY bloque ASC, tipo DESC, tema ASC";
		$resultClasificacion = mysqli_query($link, $queryClasificacion);*/

		// bloques temas
		$queryOfertaCuerpo = "SELECT c.id idCuerpo, c.descripcion cuerpo, 
		c.nivel, c.activa cuerpoActiva, o.id idOferta, o.descripcion, o.anio, a.id idAdministracion,
		a.nombre administracion, a.activa administracionActiva,
		oc.id idOfertaCuerpo, oc.numPlazas, oc.activa ofertaCuerpoActiva, 
		(SELECT count(1) FROM tabOfertaCuerpo_Clasificacion occ 
		WHERE occ.idOfertaCuerpo = oc.id) as numTemas,
		(SELECT count(1) 
		FROM tabOfertaCuerpo_Clasificacion occ, 
		   tabClasificacion c, 
		   tabMapeoClasificacionOriginal mco 
		WHERE occ.idOfertaCuerpo = oc.id 
		   and occ.idClasificacion = c.id 
		   and c.id = mco.idClasificacion) as numClasif
		FROM tabCuerpo c, tabOferta o, tabOfertaCuerpo oc, tabAdministracion a
		WHERE oc.idCuerpo = c.id
		AND oc.idOferta = o.id
		AND o.idAdministracion = a.id
		ORDER BY a.id, o.anio DESC, c.nivel asc";

		$resultOfertaCuerpo = mysqli_query($link, $queryOfertaCuerpo);
	?>

  <?php include 'campusHeadIncludes.php';?>

  <body>

	<script type="text/javascript">

		var idOfertaForm=0;
		var descripcionOfertaUseModal=0;
		var idAdministracionOfertaUseModal=0;
		var anioOfertaUseModal=0;
		var idOfertaCuerpoForm=0;
		var mode='';

		function eliminarOfertaCuerpo(idOfertaCuerpo){
			
			$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { idOfertaCuerpoForm: idOfertaCuerpo, mode: 'DELETEOFERTACUERPO'},
					success: function(dataresponse, statustext, response){
						location.reload();

					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
					}
				});
		}



		function editarOferta(
			idCuerpo, idAdministracion, descripcionOferta, 
			anio, numPlazas, ofertaCuerpoActiva,		
			idOferta, idOfertaCuerpo){

			$("#ofertaDiv").show();
			$("#temarioDiv").hide();

			document.getElementById('administracionSelect').disabled = false;
			document.getElementById('descripcionOfertaInput').disabled = false;
			document.getElementById('anioOfertaInput').disabled = false;

			idOfertaForm=idOferta;
			idOfertaCuerpoForm=idOfertaCuerpo;
			mode='UPDATE';

			let elementCuerpo = document.getElementById('cuerpoSelect');
    		elementCuerpo.value = idCuerpo;

			document.getElementById('administracionSelect').value = idAdministracion;
			document.getElementById('descripcionOfertaInput').value = descripcionOferta;
			document.getElementById('anioOfertaInput').value = anio;
			document.getElementById('ofertaPlazasInput').value = numPlazas;
			document.getElementById('ofertaActivaInput').value = ofertaCuerpoActiva;
    		
			return;
		}
		

		$(function () {
			$("#usarOfertaBtn").click(function(evt){
				evt.preventDefault();

				var elementOferta = document.getElementById('ofertaSelect');

				var text = elementOferta.options[elementOferta.selectedIndex].text;
				const myTextArray = text.split(" | ");
				descripcionOfertaUseModal=myTextArray[0];
				idAdministracionOfertaUseModal=myTextArray[2].split(')')[0];
				anioOfertaUseModal=myTextArray[1];

				var modal = document.getElementById("modalAniadirOferta");
				var body = document.getElementsByTagName("body")[0];				
				modal.style.display = "none";
				body.style.position = "inherit";
				body.style.height = "auto";
				body.style.overflow = "visible";
								
				$("#ofertaDiv").show();

				document.getElementById('administracionSelect').disabled = true;
				document.getElementById('descripcionOfertaInput').disabled = true;
				document.getElementById('anioOfertaInput').disabled = true;

				idOfertaForm = elementOferta.value;
				idOfertaCuerpoForm=0;
				mode='INSERTOFERTACUERPO';

				document.getElementById('cuerpoSelect').value = 0;
				document.getElementById('administracionSelect').value = idAdministracionOfertaUseModal;
				document.getElementById('descripcionOfertaInput').value = descripcionOfertaUseModal;
				document.getElementById('anioOfertaInput').value = anioOfertaUseModal;
				document.getElementById('ofertaPlazasInput').value = '';
				document.getElementById('ofertaActivaInput').value = '';

			});
		});

		$(function () {
			$("#guardarOfertaBtn").click(function(evt){
				evt.preventDefault();

				var idCuerpo = $("#cuerpoSelect").val();
				var idAdministracionSelect = $("#administracionSelect").val();
				var descripcionOfertaInput = $("#descripcionOfertaInput").val();
				var anioOfertaInput = $("#anioOfertaInput").val();
				var ofertaPlazasInput = $("#ofertaPlazasInput").val();
				var ofertaActivaInput = $("#ofertaActivaInput").val();
				
				$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { idCuerpo: idCuerpo, idOfertaForm: idOfertaForm, 
						idOfertaCuerpoForm: idOfertaCuerpoForm, mode: mode,
						idAdministracionSelect: idAdministracionSelect, 
						descripcionOfertaInput: descripcionOfertaInput, 
						anioOfertaInput: anioOfertaInput, ofertaPlazasInput: ofertaPlazasInput, 
						ofertaActivaInput: ofertaActivaInput},
					success: function(dataresponse, statustext, response){
						location.reload();

					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
					}
				});
				
			});
		});


		function editarTemario(){
			$("#temarioDiv").show();
			$("#temarioSelect").val([]);
			$("#temarioCuerpoOferta").val([]);
			
			$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { idOfertaCuerpoForm: idOfertaCuerpoForm, mode: "CONSULTATEMARIO" },
					success: function(dataresponse, statustext, response){
						cargarTemario(dataresponse);
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});

			return;
		}

		$(function () {
			$("#aniadirClasificacion").click(function(evt){
				evt.preventDefault();				

				var selected = [];
				for (var option of document.getElementById('temarioSelect').options)
				{
					if (option.selected) {
						selected.push(option.value);
					}
				}
				
				$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { idOfertaCuerpoForm: idOfertaCuerpoForm, mode: "ANIADIRCLASIFICACION", 
						selected: selected.toString()},
					success: function(dataresponse, statustext, response){						
						cargarTemario(dataresponse);
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
						$("#temarioSelect").val([]);
						$("#temarioCuerpoOferta").val([]);
					}
				});
				
			});
		});

		function cargarTemario(dataresponse){
			$("#temarioSelect").val([]);
			$("#temarioCuerpoOferta").val([]);

			var data = JSON.parse(dataresponse);
			var select_option = '';
			var select_option2 = '';

			for (index = 0; index < data.length; ++index) {
				option = data[index];
				if (option.tipo == '1') {
					if (option.asignado == 'S') {
						select_option += '<optgroup label="'+option.tema+' / '+option.observaciones+'" value="'+option.id+'">'+option.tema+' / '+option.observaciones+'</optgroup>';
						select_option += '<option value="'+option.id+'">(+) '+option.tema+'</option>';
					}
					select_option2 += '<optgroup label="'+option.tema+' / '+option.observaciones+'" value="'+option.id+'">'+option.tema+' / '+option.observaciones+'</optgroup>';
					select_option2 += '<option value="'+option.id+'">(+) '+option.tema+'</option>';						
				} else {	
					if (option.asignado == 'S') {
						select_option += '<option value="'+option.id+'"> - '+option.tema+'</option>';
					} else {
						select_option2 += '<option value="'+option.id+'"> - '+option.tema+'</option>';
					}
				}
			}

			$('#temarioCuerpoOferta').html('').append(select_option);
			$('#temarioSelect').html('').append(select_option2);

			return;
		}

		$(function () {
			$("#eliminarClasificacion").click(function(evt){
				evt.preventDefault();

				var selected = [];
				for (var option of document.getElementById('temarioCuerpoOferta').options)
				{
					if (option.selected) {
						selected.push(option.value);
					}
				}
				
				$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { idOfertaCuerpoForm: idOfertaCuerpoForm, mode: "ELIMINARCLASIFICACION", 
						selected: selected.toString()},
					success: function(dataresponse, statustext, response){						
						cargarTemario(dataresponse);
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
						$("#temarioSelect").val([]);
						$("#temarioCuerpoOferta").val([]);
					}
				});
				
			});
		});

		$(function () {
			$("#nuevoTemaBtn").click(function(evt){
				evt.preventDefault();

				var nombreTema = $("#nombreNuevoTema").val();
				var nombreBloque = $("#bloqueNuevoTema").val();
				var tipoBloque = $("#tipoNuevoTema").val();
				
				$.ajax({
					url: "campusControllerAdminOferta.php",
					method: "POST",
					data: { nombreTema: nombreTema, nombreBloque:nombreBloque, tipoBloque:tipoBloque, idOfertaCuerpoForm: idOfertaCuerpoForm, mode: "CREARCLASIFICACION"},
					success: function(dataresponse, statustext, response){
						cargarTemario(dataresponse);						
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
						$("#temarioSelect").val([]);
						$("#temarioCuerpoOferta").val([]);
					}
				});

			});
		});



		function cargarClasificacionOriginal(){
			
			var options = $("#temarioCuerpoOferta").val();  			

			if (options.length > 1) {
				alert("Solo se permite seleccionar 1 tema para importar su clasificacion");	
				return false;
			}

			var temarioCuerpoOferta = options[0];
			clasificacionSelect = document.getElementById('clasificacionSelect');
			clasificacionSelect.options.length = 0;			
			$('#clasificacionSelect').html('').append('');			

			$.ajax({
				url: "campusControllerConsultarMapeoTemasOriginal.php",
				method: "POST",
				data: { temarioCuerpoOferta: temarioCuerpoOferta, mode: "SELECT"},
				success: function(dataresponse, statustext, response){
					var data = JSON.parse(dataresponse);

					var select_option = '';
					select_option += '<option value=""></option>';
					// Load the new options
					for (index = 0; index < data.length; ++index) {
						option = data[index];
						if (option.tipo == '1') {
							select_option += '<optgroup label="'+ option.tema +'" value="'+option.id+'">'+option.tema+'</optgroup>';
						} else {
							if (option.selected == 'S') {
								select_option += '<option value="'+option.id+'" selected> - '+option.tema+'</option>';
							} else {
								select_option += '<option value="'+option.id+'"> - '+option.tema+'</option>';
							}							
						}
					}

					$('#clasificacionSelect').html('').append(select_option)
					$('#clasificacionSelect').prop('disabled', false);

					modal2.style.display = "block";
					body2.style.position = "static";
					body2.style.height = "100%";
					body2.style.overflow = "hidden";	

				},
				error: function(request, errorcode, errortext){
					$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
				}
			});
		}

		$(function () {
			$("#usarImportarClasificacionBtn").click(function(evt){
				evt.preventDefault();

				var options = $("#temarioCuerpoOferta").val();  			
				if (options.length > 1) {
					alert("Solo se permite seleccionar 1 tema para importar su clasificacion");	
					return false;
				}
				var temarioCuerpoOferta = options[0];
				
				var clasificacionOptions = $("#clasificacionSelect").val();  			
				
				$.ajax({
					url: "campusControllerConsultarMapeoTemasOriginal.php",
					method: "POST",
					data: { temarioCuerpoOferta: temarioCuerpoOferta, clasificacionOptions: clasificacionOptions.toString(), 
						mode: "UPDATE_ALL"},
					success: function(dataresponse, statustext, response){
						alert(dataresponse);						
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");						
					}
				});

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
	
	<div class="container-fluid">
				
		<h3>ADM. OFERTA:</h3>
		<form class="" action="">
			<!--<div class="container-fluid">	-->	
			<!--<div class="row">-->

			<div class="buscadorPanel col-sm-12">
				<br>
				<div class="row">
					<div class="col-sm-9">	
																		
						<table style="width:100%;">
						<tr>
							<th>Administracion</th>
							<th>Oferta</th>
							<th>Año</th>

							<th>Cuerpo</th>
							<th>Nivel</th>							
							<th>N_Temas</th>
							<th>N_Clasif</th>
							<th>Activa</th>

							<th style="display:none;">idCuerpo</th>
							<th style="display:none;">idOferta</th>
							<th style="display:none;">idAdministracion</th>
							<th style="display:none;">idOfertaCuerpo</th>
							<th style="display:none;">Plazas</th>

							<th></th>
						</tr>
						<?php
						while ($row = mysqli_fetch_array($resultOfertaCuerpo)){
							echo "<tr>";

							echo "<td>".$row['administracion']."</td>";
							echo "<td>".$row['descripcion']."</td>";
							echo "<td>".$row['anio']."</td>";	

							echo "<td>".$row['cuerpo']."</td>";
							echo "<td>".$row['nivel']."</td>";	
							echo "<td>".$row['numTemas']."</td>";	
							echo "<td>".$row['numClasif']."</td>";							
							echo "<td>".$row['ofertaCuerpoActiva']."</td>";	

							echo "<td style='display:none;'>".$row['idCuerpo']."</td>";
							echo "<td style='display:none;'>".$row['idOferta']."</td>";
							echo "<td style='display:none;'>".$row['idAdministracion']."</td>";
							echo "<td style='display:none;'>".$row['idOfertaCuerpo']."</td>";
							echo "<td style='display:none;'>".$row['numPlazas']."</td>";
							
							?>
							<td>
								<button id="" type="button"
									class="btn btn-primary pull-right" aria-label="Left Align"
									onclick="editarOferta(
														<?php printf($row['idCuerpo']);?>,
														'<?php printf($row['idAdministracion']);?>',
														'<?php printf($row['descripcion']);?>',
														'<?php printf($row['anio']);?>',
														'<?php printf($row['numPlazas']);?>',
														'<?php printf($row['ofertaCuerpoActiva']);?>', 
														<?php printf($row['idOferta']);?>, 
														<?php printf($row['idOfertaCuerpo']);?>);">
									Editar
								</button>
								<button id="" type="button"
									class="btn btn-danger pull-right" aria-label="Center Align"
									onclick="eliminarOfertaCuerpo(<?php printf($row['idOfertaCuerpo']);?>);">
									<i class="fa fa-remove" aria-hidden="true"></i>X</button>
							</td>

							<?php
							
							echo "</tr>";
						}
						?>

						</table>

						<?php //printf($queryOfertaCuerpo); ?>

						<button id="aniadirCuerpoBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
							<i class="fa fa-plus" aria-hidden="true"></i> Añadir Cuerpo
						</button>
						<button id="aniadirOfertaBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
							<i class="fa fa-plus" aria-hidden="true"></i> Añadir Oferta
						</button>
						<button id="aniadirOfertaCuerpoBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
							<i class="fa fa-plus" aria-hidden="true"></i> Añadir Convocatoria
						</button>

					</div>
					<div id="ofertaDiv" class="col-sm-3" style="display:none">
						
						<div class="col-sm-12">
							<label for="administracionSelect">Administracion:</label><br>
							<select name="administracionSelect" id="administracionSelect" type="text" style="width: 80%;">
								<option value=""></option>  
								<?php 
								while ($row = mysqli_fetch_array($resultAdministracion))
								{
									echo "<option value=".$row['id'].">".$row['nombre']."</option>";
								}
								?>   
							</select>
						</div>

						<div class="col-sm-12">
							<label for="descripcionOfertaInput">Descripcion oferta:</label><br>
							<input id="descripcionOfertaInput" name="descripcionOfertaInput" type="text" style="width: 80%;">
						</div>

						<div class="col-sm-12">
							<label>Año</label><br>
							<input id="anioOfertaInput" type="text" style="width: 80%;">
						</div>

						<div class="col-sm-12">																			
							<label for="cuerpoSelect">Cuerpo:</label><br>
							<!-- Se deben mostrar solo los cursos para los que el usuario esta matriculado (activo) -->						
							<select name="cuerpoSelect" id="cuerpoSelect" style="width: 80%;">
								<option value=""></option>
								<?php 
								while ($row = mysqli_fetch_array($resultCuerpo))
								{
									echo "<option value=".$row['id'].">".$row['descripcion']." | ".$row['nivel']."</option>";
								}
								?>        
							</select>
						</div>

						<div class="col-sm-12">
							<label>Plazas</label><br>
							<input id="ofertaPlazasInput" type="text" style="width: 80%;"> 
						</div>

						<div class="col-sm-12">
							<label>Activa</label><br>
							<input id="ofertaActivaInput" type="text" style="width: 80%;"> 
						</div>

						<div class="col-sm-12">	
							<button id="guardarOfertaBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
								Guardar
							</button>
							<button id="" type="button" class="btn btn-primary pull-right" aria-label="Left Align" onclick="editarTemario();return false;">
								Temario Convocatoria
							</button>
						</div>
					</div>
				</div>
			</div>

			<div id="modalAniadirOferta" class="modalContainer" style="display: none;">
				<div class="modal-content">
					<span class="close">×</span> 
					<h2>Oferta</h2>
					<p>Puedes utilizar una oferta existente o crear una nueva:</p> 

					<div class="col-sm-12">																			
							<label for="ofertaSelect">Oferta:</label><br>
							<select name="ofertaSelect" id="ofertaSelect" style="width: 80%;">
								<?php 
								while ($row = mysqli_fetch_array($resultOferta))
								{
									echo "<option value=".$row['idOferta'].">".$row['descripcion']." | ".$row['anio']." | ".$row['idAdministracion'].") ".$row['nombreAdministracion']."</option>";
								}
								?>        
							</select>
					</div>
					<button id="usarOfertaBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
							Usar
					</button>
				</div>
			</div> 

			<script>
				if(document.getElementById("aniadirOfertaCuerpoBtn")){
					var modal = document.getElementById("modalAniadirOferta");
					var btn = document.getElementById("aniadirOfertaCuerpoBtn");
					var span = document.getElementsByClassName("close")[0];
					var body = document.getElementsByTagName("body")[0];

					btn.onclick = function() {						
						$("#temarioDiv").hide();
						
						modal.style.display = "block";
						body.style.position = "static";
						body.style.height = "100%";
						body.style.overflow = "hidden";
						return false;
					}

					span.onclick = function() {
						modal.style.display = "none";
						body.style.position = "inherit";
						body.style.height = "auto";
						body.style.overflow = "visible";
					}

				}
			</script>
		</form>
		
		
		<form class="" action="">
			<div id="temarioDiv" class="col-sm-12" style="display:none">
				<h3>TEMARIO OFICIAL CONVOCATORIA:</h3>
				<div class="row">		
			
					<div class="col-sm-5">					
						<label for="temarioSelect">Temas disponibles:</label><br>
						<select id="temarioSelect" multiple="multiple" size="30" style="width:100%;height: 100%;">
						</select>				
						
					</div>
					<div class="col-sm-2">
						<div class="row">	
							<div class="centerInContainer">
								<button id="aniadirClasificacion" type="button" class="btn btn-primary pull-center">
									<i class="fa fa-arrow-right" aria-hidden="true"></i>
								</button>
							</div>	
						</div>
						<div class="row">	
							<div class="centerInContainer">
								<button id="eliminarClasificacion" type="button" class="btn btn-primary pull-center">
									<i class="fa fa-arrow-left" aria-hidden="true"></i>
								</button>
							</div>
						</div>
						<div class="row">	
							<div class="centerInContainer">
								<button id="importarClasificacion" type="button" class="btn btn-primary pull-center">
									<i class="fa fa-copy" aria-hidden="true"></i>
								</button>
							</div>
						</div>

						<div class="row">	
							<div id="crearClasificacion" class="" style="">
								<div class="">							
									<h2>Tema</h2>
									<p>Puedes añadir un nuevo tema al catalogo:</p> 

									<div class="col-sm-12">																			
										<label>Nombre Tema</label><br>
										<input id="nombreNuevoTema" type="text" style="width: 100%;"> 
									</div>

									<div class="col-sm-12">																			
										<label>Bloque</label><br>
										<input id="bloqueNuevoTema" type="text" style="width: 100%;"> 
									</div>

									<div class="col-sm-12">																			
										<label>Tipo</label><br>
										<select id="tipoNuevoTema" name="tipoNuevoTema">
											<option value="0">TEMA</option>
											<option value="1" selected>BLOQUE</option>
										</select> 
									</div>

									<div class="col-sm-12">
										<button id="nuevoTemaBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
												Crear
										</button>
									</div>
								</div>
							</div> 
						
							<br>
																	
						</div>

					</div>
					<div class="col-sm-5">					
						<label for="temarioCuerpoOferta">Temario oficial de la convocatoria:</label><br>
						<select id="temarioCuerpoOferta" multiple="multiple" size="30" style="width:100%;height: 100%;">
						</select>	
					</div>

					<div id="modalImportarClasificacion" class="modalContainer" style="display: none;">
						<div class="modal-content">
							<span class="close">×</span> 
							<h2>ImportarClasificacion</h2>
							<p>Elegir un tema para vincular todas las preguntas al tema:</p> 

							<div class="col-sm-12">																			
									<label for="clasificacionSelect">Clasificacion:</label><br>
									<select name="clasificacionSelect" multiple="multiple" size="20" id="clasificacionSelect" style="width: 80%;">
									</select>
							</div>
							<button id="usarImportarClasificacionBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
									Usar
							</button>
						</div>
					</div> 

					<script>
						if(document.getElementById("importarClasificacion")){
							var modal2 = document.getElementById("modalImportarClasificacion");
							var btn2 = document.getElementById("importarClasificacion");
							var span2 = document.getElementsByClassName("close")[1];
							var body2 = document.getElementsByTagName("body")[0];

							btn2.onclick = function() {										
								cargarClasificacionOriginal();								
								return false;
							}

							span2.onclick = function() {
								modal2.style.display = "none";
								body2.style.position = "inherit";
								body2.style.height = "auto";
								body2.style.overflow = "visible";
							}

							window.onclick = function(event) {
								if (event.target == modal2) {
									modal2.style.display = "none";
									body2.style.position = "inherit";
									body2.style.height = "auto";
									body2.style.overflow = "visible";
								}
								if (event.target == modal) {
									modal.style.display = "none";
									body.style.position = "inherit";
									body.style.height = "auto";
									body.style.overflow = "visible";
								}
							}
						}
					</script>			

				</div>
				<br>
				
			</div>
		</form>								
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