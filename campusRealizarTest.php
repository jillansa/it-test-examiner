<meta charset="UTF-8">

<?php

session_start();
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

$queryCuerpo = "SELECT id, descripcion, nivel FROM tabCuerpo WHERE activa= 'S' ORDER BY orden asc, nivel, descripcion ASC";
$resultCuerpo = mysqli_query($link, $queryCuerpo);
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

		var idPregunta = 0;
		var idPreguntaClasificacion = 0;
		var respuestaCorrecta = "";
		var idRespuestaA;
		var idRespuestaB;
		var idRespuestaC;
		var idRespuestaD;
		var sinContestarSession = false;
		var acierto = 99;

		$(function () { /* DOM ready */
			$("#cursoCuerpoSelect").change(function() {		

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();

				examenSelect = document.getElementById('examenSelect');
				examenSelect.options.length = 0;
				$('#examenSelect').html('').append('');

				bloqueSelect = document.getElementById('bloqueSelect');
				bloqueSelect.options.length = 0;
				$('#bloqueSelect').html('').append('');

				clasificacionSelect = document.getElementById('clasificacionSelect');
				clasificacionSelect.options.length = 0;
				$('#clasificacionSelect').html('').append('');				
					
				//CARGAR EXAMENES
				$.ajax({
					url: "campusControllerConsultarExamenes.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect },
					success: function(dataresponse, statustext, response){
						var data = JSON.parse(dataresponse);

						var select_option = '';
						select_option += '<option value=""></option>';
						// Load the new options
						for (index = 0; index < data.length; ++index) {
							option = data[index];
							aux ='<option value="'+option.id+'">'+option.fechaExamen+ ' - ' +option.modalidad+ ' | ' +option.descripcion;
							if (option.nombreAdm != null) aux += ' | ' + option.nombreAdm;
							if (option.descripcionOferta != null) aux += ' | ' + option.descripcionOferta;
							
							aux += ' </option>';

							select_option += aux;
						}

						$('#examenSelect').html('').append(select_option)
						$('#examenSelect').prop('disabled', false);
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});

				//CARGAR BLOQUES y CLASIFICACION para el ADMINISTRADOR
				$.ajax({
					url: "campusControllerConsultarTemas.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect },
					success: function(dataresponse, statustext, response){
						var data = JSON.parse(dataresponse);

						var select_option = '';
						select_option += '<option value=""></option>';
						
						var select_option_bloque = '';
						select_option_bloque += '<option value=""></option>';				

						// Load the new options
						for (index = 0; index < data.length; ++index) {
							option = data[index];
							
							if (option.tipo == '1') {
								// NUEVO BLOQUE
								select_option_bloque += '<option value="'+option.id+'">'+option.tema+'</option>';
								
								select_option += '<optgroup label="'+ option.tema +'" value="'+option.id+'">'+option.tema+'</optgroup>';
								select_option += '<option value="'+option.id+'">(+) '+option.tema+'</option>';
							} else {
								// TEMA DEL BLOQUE ACTUAL
								select_option += '<option value="'+option.id+'"> - '+option.tema+'</option>';
							}
						}

						$('#bloqueSelect').html('').append(select_option_bloque)
						$('#bloqueSelect').prop('disabled', false);

						$('#temaSelectPregunta').html('').append(select_option)
						$('#temaSelectPregunta').prop('disabled', false);

					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});


			});
		});


		$(function () { /* DOM ready */
			$("#bloqueSelect").change(function() {		

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				var bloqueSelect = $("#bloqueSelect").val();

				clasificacionSelect = document.getElementById('clasificacionSelect');
				clasificacionSelect.options.length = 0;
				$('#clasificacionSelect').html('').append('');		
				
				//CARGAR CLASIFICACION
				$.ajax({
					url: "campusControllerConsultarTemas.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect, bloqueSelect: bloqueSelect },
					success: function(dataresponse, statustext, response){
						var data = JSON.parse(dataresponse);
					
						var select_option_clasif = '';
						select_option_clasif += '<option value=""></option>';						

						// Load the new options
						for (index = 0; index < data.length; ++index) {
							option = data[index];
							if (option.tipo != '1') {
									select_option_clasif += '<option value="'+option.id+'"> - '+option.tema+'</option>';
							}
						}

						$('#clasificacionSelect').html('').append(select_option_clasif)
						$('#clasificacionSelect').prop('disabled', false);

					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});

			});
		});

		function updateGraphic(data){

			if (data.aciertosSession != 0 || data.erroresSession != 0 || data.sinContestarSession) {
				$('#myChart2').show();
				$('#estadisticasTema2').hide();
				var xValues2 = ["Aciertos en el tema:" , "Errores en el tema:"];
				var yValues2 = [data.aciertosTema, data.erroresTema];
				var barColors = [								
					"#00aba9",
					"#b91d47"];						

				let chart2 = Chart.getChart("myChart2"); // <canvas> id
				if (chart2 != undefined) {
					chart2.destroy();
				}

				new Chart("myChart2", {
				type: "doughnut",
				data: {
					labels: xValues2,
					datasets: [{
					backgroundColor: barColors,
					data: yValues2
					}]
				},
				options: {
					title: {
					display: true,
					text: "Estadisticas por tema"
					}
				}
				});
			
			} else {
				$('#estadisticasTema2').show();
				$('#myChart2').hide();
			}	
			
			
			$('#estadisticasPregunta1').text("ACIERTOS: " + data.aciertosPregunta );
			$('#estadisticasPregunta2').text("ERRORES: " + data.erroresPregunta);

						
			if (data.aciertosSession != 0 || data.erroresSession != 0 || data.sinContestarSession) {
				$('#myChart4').show();
				$('#estadisticasSession2').hide();
				var xValues3 = ["Aciertos en la sesión:", "Errores en la sesión", "Sin contestar"];
				var yValues3 = [data.aciertosSession, data.erroresSession, data.sinContestarSession];
				var barColors = [								
					"#00aba9",
					"#b91d47",
					"#f0a502"];						
				
				let chart4 = Chart.getChart("myChart4"); // <canvas> id
				if (chart4 != undefined) {
					chart4.destroy();
				}

				new Chart("myChart4", {
				type: "doughnut",
				data: {
					labels: xValues3,
					datasets: [{
					backgroundColor: barColors,
					data: yValues3
					}]
				},
				options: {
					title: {
					display: true,
					text: "Estadisticas de la sesión"
					}
				}
				});		
			} else {
				$('#estadisticasSession2').show();
				$('#myChart4').hide();
			}	
		}
 

		$(function () {
			$("#siguienteBtn").click(function(evt){
				evt.preventDefault();	

				// SI NO HAY RESPUESTA, MARCAR PARA LISTADO 
				comprobarRespuestaBtn(0);	
				
				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				var bloqueSelect = $("#bloqueSelect").val();
				var clasificacionSelect = $("#clasificacionSelect").val();
				var examenSelect = $("#examenSelect").val();
				var nivelSelect = $("#nivelSelect").val();
				var percentErrorFilter = $("#percentErrorFilter").val();
				var textFilter = $("#textFilter").val();
				var anioOfertaFilter = $("#anioOfertaFilter").val();
				var sinRespuestasFilter = $("#sinRespuestasFilter").prop('checked');
				var clasifPlusFilter = $("#clasifPlusFilter").prop('checked');
				var bugFilter = $("#bugFilter").prop('checked');
				var noExisteRespuestaFilter = $("#noExisteRespuestaFilter").prop('checked');
				var favoritasFilter = $("#favoritasFilter").prop('checked');

				//document.querySelector('input[name="respuesta"]').checked = false
				document.getElementById("repuestaA").checked = false;
				document.getElementById("repuestaB").checked = false;
				document.getElementById("repuestaC").checked = false;
				document.getElementById("repuestaD").checked = false;					
				$('#rowRepuestaA').hide();
				$('#rowRepuestaB').hide();
				$('#rowRepuestaC').hide();
				$('#rowRepuestaD').hide();
				idRespuestaA=0;	
				idRespuestaB=0;
				idRespuestaC=0;
				idRespuestaD=0;

				$.ajax({
					url: "campusControllerSiguientePregunta.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect, 
						clasificacionSelect: clasificacionSelect, bloqueSelect: bloqueSelect,
						examenSelect: examenSelect,
						nivelSelect: nivelSelect, idPreguntaClasificacion: idPreguntaClasificacion, percentErrorFilter: percentErrorFilter, 
						textFilter: textFilter, anioOfertaFilter: anioOfertaFilter, sinRespuestasFilter: sinRespuestasFilter, favoritasFilter: favoritasFilter, 
						clasifPlusFilter: clasifPlusFilter, bugFilter: bugFilter, noExisteRespuestaFilter: noExisteRespuestaFilter,
						sinContestarSession: sinContestarSession},
					success: function(dataresponse, statustext, response){
						
						acierto = 99;
						sinContestarSession = false;
						
						var data = JSON.parse(dataresponse);
						$("#statusConsole").html("<p>" + data.metadatos + "</p>");							
						$("#estadisticasContainer").show();

						console.log(data.sql);
						
						<?php			
							session_start();
							if($_SESSION["session_username"] == "jillansa") {
						?>	
							$("#preguntaTexto").val(data.texto);
							
							if (data.respuestaA != null){
								$('#areaRespuestaA').val(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').val(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').val(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').val(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php } else { ?>					
							$('#preguntaTexto').text(data.texto);

							if (data.respuestaA != null){
								$('#areaRespuestaA').text(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').text(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').text(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').text(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php
							}
						?>

						idPregunta = data.idPregunta;
						idPreguntaClasificacion = data.idPreguntaClasificacion;
						$('#idPregunta').text("ID: " + data.idPregunta);
						$('#temaPregunta').text("TEMA: " + data.tema);						
						$('#temaSelectPregunta').val(data.idClasificacion);						
						$('#examenPregunta').text("EXAMEN: " + data.examen);
						$('#fechaExamenPregunta').text("FECHA EXAMEN: " + data.fechaExamen);						
						$('#modalidadExamenPregunta').text("MODALIDAD: " + data.modalidad);
						$('#ofertaExamenPregunta').text("CONVOCATORIA: " + data.oferta);						
						$('#estadisticasCursoOfertaText').text(data.cuerpo);
						$('#estadisticasTemaText').text(data.tema);

						if (data.link){			
							$('#pop').show();
							$('#imageresource').attr('src',data.link);
						} else {
							$('#pop').hide();	
							$('#imageresource').attr('src',data.link);
						}
						
						$('#numeroPregunta').text("Quedan " + data.numTotalPreguntas + " preguntas   ");

						if (data.numTotalPreguntas <= 0) {
							$('#siguienteBtn').prop("disabled",true);
						} else {
							$('#siguienteBtn').prop("disabled",false);
						}

						if (data.numTotalPreguntas >= 0 ) {
							$("#preguntaContainer").show();
							$("#preguntaContainerNoData").hide();
						} else {
							$("#preguntaContainer").hide();
							$("#preguntaContainerNoData").show();	
						}

						respuestaCorrecta = data.respuestaCorrecta;						
						
						if (data.favorita > 0) {
							$('#favoritaOnToOffBtn').show();	
							$('#favoritaOffToOnBtn').hide();
						} else {
							$('#favoritaOffToOnBtn').show();
							$('#favoritaOnToOffBtn').hide();	
						}
						
						updateGraphic(data);

						$('.js-example-basic-multiple').select2();

					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});

				// jump anchor 
				var top = 0;
				var element = document.getElementById('panelPreguntas');

				do {
					top += element.offsetTop  || 0;
					element = element.offsetParent;
				} while(element);
				
    			window.scrollTo(0, top-25);
			});
		});

		$(function () {
			$("#buscarBtn").click(function(evt){
				evt.preventDefault();

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				var bloqueSelect = $("#bloqueSelect").val();
				var clasificacionSelect = $("#clasificacionSelect").val();
				var examenSelect = $("#examenSelect").val();
				var nivelSelect = $("#nivelSelect").val();
				var percentErrorFilter = $("#percentErrorFilter").val();
				var textFilter = $("#textFilter").val();
				var anioOfertaFilter = $("#anioOfertaFilter").val();
				var sinRespuestasFilter = $("#sinRespuestasFilter").prop('checked');
				var clasifPlusFilter = $("#clasifPlusFilter").prop('checked');	
				var bugFilter = $("#bugFilter").prop('checked');	
				var noExisteRespuestaFilter = $("#noExisteRespuestaFilter").prop('checked');
				var favoritasFilter = $("#favoritasFilter").prop('checked');

				if (cursoCuerpoSelect == "") {
					alert("El campo 'Cuerpo' es obligatorio para iniciar la sesion de tests");
					return;
				}

				//document.querySelector('input[name="respuesta"]').checked = false
				document.getElementById("repuestaA").checked = false;
				document.getElementById("repuestaB").checked = false;
				document.getElementById("repuestaC").checked = false;
				document.getElementById("repuestaD").checked = false;
				$('#rowRepuestaA').hide();
				$('#rowRepuestaB').hide();
				$('#rowRepuestaC').hide();
				$('#rowRepuestaD').hide();	
				idRespuestaA=0;	
				idRespuestaB=0;
				idRespuestaC=0;
				idRespuestaD=0;
				
				$.ajax({
					url: "campusControllerSiguientePregunta.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect, 
						clasificacionSelect: clasificacionSelect, bloqueSelect: bloqueSelect,
						examenSelect: examenSelect,
						nivelSelect: nivelSelect, idPreguntaClasificacion: "0", percentErrorFilter: percentErrorFilter, 
						textFilter: textFilter, anioOfertaFilter: anioOfertaFilter, sinRespuestasFilter: sinRespuestasFilter, favoritasFilter: favoritasFilter,
						clasifPlusFilter: clasifPlusFilter, bugFilter: bugFilter, noExisteRespuestaFilter: noExisteRespuestaFilter},
					success: function(dataresponse, statustext, response){
						
						acierto = 99;
						sinContestarSession = false;
						
						var data = JSON.parse(dataresponse);						
						$("#statusConsole").html("<p>" + data.metadatos + "</p>");
						$("#estadisticasContainer").show();

						console.log(data.sql);

						<?php			
							session_start();
							if($_SESSION["session_username"] == "jillansa") {
						?>	
							$("#preguntaTexto").val(data.texto);
							
							if (data.respuestaA != null){
								$('#areaRespuestaA').val(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').val(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').val(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').val(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php } else { ?>					
							$('#preguntaTexto').text(data.texto);

							if (data.respuestaA != null){
								$('#areaRespuestaA').text(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').text(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').text(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').text(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php
							}
						?>

						idPregunta = data.idPregunta;
						idPreguntaClasificacion = data.idPreguntaClasificacion;
						$('#idPregunta').text("ID: " + data.idPregunta);
						$('#temaPregunta').text("TEMA: " + data.tema);						
						$('#temaSelectPregunta').val(data.idClasificacion);						
						$('#examenPregunta').text("EXAMEN: " + data.examen);
						$('#fechaExamenPregunta').text("FECHA EXAMEN: " + data.fechaExamen);						
						$('#modalidadExamenPregunta').text("MODALIDAD: " + data.modalidad);
						$('#ofertaExamenPregunta').text("CONVOCATORIA: " + data.oferta);						
						$('#estadisticasCursoOfertaText').text(data.cuerpo);
						$('#estadisticasTemaText').text(data.tema);

						if (data.link){			
							$('#pop').show();
							$('#imageresource').attr('src',data.link);
						} else {
							$('#pop').hide();	
							$('#imageresource').attr('src',data.link);
						}

						$('#numeroPregunta').text("Quedan " + data.numTotalPreguntas + " preguntas   ");

						if (data.numTotalPreguntas <= 0) {
							$('#siguienteBtn').prop("disabled",true);
						} else {
							$('#siguienteBtn').prop("disabled",false);
						}
						
						if (data.numTotalPreguntas >= 0 ) {
							$("#preguntaContainer").show();
							$("#preguntaContainerNoData").hide();
						} else {
							$("#preguntaContainer").hide();
							$("#preguntaContainerNoData").show();	
						}
						
						respuestaCorrecta = data.respuestaCorrecta;						
												
						if (data.favorita > 0) {
							$('#favoritaOnToOffBtn').show();	
							$('#favoritaOffToOnBtn').hide();
						} else {
							$('#favoritaOffToOnBtn').show();
							$('#favoritaOnToOffBtn').hide();	
						}

						updateGraphic(data);

						$('.js-example-basic-multiple').select2();
						
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});
			});
		});

		$(function () {
			$("#cargarUltimaSesionBtn").click(function(evt){
				evt.preventDefault();

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				
				if (cursoCuerpoSelect == "") {
					alert("El campo 'Cuerpo' es obligatorio para iniciar la sesion de tests");
					return;
				}
				
				$.ajax({
					url: "campusControllerSiguientePregunta.php",
					method: "POST",
					data: { recuperarSesion: 1},
					success: function(dataresponse, statustext, response){
						
						acierto = 99;
						sinContestarSession = false;
						
						var data = JSON.parse(dataresponse);						
						$("#statusConsole").html("<p>" + data.metadatos + "</p>");
						$("#estadisticasContainer").show();

						console.log(data.sql);

						/*
						POSICIONAR COMBOS y LISTAS ¿?
						*/
						/*
						var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
						var bloqueSelect = $("#bloqueSelect").val();
						var clasificacionSelect = $("#clasificacionSelect").val();
						var examenSelect = $("#examenSelect").val();
						var nivelSelect = $("#nivelSelect").val();
						var percentErrorFilter = $("#percentErrorFilter").val();
						var textFilter = $("#textFilter").val();
						var sinRespuestasFilter = $("#sinRespuestasFilter").prop('checked');
						var clasifPlusFilter = $("#clasifPlusFilter").prop('checked');	
						var bugFilter = $("#bugFilter").prop('checked');	
						var noExisteRespuestaFilter = $("#noExisteRespuestaFilter").prop('checked');
						var favoritasFilter = $("#favoritasFilter").prop('checked');*/

						// CARGAR PREGUNTAS
						<?php			
							session_start();
							if($_SESSION["session_username"] == "jillansa") {
						?>	
							$("#preguntaTexto").val(data.texto);
							
							if (data.respuestaA != null){
								$('#areaRespuestaA').val(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').val(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').val(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').val(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php } else { ?>												
							$('#preguntaTexto').text(data.texto);

							if (data.respuestaA != null){
								$('#areaRespuestaA').text(data.respuestaA);
								idRespuestaA = data.idRespuestaA;
								$('#rowRepuestaA').show();	
							}
							if (data.respuestaB != null){
								$('#areaRespuestaB').text(data.respuestaB);
								idRespuestaB = data.idRespuestaB;
								$('#rowRepuestaB').show();	
							}
							if (data.respuestaC != null){
								$('#areaRespuestaC').text(data.respuestaC);
								idRespuestaC = data.idRespuestaC;
								$('#rowRepuestaC').show();	
							}
							if (data.respuestaD != null){
								$('#areaRespuestaD').text(data.respuestaD);
								idRespuestaD = data.idRespuestaD;
								$('#rowRepuestaD').show();	
							}

						<?php
							}
						?>

						idPregunta = data.idPregunta;
						idPreguntaClasificacion = data.idPreguntaClasificacion;
						$('#idPregunta').text("ID: " + data.idPregunta);
						$('#temaPregunta').text("TEMA: " + data.tema);						
						$('#temaSelectPregunta').val(data.idClasificacion);						
						$('#examenPregunta').text("EXAMEN: " + data.examen);
						$('#fechaExamenPregunta').text("FECHA EXAMEN: " + data.fechaExamen);						
						$('#modalidadExamenPregunta').text("MODALIDAD: " + data.modalidad);
						$('#ofertaExamenPregunta').text("CONVOCATORIA: " + data.oferta);						
						$('#estadisticasCursoOfertaText').text(data.cuerpo);
						$('#estadisticasTemaText').text(data.tema);

						if (data.link){			
							$('#pop').show();
							$('#imageresource').attr('src',data.link);
						} else {
							$('#pop').hide();	
							$('#imageresource').attr('src',data.link);
						}

						$('#numeroPregunta').text("Quedan " + data.numTotalPreguntas + " preguntas   ");

						if (data.numTotalPreguntas <= 0) {
							$('#siguienteBtn').prop("disabled",true);
						} else {
							$('#siguienteBtn').prop("disabled",false);
						}
						
						if (data.numTotalPreguntas >= 0 ) {
							$("#preguntaContainer").show();
							$("#preguntaContainerNoData").hide();
						} else {
							$("#preguntaContainer").hide();
							$("#preguntaContainerNoData").show();	
						}
						
						respuestaCorrecta = data.respuestaCorrecta;						
												
						if (data.favorita > 0) {
							$('#favoritaOnToOffBtn').show();	
							$('#favoritaOffToOnBtn').hide();
						} else {
							$('#favoritaOffToOnBtn').show();
							$('#favoritaOnToOffBtn').hide();	
						}

						updateGraphic(data);

						$('.js-example-basic-multiple').select2();
						
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});
			});
		});


		
		$(function () { /* DOM ready */
			$("#notasUsuario").change(function() {		

				var notasUsuario = $("#notasUsuario").val();

				$.ajax({
					url: "campusControllerNotasUsuario.php",
					method: "POST",
					data: { notasUsuario: notasUsuario},
					success: function(dataresponse, statustext, response){
											
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});
			
			});
		});

		
		function comprobarRespuestaBtn(flagComprobar){

			// COMPROBAR
			if (flagComprobar == 1) {
				//var respuesta = document.getElementById('respuesta').value;
				var respuesta = document.querySelector('input[name="respuesta"]:checked').value;
				var idUsuario = <?php echo json_encode($_SESSION["session_id_username"]); ?>;
				
				if (respuestaCorrecta == ''){
					alert("No hay ninguna respuesta correcta");
					return;	
				}
				// si hay respuesta 
				if (respuesta != '') {

					if (respuestaCorrecta == respuesta) {
						acierto = 1;
						$.ajax({
							url: "campusControllerInsertarRespuestaUsuario.php",
							method: "POST",
							data: { idPregunta: idPregunta, idUsuario: idUsuario, acierto: acierto, respuestaCorrecta: respuestaCorrecta, respuesta: respuesta}, // 1=true , 0=false 
							success: function(dataresponse, statustext, response){
								alert("Respuesta correcta");
							},
							error: function(request, errorcode, errortext){
								$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
							}
						});						

					} else {
						acierto = 0;
						$.ajax({
							url: "campusControllerInsertarRespuestaUsuario.php",
							method: "POST",
							data: { idPregunta: idPregunta, idUsuario: idUsuario, acierto: acierto, respuestaCorrecta: respuestaCorrecta, respuesta: respuesta}, // 1=true , 0=false 
							success: function(dataresponse, statustext, response){
								alert("Respuesta Incorrecta");
							},
							error: function(request, errorcode, errortext){
								$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
							}
						});					

					}	

				} 
				

			}	

			// SIGUIENTE y no ha habido ya ninguna respuesta
			if (flagComprobar == 0 && acierto == 99) {
				//var acierto = 99;
				var respuesta = 0;
					$.ajax({
						url: "campusControllerInsertarRespuestaUsuario.php",
						method: "POST",
						data: { idPregunta: idPregunta, idUsuario: idUsuario, acierto: acierto, respuestaCorrecta: respuestaCorrecta, respuesta: respuesta}, // 1=true , 0=false 
						success: function(dataresponse, statustext, response){
							//alert("Respuesta Incorrecta");
						},
						error: function(request, errorcode, errortext){
							$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
						}
					});				

			}
			}

			$(function () {
			$("#comprobarRespuestaBtn").click(function(evt){
				evt.preventDefault();
				sinContestarSession = true;
				
				comprobarRespuestaBtn(1);
				

			});
			});


		$(function () {
			$("#actualizarPreguntaBtn").click(function(evt){
				evt.preventDefault();
				
				var preguntaTxt = $('#preguntaTexto').val();
				var respuestaTxtA = $('#areaRespuestaA').val();
				var respuestaTxtB = $('#areaRespuestaB').val();
				var respuestaTxtC = $('#areaRespuestaC').val();
				var respuestaTxtD = $('#areaRespuestaD').val();
				var temaSelectPregunta = $("#temaSelectPregunta").val();

				var respuestaCorrecta = '0';
				if (document.querySelector('input[name="respuesta"]:checked')!= null) {
					respuestaCorrecta = document.querySelector('input[name="respuesta"]:checked').value;
				}
				$.ajax({
					url: "campusControllerActualizarPregunta.php",
					method: "POST",
					data: { idPregunta: idPregunta, preguntaTxt: preguntaTxt, 
						respuestaTxtA: respuestaTxtA, idRespuestaA: idRespuestaA,
						respuestaTxtB: respuestaTxtB, idRespuestaB: idRespuestaB,
						respuestaTxtC: respuestaTxtC, idRespuestaC: idRespuestaC,
						respuestaTxtD: respuestaTxtD, idRespuestaD: idRespuestaD,
						respuestaCorrecta: respuestaCorrecta,
						temaSelectPregunta: temaSelectPregunta.toString()
						}, // 1=true , 0=false 
					success: function(dataresponse, statustext, response){
						alert("Respuesta actualizada");
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
					}
				});	
			});
		});

		$(document).ready(function() {
			$('.js-example-basic-multiple').select2();
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
			
			<h3>BUSQUEDA:</h3>
			<form class="" action="" method="get">
				<!--<div class="container-fluid">	-->	
				<!--<div class="row">-->

				<div class="buscadorPanel col-sm-12">

					<br>

					<div class="row">
						<div class="col-sm-3">	
																			
							<label for="cursoCuerpoSelect">Curso (*):</label><br>
							<!-- Se deben mostrar solo los cursos para los que el usuario esta matriculado (activo) -->						
							<select name="cursoCuerpoSelect" id="cursoCuerpoSelect" style="width: 80%;">
								<option value=""></option>
								<?php 
								while ($row = mysqli_fetch_array($resultCuerpo))
								{
									echo "<option value=".$row['id'].">".$row['descripcion']." | ".$row['nivel']."</option>";
								}
								?>        
							</select>
						</div>


						<div class="col-sm-3">					
							<!-- Se deben mostrar el listado de temas de curso indicado -->
							<label for="bloqueSelect">Bloque:</label><br>	
							<select class="selectGroup" name="bloqueSelect" id="bloqueSelect" style="width: 80%;" disabled>
								<option value=""></option>  
							</select>					
						</div>

						<div class="col-sm-3">					
							<!-- Se deben mostrar el listado de temas de curso indicado -->
							<label for="clasificacionSelect">Tema:</label><br>	
							<select class="selectGroup" name="clasificacionSelect" id="clasificacionSelect" style="width: 80%;" disabled>
								<option value=""></option>  
							</select>					
						</div>
					
					</div>

					<div class="row">	
						<div class="col-sm-3">
							<!--<div class="form-group mb-5">-->
								<label for="textFilter">Id/Texto a buscar:</label><br>
								<input id="textFilter" type="text" class="" placeholder="Search text" name="textFilter" style="width: 80%;">										
							<!--</div>-->
						</div>
						<div class="col-sm-3">
								<label for="anioOfertaFilter">Fec. Examen [ YYYY >= ]:</label><br>
								<input id="anioOfertaFilter" type="text" class="" placeholder="Search Year Filter" name="anioOfertaFilter" style="width: 80%;">										
							
						</div>		

						<div class="col-sm-6">					
							<!-- Se deben mostrar el listado de temas de curso indicado -->
							<label for="examenSelect">Examenes del curso:</label><br>	
							<select class="selectGroup" name="examenSelect" id="examenSelect" style="width: 80%;" disabled>
								<option value=""></option>  
							</select>					
						</div>
						
					</div>

					<div class="row">	

						<div class="col-sm-3">
							<!--<div class="row">-->
							<!--<div class="form-group mb-5">-->
								<label for="percentErrorFilter">Ratio error [errores/intentos] (%):</label><br>
								<input id="percentErrorFilter" type="text" class="" placeholder="50" name="percentErrorFilter" style="width: 80%;">										
							<!--</div>-->
						</div>

						<div class="col-sm-2">	
							<label class="labelLogin" for="exclusivoFilter">Modo Exclusivo:</label><br>									
							<input class="" type="checkbox" value="" name="exclusivoFilter" id="exclusivoFilter" style="width: 80%;">
						</div>
						
						<div class="col-sm-2">			
							<!--<div class="form-group mb-2">-->
								<!--<div class="form-check">-->
									<label class="labelLogin" for="sinRespuestasFilter">Sin responder:</label><br>
									<input class="" type="checkbox" value="" name="sinRespuestasFilter" id="sinRespuestasFilter" style="width: 80%;">
								<!--</div>-->
							<!--</div>-->
						</div>

						<div class="col-sm-2">			
							<!--<div class="form-group mb-2">-->
								<!--<div class="form-check">-->
									<label class="labelLogin" for="favoritasFilter">(<i class="fa fa-star"></i>) Favoritas:</label><br>									
									<input class="" type="checkbox" value="" name="favoritasFilter" id="favoritasFilter" style="width: 80%;">
								<!--</div>-->
							<!--</div>-->
						</div>
						
						<?php			
							session_start();
							if($_SESSION["session_username"] == "jillansa") {
						?>	
							<div class="col-sm-1">			
								<!--<div class="form-group mb-2">-->
									<!--<div class="form-check">-->
										<label class="labelLogin" for="clasifPlusFilter">Clasif(+):</label><br>									
										<input class="" type="checkbox" value="" name="clasifPlusFilter" id="clasifPlusFilter" style="width: 80%;">
										<label class="labelLogin" for="CharFilter">Caracteres Extraños:</label><br>									
										<input class="" type="checkbox" value="" name="CharFilter" id="CharFilter" style="width: 80%;">
										<label class="labelLogin" for="bugFilter">(<i class="fa fa-bug"></i>) Debug:</label><br>									
										<input class="" type="checkbox" value="" name="bugFilter" id="bugFilter" style="width: 80%;">
										<label class="labelLogin" for="noExisteRespuestaFilter">No existe Respuesta Correcta:</label><br>									
										<input class="" type="checkbox" value="" name="noExisteRespuestaFilter" id="noExisteRespuestaFilter" style="width: 80%;">
									<!--</div>-->
								<!--</div>-->
							</div>				
						<?php } ?>
					</div>

					<br>

					<div class="">		
						<button id="buscarBtn" type="submit" class="btn btn-primary pull-right" aria-label="Left Align">
							<i class="fa fa-search" aria-hidden="true"></i>
							Buscar
						</button>

						<button id="listadoBtn" 
							formaction="campusControllerListar.php"
							formtarget="_blank"
							type="submit" 
							class="btn btn-secondary" 
							aria-label="Right Align">				
								<i class="fa fa-print" aria-hidden="true"></i>
								Generar Listado
						</button>

						<button id="cargarUltimaSesionBtn" 
							type="submit" 
							class="btn btn-secondary" 
							aria-label="Right Align">				
							<i class="fa fa-download" aria-hidden="true"></i>
								Cargar Ult. Sesion
						</button>

					</div>
					<br>
				</div>
				
				<!--<div class="row">	-->									
				<div class="col-sm-12" id="panelPreguntas">
					<div class="row">
						<div class="col-sm-9">						
						<!--<div class="panel-group">-->
						<!--	<div class="panel panel-default">-->
						<!--		<div class="panel-heading">		-->	
								
									<div class="" id="preguntaContainer" style="display:none">	

										<script>

											function fnFavoritaBtn(bFavorita){													
														
												var idUsuario = <?php echo json_encode($_SESSION["session_id_username"]); ?>;
												$.ajax({
														url: "campusControllerFavoritaPregunta.php",
														method: "POST",
														data: { idPregunta: idPregunta, 
															idUsuario: idUsuario,
															bFavorita: bFavorita
														},
														success: function(dataresponse, statustext, response){
															
															if (dataresponse > 0) {
																$('#favoritaOnToOffBtn').show();	
																$('#favoritaOffToOnBtn').hide();
															} else {
																$('#favoritaOffToOnBtn').show();
																$('#favoritaOnToOffBtn').hide();	
															}
																															
														},
														error: function(request, errorcode, errortext){
															$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
														}
													});

													return;
												}


												function fnDudaErrorBtn(){													
														
														var idUsuario = <?php echo json_encode($_SESSION["session_id_username"]); ?>;
														$.ajax({
																url: "campusControllerDudaErrorPregunta.php",
																method: "POST",
																data: { idPregunta: idPregunta, 
																	idUsuario: idUsuario
																},
																success: function(dataresponse, statustext, response){
																							
																},
																error: function(request, errorcode, errortext){
																	$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
																}
															});
		
															return;
														}		
														
														function fnPreguntaCorreoBtn(){													
														
														var idUsuario = <?php echo json_encode($_SESSION["session_id_username"]); ?>;

														idPregunta = $('#idPregunta').text();
														
														examenPregunta = $('#examenPregunta').text();
														fechaExamenPregunta = $('#fechaExamenPregunta').text();
														modalidadExamenPregunta = $('#modalidadExamenPregunta').text();
														ofertaExamenPregunta = $('#ofertaExamenPregunta').text();

														<?php			
															session_start();
															if($_SESSION["session_username"] == "jillansa") {
														?>	
															// al ser HTML input lo cogemos de val()
															temaPregunta = "CLASIFICACION: " + $('select[name=temaSelectPregunta] option:selected').text();															
															preguntaTexto = $('#preguntaTexto').val();
															respuestaTxtA = $('#areaRespuestaA').val();
															respuestaTxtB = $('#areaRespuestaB').val();
															respuestaTxtC = $('#areaRespuestaC').val();
															respuestaTxtD = $('#areaRespuestaD').val();					
														
														<?php			
															} else {
														?>	
															// al ser HTML div lo cogemos de text()
															temaPregunta = $('#temaPregunta').text();
															preguntaTexto = $('#preguntaTexto').text();
															respuestaTxtA = $('#areaRespuestaA').text();
															respuestaTxtB = $('#areaRespuestaB').text();
															respuestaTxtC = $('#areaRespuestaC').text();
															respuestaTxtD = $('#areaRespuestaD').text();		
														<?php			
															}
														?>	

														estadisticaAciertosPregunta = $('#estadisticasPregunta1').text();
														estadisticaErroresPregunta = $('#estadisticasPregunta2').text();

														//alert("ENVIO DE CORREO:" + idPregunta + " / " + temaPregunta + " / " + examenPregunta + " / " + fechaExamenPregunta + " / " + modalidadExamenPregunta + " / " + ofertaExamenPregunta + " / " + preguntaTexto + " / " + respuestaTxtA + " / " + respuestaTxtB + " / " + respuestaTxtC + " / " + respuestaTxtD + " / " + estadisticaAciertosPregunta + " / " + estadisticaErroresPregunta);
														
														//return;

														$.ajax({
																url: "campusControllerCorreoPregunta.php",
																method: "POST",
																data: { idPregunta: idPregunta, 
																	idUsuario: idUsuario,
																	examenPregunta: examenPregunta,
																	fechaExamenPregunta: fechaExamenPregunta,
																	modalidadExamenPregunta: modalidadExamenPregunta,
																	ofertaExamenPregunta: ofertaExamenPregunta, 
																	temaPregunta: temaPregunta, 
																	preguntaTexto: preguntaTexto,
																	respuestaTxtA: respuestaTxtA,
																	respuestaTxtB: respuestaTxtB, 
																	respuestaTxtC: respuestaTxtC, 
																	respuestaTxtD: respuestaTxtD,
																	estadisticaAciertosPregunta: estadisticaAciertosPregunta, 
																	estadisticaErroresPregunta: estadisticaErroresPregunta
																},
																success: function(dataresponse, statustext, response){
																							
																},
																error: function(request, errorcode, errortext){
																	$("#statusConsole").html("<p>Ocurrió un error en la consulta de datos</p>");
																}
															});
		
															return;
														}		
													
										</script>				
														
																		
										<div class="form-group shadow-textarea">
							
											<div class="row">
												<div class="col-sm-3">												
													<h3>PREGUNTA:</h3>									
												</div>
												
												<div class="col-sm-3"><h5><div id="estadisticasPregunta1">Num. ACIERTOS: Sin datos para mostrar</div></h5></div>
												<div class="col-sm-3"><h5><div id="estadisticasPregunta2">Num. ERRORES: Sin datos para mostrar</div></h5></div>
													
												<div class="col-sm-1">
													<button id="favoritaOnToOffBtn" style="display:none"
														title="Marcar como favorita"
														type="button" onclick="fnFavoritaBtn('N');return false;"
														class="btn btn-primary float-right">
														<i class="fa fa-star"></i>
													</button>
													<button id="favoritaOffToOnBtn" style="display:none"
														title="Marcar como favorita"
														type="button" onclick="fnFavoritaBtn('S');return false;"
														class="btn btn-outline-primary float-right">												
														<i class="fa fa-star"></i>
													</button>
												</div>	
												<div class="col-sm-1">
													<button id="dudaErrorBtn"
														title="Reportar DUDA/ERROR en la pregunta"
														type="button" onclick="fnDudaErrorBtn();return false;"
														class="btn btn-outline-primary float-right">	
														<i class="fa fa-bug" aria-hidden="true"></i>
													</button>														
												</div>	
												<div class="col-sm-1">
													<button id="preguntaCorreoBtn"
														title="Enviar pregunta por correo"
														type="button" onclick="fnPreguntaCorreoBtn();return false;"
														class="btn btn-outline-primary float-right">	
														<i class="fa fa-envelope" aria-hidden="true"></i>
													</button>														
												</div>	
											</div>	
												

											<div class="row">
																								
												<div class="col-sm-1 infoPregunta" id="idPregunta"></div>
												<?php			
													session_start();
													if($_SESSION["session_username"] == "jillansa") {
												?>	
													<div class="col-sm-3 infoPregunta">
														<label for="temaSelectPregunta">TEMA:</label><br>	
														<select class="js-example-basic-multiple" multiple="multiple"
														    name="temaSelectPregunta" id="temaSelectPregunta" style="width: 100%;">																																						      
														</select>			
													</div>	
												
												<?php } else { ?>
												
													<div class="col-sm-2 infoPregunta" id="temaPregunta"></div>							

												<?php
													}
												?>

												<div class="col-sm-2 infoPregunta" id="examenPregunta"></div>					
												<div class="col-sm-2 infoPregunta" id="fechaExamenPregunta"></div>
												<div class="col-sm-2 infoPregunta" id="modalidadExamenPregunta"></div>			
												<div class="col-sm-2 infoPregunta" id="ofertaExamenPregunta"></div>
														
											</div>

											<br>
												
											<div class="row">
												<div class="col-sm-9">
													
													<?php			
														session_start();
														if($_SESSION["session_username"] == "jillansa") {
													?>	
														<textarea class="form-control z-depth-1" name="preguntaTexto" id="preguntaTexto" rows="4"></textarea>
												
													<?php } else { ?>
												
														<div name="preguntaTexto" id="preguntaTexto" class="divPreWrap">></div>
												
													<?php
														}
													?>								
												
												</div>
												<div class="col-sm-3">
													
													<script type="text/javascript">
														$(function () { /* DOM ready */
															$("#pop").click(function(evt){
																evt.preventDefault();
																$('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
																$('#imagepreviewlink').attr('href', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
																$('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
															});
														});
													</script>

													<a href="#" id="pop" style="display:none">
														<img id="imageresource" src="" 
														style="max-width: 100%; max-height: 100%; height: auto; width: auto;">
														Click para agrandar
													</a>
												
												</div>												
											</div>
										</div>

											<!-- Creates the bootstrap modal where the image will appear -->
											<div class="modal fade" id="imagemodal" tabindex="-1" 
												role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<h4 class="modal-title" id="myModalLabel">Image preview</h4>
															<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
														</div>
														<div class="modal-body">
															<a href="" id="imagepreviewlink" target="_blank">
																<img src="" id="imagepreview" style="max-width: 100%; max-height: 100%;" >
															</a>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
										
										<br>
										
										<div class="">
											<h3>RESPUESTAS:</h3><br>
											
											<table class="table table-hover">
												
												<tr id="rowRepuestaA" style="display:none">
													<td><input id="repuestaA" type="radio" name="respuesta" value="A"></td>
													<td>													
													<?php			
														session_start();
														if($_SESSION["session_username"] == "jillansa") {
													?>	
														<textarea class="form-control z-depth-1" name="areaRespuestaA" id="areaRespuestaA" rows="4"></textarea>
													<?php } else { ?>
												
														<div name="areaRespuestaA" id="areaRespuestaA" class="divPreWrap">></div>
												
													<?php
														}
													?>	
													</td>
												</tr>
												<tr id="rowRepuestaB" style="display:none">
													<td><input id="repuestaB" type="radio" name="respuesta" value="B"></td>
													<td>
													<?php			
														session_start();
														if($_SESSION["session_username"] == "jillansa") {
													?>	
														<textarea class="form-control z-depth-1" name="areaRespuestaB" id="areaRespuestaB" rows="4"></textarea>
													<?php } else { ?>
												
														<div name="areaRespuestaB" id="areaRespuestaB" class="divPreWrap">></div>
												
													<?php
														}
													?>														
													</td>
												</tr>
												<tr id="rowRepuestaC" style="display:none">
													<td><input id="repuestaC" type="radio" name="respuesta" value="C"></td>
													<td>
													<?php			
														session_start();
														if($_SESSION["session_username"] == "jillansa") {
													?>	
														<textarea class="form-control z-depth-1" name="areaRespuestaC" id="areaRespuestaC" rows="4"></textarea>
													<?php } else { ?>
												
														<div name="areaRespuestaC" id="areaRespuestaC" class="divPreWrap">></div>
												
													<?php
														}
													?>	
													</td>
												</tr>
												<tr id="rowRepuestaD" style="display:none"> 
													<td><input id="repuestaD" type="radio" name="respuesta" value="D"></td>
													<td>
													<?php			
														session_start();
														if($_SESSION["session_username"] == "jillansa") {
													?>	
														<textarea class="form-control z-depth-1" name="areaRespuestaD" id="areaRespuestaD" rows="4"></textarea>
													<?php } else { ?>
												
														<div name="areaRespuestaD" id="areaRespuestaD" class="divPreWrap">></div>
												
													<?php
														}
													?>	
													</td>													
												</tr>

											</table>
										</div>

										<br><br>
										
										<div id="numeroPregunta" class="pull-right"></div>
										
										<br><br>

										<div class="">												
										
											<button id="siguienteBtn" type="submit" class="btn btn-primary pull-right" aria-label="Left Align">
												<i class="fa fa-arrow-right" aria-hidden="true"></i>
												Siguiente
											</button>

											<button id="comprobarRespuestaBtn" type="button" class="btn btn-secondary pull-right" aria-label="Left Align">				
												<i class="fa fa-flash" aria-hidden="true"></i>
												Comprobar				
											</button>	

											<?php			
												session_start();
												if($_SESSION["session_username"] == "jillansa") {
											?>	
												<button id="actualizarPreguntaBtn" type="button" class="btn btn-secondary pull-right" aria-label="Left Align">				
													<i class="fa fa-download" aria-hidden="true"></i>			
													Actualizar	
												</button>	
											<?php
												}
											?>

										</div>	
																				
									</div>
									<div class="mensajeCentral" id="preguntaContainerNoData" style="display:none">	
									
										Ninguna pregunta cumple los requisitos
								
									</div>
									
						<!--		</div>-->
						<!--	</div>-->
						<!--</div>-->
						</div>

						<div id="estadisticasContainer" class="col-sm-3" style="display:none"> 
							<div class="infoEstadisticasPanel">			
								
								<div class="container">
									<div class="row">
										<div class="col text-center">

											<h3>ESTADISTICAS:</h3>
											<br>
											
											<b><div>NOTAS DE USUARIO:</div></b>
											<textarea class="form-control z-depth-1" name="notasUsuario" id="notasUsuario" rows="10"> <?= $_SESSION["notasUsuario"] ?> </textarea>
													
											<br><hr><br>
											
											<b><div id="estadisticasSession">SESION:</div></b>
											<div id="estadisticasSession2">Sin datos para mostrar</div>
											<canvas id="myChart4" style="width:100%;max-width:600px"></canvas>
											
											<br>	

											<div class="container">
												<div class="row">
													<div class="col text-center">
														<button id="listarErroresSesionBtn" 
															formaction="campusControllerListarErroresSession.php"
															formtarget="_blank"
															type="submit" 
															class="btn btn-secondary" 
															aria-label="Left Align">				
																<i class="fa fa-download" aria-hidden="true"></i>			
																Listar Errores Sesion	
														</button>
													</div>
												</div>
											</div>

											<br><hr><br>	

											<b><div id="estadisticasTema">TEMA:</div></b>
											<div id="estadisticasTemaText"></div>
											<div id="estadisticasTema2">Sin datos para mostrar</div>
											<canvas id="myChart2" style="width:100%;max-width:600px"></canvas>

											<br><br>
										</div>
									</div>
								</div>
							</div>
						</div>		
					</div>	
				</div>
			</form>
		</div>
	</div>
	<div class="container-fluid" style="display:none">
		<div class="col-sm-12">
			<div class="" id="statusConsole">				
			</div>
		</div>
	</div>
	
	<?php include 'campusFooter.php';?>

  </body>
</html>