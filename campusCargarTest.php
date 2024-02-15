<meta charset="UTF-8">

<?php

if(!isset($_SESSION)) {session_start();}
header("Content-Type: text/html;charset=utf-8");

// Include config environment file
require_once "campusConfig.php";

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!($_SESSION["session_username"] == "jillansa")) {
	header("location: campus.php");
	exit;
}

$queryCuerpo = "SELECT id, descripcion, nivel FROM tabCuerpo WHERE activa= 'S' ORDER BY orden asc, nivel, descripcion ASC";
$resultCuerpo = mysqli_query($link, $queryCuerpo);

$queryExamen = "SELECT id, descripcion, fecha_examen, modalidad FROM tabExamen ORDER BY fecha_examen desc, descripcion ASC";
$resultExamen = mysqli_query($link, $queryExamen);


$queryClasificacion = "SELECT id, bloque, tema, tipo FROM tabClasificacion ORDER BY bloque, tema";
$resultClasificacion = mysqli_query($link, $queryClasificacion);
$resultClasificacion2 = mysqli_query($link, $queryClasificacion);
$resultClasificacion3 = mysqli_query($link, $queryClasificacion);
$resultClasificacion4 = mysqli_query($link, $queryClasificacion);
$resultClasificacion5 = mysqli_query($link, $queryClasificacion);

//mysql_close($link);
?>
 
<!DOCTYPE html>
<html lang="es">

