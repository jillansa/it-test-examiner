<meta charset="UTF-8">

<?php
error_reporting(E_ERROR);

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
		
		$(function () { /* DOM ready */
			$("#cursoCuerpoSelect").change(function() {		

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();
				$("#temarioSelect").val([]);
				
				$.ajax({
					url: "campusControllerClasificacionCuerpo.php",
					method: "POST",
					data: { cursoCuerpoSelect: cursoCuerpoSelect, mode: "CONSULTA"},
					success: function(dataresponse, statustext, response){						
						cargarTemario(dataresponse);
					},
					error: function(request, errorcode, errortext){
						$("#statusConsole").html("<p>Ocurrió un error en la actualización de datos</p>");
						$("#temarioSelect").val([]);
					}
				});

			});
		});

		function cargarTemario(dataresponse){
			$("#temarioSelect").val([]);

			var data = JSON.parse(dataresponse);
			var select_option = '';

			for (index = 0; index < data.length; ++index) {
				option = data[index];
				//BLOQUE
				if (option.tipo == '1') {
					if (option.asignado == 'S') {
						select_option += '<optgroup label="'+ option.tema +'" value="'+option.id+'">'+option.tema+'</optgroup>';
						select_option += '<option value="'+option.id+'" selected>(+) '+option.tema+'</option>';
					}
					else {
						select_option += '<optgroup label="'+ option.tema +'" value="'+option.id+'">'+option.tema+'</optgroup>';
						select_option += '<option value="'+option.id+'">(+) '+option.tema+'</option>';						
					}		
				//TEMA
				} else {	
					if (option.asignado == 'S') {
						select_option += '<option value="'+option.id+'" selected> - '+option.tema+'</option>';
					} else {
						select_option += '<option value="'+option.id+'"> - '+option.tema+'</option>';
					}
				}
			}

			$('#temarioSelect').html('').append(select_option);

			return;
		}
	

		$(function () {
			$("#guardarBtn").click(function(evt){
				evt.preventDefault();				

				var cursoCuerpoSelect = $("#cursoCuerpoSelect").val();				
				var selected = [];

				for (var option of document.getElementById('temarioSelect').options)
				{
					if (option.selected) {
						selected.push(option.value);
					}
				}
				
				$.ajax({
					url: "campusControllerClasificacionCuerpo.php",
					method: "POST",
					data: { 
						cursoCuerpoSelect: cursoCuerpoSelect, 
						selected: selected.toString(),						
						mode: "UPDATE"
					},
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
			
			<h3>CLASIFICACION TEMARIO-CURSO:</h3>
		
			<form class="" action="">
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
						<div class="col-md-3 offset-md-3">
								
							<label for="guardarBtn"></label><br>
							<button id="guardarBtn" type="submit" class="btn btn-primary pull-right" aria-label="Left Align">
								Guardar
							</button>
						</div>
					</div>

					<div class="row">						
						<div class="col-sm-8">					
						<label for="temarioSelect">Mi Clasificacion de Temas:</label><br>
						<select id="temarioSelect" multiple="multiple" size="30" style="width:100%;height: 100%;">
						</select>	
						</div>
					</div>

					<br>

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