<?php include 'campusHeadIncludes.php';?>

  <body>
	  
	  
	<script type="text/javascript">

		var idPregunta = 0;
		var idPreguntaClasificacion = 0;
		var respuestaCorrecta = "";
		
		function TrimText(el) {
			el.value = el.value.
			replace(/(^\s*)|(\s*$)/gi, ""). // removes leading and trailing spaces
			//replace(/[ ]{2,}/gi, " "). // replaces multiple spaces with one space
			replace(/\n +/, "\n"); // removes spaces after newlines
			return;
		}
		
		window.onload = function () {
			document.querySelectorAll('textarea').forEach(function(el) {
				el.addEventListener('change', function(ev) { TrimText(ev.target); });
			});
		}

	
		$(function () {
			$("#guardarBtn").click(function(evt){
				evt.preventDefault();
				
				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				var ofertaSelect = $("#ofertaSelect").val();
				var examenSelect = $("#examenSelect").val();
				
				for (var i = 1; i < 6; i++) {
				
					var preguntaTexto = $("#preguntaTexto"+i).val();
					var temaSelect = $("#temaSelect"+i).val();
					var repuestaA = $("#areaRespuesta"+i+"A").val();				
					var repuestaB = $("#areaRespuesta"+i+"B").val();				
					var repuestaC = $("#areaRespuesta"+i+"C").val();				
					var repuestaD = $("#areaRespuesta"+i+"D").val();
					var respuestaCheckedA = $("#repuesta"+i+"A").prop('checked');
					var respuestaCheckedB = $("#repuesta"+i+"B").prop('checked');
					var respuestaCheckedC = $("#repuesta"+i+"C").prop('checked');
					var respuestaCheckedD = $("#repuesta"+i+"D").prop('checked');	

					if (preguntaTexto) {
						$.ajax({
							url: "campusControllerInsertarPregunta.php",
							method: "POST",
							data: { cursoCuerpoSelect: cursoCuerpoSelect, temaSelect: temaSelect, 
								ofertaSelect: ofertaSelect, examenSelect: examenSelect,
								preguntaTexto: preguntaTexto, 
								repuestaA: repuestaA, respuestaCheckedA: respuestaCheckedA,
								repuestaB: repuestaB, respuestaCheckedB: respuestaCheckedB,
								repuestaC: repuestaC, respuestaCheckedC: respuestaCheckedC,
								repuestaD: repuestaD, respuestaCheckedD: respuestaCheckedD},
							success: function(dataresponse, statustext, response){
								var data = JSON.parse(dataresponse);
							
								alert("Preguntar cargadas correctamente");
							},
							error: function(request, errorcode, errortext){
								$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
							}
						});
					}
			
				}			
			});
		});

		$(function () {
			$("#limpiarPreguntasBtn").click(function(evt){
				evt.preventDefault();

				for (var i = 1; i < 6; i++) {				
					$("#preguntaTexto"+i).val("")
					$("#temaSelect"+i).val("");
					$("#areaRespuesta"+i+"A").val("");				
					$("#areaRespuesta"+i+"B").val("");				
					$("#areaRespuesta"+i+"C").val("");				
					$("#areaRespuesta"+i+"D").val("");
					$("#repuesta"+i+"A").prop('checked',false);
					$("#repuesta"+i+"B").prop('checked',false);
					$("#repuesta"+i+"C").prop('checked',false);
					$("#repuesta"+i+"D").prop('checked',false);	
				}
			});
		});
		
		$(function () {
			$("#crearExamenBtn").click(function(evt){
				evt.preventDefault();
		
				var cursoCuerpoSelect2 = $("#cursoCuerpoSelect2").val();
				var descripcionExamen = $("#descripcionExamen").val();
				var fechaExamen = $("#fechaExamen").val();
				var modalidadExamen = $("#modalidadExamen").val();
				var anioOfertaExamen = $("#anioOfertaExamen").val();
				var ofertaExamen = $("#ofertaExamen").val();
				var entidadExamen = $("#entidadExamen").val();
								
				$.ajax({
						url: "campusControllerExamen.php",
						method: "POST",
						data: { cursoCuerpoSelect2: cursoCuerpoSelect2, descripcionExamen: descripcionExamen, fechaExamen: fechaExamen, 
							modalidadExamen: modalidadExamen, anioOfertaExamen: anioOfertaExamen,
							ofertaExamen: ofertaExamen, entidadExamen: entidadExamen,
							modo: 'ALTA'},
						success: function(dataresponse, statustext, response){
						
							alert("Examen creado correctamente");

							var modal = document.getElementById("modalAniadirExamen");
							var body = document.getElementsByTagName("body")[0];				
							modal.style.display = "none";
							body.style.position = "inherit";
							body.style.height = "auto";
							body.style.overflow = "visible";

						},
						error: function(request, errorcode, errortext){
							$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
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

	<div class="container-fuild">
        <div class="col-sm-12">
			
			<h3>EXAMEN:</h3>

			<form enctype="multipart/form-data" action="campusControllerCargarFicheroPreguntas.php" method="POST">
				<!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
				<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
				<!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
				Enviar este fichero: 
				<input name="fichero_usuario" type="file" />
				<input type="submit" value="Enviar fichero" />
				<a href="/static/docs/comunes_consolidacion_medicina_SMS_2019.txt" target="_blank" title="Ejemplo fichero" download>Ejemplo fichero</a>							
			</form>		

			<form class="" action="">
				<!--<div class="container-fluid">	-->	
				<!--<div class="row">-->

				<div class="buscadorPanel col-sm-12">
					<br>

					<div class="row">
						
						<div class="col-sm-3">					
							<!-- Se deben mostrar el listado de temas de curso indicado -->
							<label for="examenSelect">Examen:</label><br>	
							<div class="input-group">
								<select class="selectGroup" name="examenSelect" id="examenSelect" style="width: 80%;">
									<option value=""></option>  
									<?php while ($row = mysqli_fetch_array($resultExamen))
									{
									echo "<option value=".$row['id'].">".$row['descripcion']." | " .$row['modalidad']. " | " .$row['fecha_examen']."</option>";

									}
									?>
								</select>	
								

								<button type="button" id="aniadirExamenBtn"
									class="btn btn-success pull-right" aria-label="Center Align">
									<span class="fa fa-plus"></span>
								</button>
							</div>
						</div>

						<div class="col-sm-3">					
							<!-- Limpiar -->
							<label for="limpiarPreguntasBtn">Limpiar Preguntas:</label><br>	
							<div class="input-group">
								<button type="button" id="limpiarPreguntasBtn"
									class="btn btn-success pull-right" aria-label="Center Align">
									<span class="fa fa-trash"></span>
								</button>
							</div>
						</div>
					</div>
					<br>
				</div>

				<div id="modalAniadirExamen" class="modalContainer" style="display: none;">
					<div class="modal-content">
						<span class="close">×</span> 
						<h2>Examen</h2>
						<p>Crear un examen nuevo:</p> 

						<div class="row">
							<div class="col-sm-3">																				
								<label for="cursoCuerpoSelect2">Curso:</label><br>
								<!-- Se deben mostrar solo los cursos para los que el usuario esta matriculado (activo) -->						
								<select name="cursoCuerpoSelect2" id="cursoCuerpoSelect2" style="width: 80%;">
									<option value=""></option>
									<?php 
									while ($row = mysqli_fetch_array($resultCuerpo))
									{
										echo "<option value=".$row['id'].">".$row['descripcion']." | ".$row['nivel']."</option>";

									}
									?>        
								</select>
							</div>
							<div class="col-sm-4">																			
								<label for="descripcionExamen">Descripcion examen:</label><br>
								<input id="descripcionExamen" name="descripcionExamen" type="text" required="true" placeholder="Descripcion">
							</div>
							<div class="col-sm-3">																			
								<label for="modalidadExamen">Modalidad:</label><br>
								<input id="modalidadExamen" name="modalidadExamen" type="text" required="true" placeholder="PI/LI/ESTABILIZACION">
							</div>
						</div>
						<div class="row">								

							<div class="col-sm-3">																			
									<label for="fechaExamen">Fecha Examen:</label><br>
									<input id="fechaExamen" name="fechaExamen" type="date" required="true">
							</div>

							<div class="col-sm-3">																			
									<label for="anioOfertaExamen">Año Convocatoria:</label><br>
									<input id="anioOfertaExamen" name="anioOfertaExamen" type="text" required="true" placeholder="2020">
							</div>

							<div class="col-sm-3">																			
								<label for="ofertaExamen">Entidad:</label><br>
								<input id="ofertaExamen" name="ofertaExamen" type="text" required="true" placeholder="AGE">
							</div>
							
							<div class="col-sm-3">																			
								<label for="entidadExamen">Tipo Entidad:</label><br>						
							
								<select name="entidadExamen" id="entidadExamen">
									<option value=""></option>
									<option value="AGE">AGE</option>
									<option value="CCAA">CCAA</option>
									<option value="AYTO">AYTO</option>
									<option value="Certificacion">Certificacion</option>
									<option value="Curso">Curso</option>
								</select>

							
							</div>

						</div>

						<button id="crearExamenBtn" type="button" class="btn btn-primary pull-right" aria-label="Left Align">
								Guardar
						</button>
					</div>
				</div> 

				<script>
					if(document.getElementById("aniadirExamenBtn")){
						var modal = document.getElementById("modalAniadirExamen");
						var btn = document.getElementById("aniadirExamenBtn");
						var span = document.getElementsByClassName("close")[0];
						var body = document.getElementsByTagName("body")[0];

						btn.onclick = function() {		
							/*if ($('#cursoCuerpoSelect').val() == ''){
								alert('Es necesario seleccionar un cuerpo');
								return;
							}
							if ($('#ofertaSelect').val() == '') {
								alert('Es necesario seleccionar una oferta');
								return;
							}*/
							$('#cursoCuerpoSelect2').val($('#cursoCuerpoSelect').val());
							$("#ofertaModalSelect").val($("#ofertaSelect").val());

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

						window.onclick = function(event) {
							if (event.target == modal) {
								modal.style.display = "none";
								body.style.position = "inherit";
								body.style.height = "auto";
								body.style.overflow = "visible";
							}
						}
					}
				</script>
											
				<div class="col-sm-12" id="panelPreguntas">
					<div class="row">
						<div class="col-sm-9">	
							<div class="" id="preguntaContainer">	
								<div class="form-group shadow-textarea">
					
									<h3>PREGUNTA:</h3>												
									<textarea class="form-control z-depth-1" name="preguntaTexto1" id="preguntaTexto1" rows="5"></textarea>

									<div class="col-sm-3">					
										<!-- Se deben mostrar el listado de temas de curso indicado -->
										<label for="temaSelect1">Clasificación:</label><br>	
										<select class="selectGroup" name="temaSelect1" id="temaSelect1" style="width: 80%;" >
											<option value=""></option>  
												<?php 
												while ($row = mysqli_fetch_array($resultClasificacion))
												{
													if ($row['tipo'] == '1') {
														echo "<optgroup label='".$row['tema']."' value='".$row['id']."'>".$row['tema']."</optgroup>";
														echo "<option value=".$row['id'].">".$row['tema']."(+)</option>";
													} else {
														echo "<option value=".$row['id'].">".$row['tema']."</option>";
													}								
													
													//echo "<option value=".$row['id'].">".$row['bloque']." | ".$row['tema']."</option>";
												}
												?>   
												
										</select>					
									</div>


								</div>
								
								<br>
								
								<div class="">
									<h3>RESPUESTAS:</h3><br>
									
									<table class="table table-hover">
										
										<tr id="rowRepuesta1A">
											<td><input id="repuesta1A" type="radio" name="repuesta1A" value="A"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta1A" id="areaRespuesta1A"></textarea></td>
										</tr>
										<tr id="rowRepuesta1B">
											<td><input id="repuesta1B" type="radio" name="repuesta1B" value="B"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta1B" id="areaRespuesta1B"></textarea></td>
										</tr>
										<tr id="rowRepuesta1C">
											<td><input id="repuesta1C" type="radio" name="repuesta1C" value="C"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta1C" id="areaRespuesta1C"></textarea></td>
										</tr>
										<tr id="rowRepuesta1D"> 
											<td><input id="repuesta1D" type="radio" name="repuesta1D" value="D"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta1D" id="areaRespuesta1D"></textarea></td>
										</tr>

									</table>
								</div>

								<div class="form-group shadow-textarea">
					
									<h3>PREGUNTA:</h3>												
									<textarea class="form-control z-depth-1" name="preguntaTexto2" id="preguntaTexto2" rows="5"></textarea>

									<div class="col-sm-3">					
										<!-- Se deben mostrar el listado de temas de curso indicado -->
										<label for="temaSelect2">Clasificación:</label><br>	
										<select class="selectGroup" name="temaSelect2" id="temaSelect2" style="width: 80%;" >
											<option value=""></option>  
											<?php 
												while ($row = mysqli_fetch_array($resultClasificacion2))
												{
													if ($row['tipo'] == '1') {
														echo "<optgroup label='".$row['tema']."' value='".$row['id']."'>".$row['tema']."</optgroup>";
														echo "<option value=".$row['id'].">".$row['tema']."(+)</option>";
													} else {
														echo "<option value=".$row['id'].">".$row['tema']."</option>";
													}								
													
													//echo "<option value=".$row['id'].">".$row['bloque']." | ".$row['tema']."</option>";
												}
												?>  
										</select>					
									</div>


								</div>
								
								<br>
								
								<div class="">
									<h3>RESPUESTAS:</h3><br>
									
									<table class="table table-hover">
										
										<tr id="rowRepuesta2A">
											<td><input id="repuesta2A" type="radio" name="repuesta2A" value="A"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta2A" id="areaRespuesta2A"></textarea></td>
										</tr>
										<tr id="rowRepuesta2B">
											<td><input id="repuesta2B" type="radio" name="repuesta2B" value="B"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta2B" id="areaRespuesta2B"></textarea></td>
										</tr>
										<tr id="rowRepuesta2C">
											<td><input id="repuesta2C" type="radio" name="repuesta2C" value="C"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta2C" id="areaRespuesta2C"></textarea></td>
										</tr>
										<tr id="rowRepuesta2D"> 
											<td><input id="repuesta2D" type="radio" name="repuesta2D" value="D"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta2D" id="areaRespuesta2D"></textarea></td>
										</tr>

									</table>
								</div>

								<div class="form-group shadow-textarea">
					
									<h3>PREGUNTA:</h3>												
									<textarea class="form-control z-depth-1" name="preguntaTexto3" id="preguntaTexto3" rows="5"></textarea>

									<div class="col-sm-3">					
										<!-- Se deben mostrar el listado de temas de curso indicado -->
										<label for="temaSelect3">Clasificación:</label><br>	
										<select class="selectGroup" name="temaSelect3" id="temaSelect3" style="width: 80%;" >
											<option value=""></option>  
											<?php 
												while ($row = mysqli_fetch_array($resultClasificacion3))
												{
													if ($row['tipo'] == '1') {
														echo "<optgroup label='".$row['tema']."' value='".$row['id']."'>".$row['tema']."</optgroup>";
														echo "<option value=".$row['id'].">".$row['tema']."(+)</option>";
													} else {
														echo "<option value=".$row['id'].">".$row['tema']."</option>";
													}								
													
													//echo "<option value=".$row['id'].">".$row['bloque']." | ".$row['tema']."</option>";
												}
												?>  
										</select>					
									</div>


								</div>
								
								<br>
								
								<div class="">
									<h3>RESPUESTAS:</h3><br>
									
									<table class="table table-hover">
										
										<tr id="rowRepuesta3A">
											<td><input id="repuesta3A" type="radio" name="repuesta3A" value="A"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta3A" id="areaRespuesta3A"></textarea></td>
										</tr>
										<tr id="rowRepuesta3B">
											<td><input id="repuesta3B" type="radio" name="repuesta3B" value="B"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta3B" id="areaRespuesta3B"></textarea></td>
										</tr>
										<tr id="rowRepuesta3C">
											<td><input id="repuesta3C" type="radio" name="repuesta3C" value="C"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta3C" id="areaRespuesta3C"></textarea></td>
										</tr>
										<tr id="rowRepuesta3D"> 
											<td><input id="repuesta3D" type="radio" name="repuesta3D" value="D"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta3D" id="areaRespuesta3D"></textarea></td>
										</tr>

									</table>
								</div>

								<div class="form-group shadow-textarea">
					
									<h3>PREGUNTA:</h3>												
									<textarea class="form-control z-depth-1" name="preguntaTexto4" id="preguntaTexto4" rows="5"></textarea>

									<div class="col-sm-3">					
										<!-- Se deben mostrar el listado de temas de curso indicado -->
										<label for="temaSelect4">Clasificación:</label><br>	
										<select class="selectGroup" name="temaSelect4" id="temaSelect4" style="width: 80%;" >
											<option value=""></option>  
											<?php 
												while ($row = mysqli_fetch_array($resultClasificacion4))
												{
													if ($row['tipo'] == '1') {
														echo "<optgroup label='".$row['tema']."' value='".$row['id']."'>".$row['tema']."</optgroup>";
														echo "<option value=".$row['id'].">".$row['tema']."(+)</option>";
													} else {
														echo "<option value=".$row['id'].">".$row['tema']."</option>";
													}								
													
													//echo "<option value=".$row['id'].">".$row['bloque']." | ".$row['tema']."</option>";
												}
												?>  
										</select>					
									</div>


								</div>
								
								<br>
								
								<div class="">
									<h3>RESPUESTAS:</h3><br>
									
									<table class="table table-hover">
										
										<tr id="rowRepuesta4A">
											<td><input id="repuesta4A" type="radio" name="repuesta4A" value="A"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta4A" id="areaRespuesta4A"></textarea></td>
										</tr>
										<tr id="rowRepuesta4B">
											<td><input id="repuesta4B" type="radio" name="repuesta4B" value="B"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta4B" id="areaRespuesta4B"></textarea></td>
										</tr>
										<tr id="rowRepuesta4C">
											<td><input id="repuesta4C" type="radio" name="repuesta4C" value="C"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta4C" id="areaRespuesta4C"></textarea></td>
										</tr>
										<tr id="rowRepuesta4D"> 
											<td><input id="repuesta4D" type="radio" name="repuesta4D" value="D"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta4D" id="areaRespuesta4D"></textarea></td>
										</tr>

									</table>
								</div>

								<div class="form-group shadow-textarea">
					
									<h3>PREGUNTA:</h3>												
									<textarea class="form-control z-depth-1" name="preguntaTexto5" id="preguntaTexto5" rows="5"></textarea>

									<div class="col-sm-3">					
										<!-- Se deben mostrar el listado de temas de curso indicado -->
										<label for="temaSelect5">Clasificación:</label><br>	
										<select class="selectGroup" name="temaSelect5" id="temaSelect5" style="width: 80%;" >
											<option value=""></option>  
											<?php 
												while ($row = mysqli_fetch_array($resultClasificacion5))
												{
													if ($row['tipo'] == '1') {
														echo "<optgroup label='".$row['tema']."' value='".$row['id']."'>".$row['tema']."</optgroup>";
														echo "<option value=".$row['id'].">".$row['tema']."(+)</option>";
													} else {
														echo "<option value=".$row['id'].">".$row['tema']."</option>";
													}								
													
													//echo "<option value=".$row['id'].">".$row['bloque']." | ".$row['tema']."</option>";
												}
												?>  
										</select>					
									</div>


								</div>
								
								<br>
								
								<div class="">
									<h3>RESPUESTAS:</h3><br>
									
									<table class="table table-hover">
										
										<tr id="rowRepuesta5A">
											<td><input id="repuesta5A" type="radio" name="repuesta5A" value="A"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta5A" id="areaRespuesta5A"></textarea></td>
										</tr>
										<tr id="rowRepuesta5B">
											<td><input id="repuesta5B" type="radio" name="repuesta5B" value="B"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta5B" id="areaRespuesta5B"></textarea></td>
										</tr>
										<tr id="rowRepuesta5C">
											<td><input id="repuesta5C" type="radio" name="repuesta5C" value="C"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta5C" id="areaRespuesta5C"></textarea></td>
										</tr>
										<tr id="rowRepuesta5D"> 
											<td><input id="repuesta5D" type="radio" name="repuesta5D" value="D"></td>
											<td><textarea class="form-control z-depth-1" name="areaRespuesta5D" id="areaRespuesta5D"></textarea></td>
										</tr>

									</table>
								</div>

								
								<div class="">												
								
									<button id="guardarBtn" type="submit" class="btn btn-primary pull-right" aria-label="Left Align">
										<i class="fa fa-arrow-right" aria-hidden="true"></i>
										Guardar
									</button>

								</div>
							</div>	
						</div>
					</div>	
				</div>
			</form>
		</div>
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