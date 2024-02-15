 
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
    <div class="container-fuild">
        <div class="center">
            <div class="row">

                <div class="col-sm-6">
                    <h1>Registrar cuenta de usuario</h1>
                        
                    <form method="post" action="campusControllerRegisterUser.php">
                    
                        <div>
                            <label class="labelLogin" id="labelusername" for="username">USERNAME: </label>
                            <input id="username" name="register_username" type="text" required="true" placeholder="Username">                     
                        </div>
                            
                        <div>
                            <label class="labelLogin" id="labelpassword" for="password">PASSWORD: </label>
                            <input id="password" name="register_password" type="password" required="true" placeholder="Password">
                        </div>

                        <div>
                            <label class="labelLogin" id="labelpassword2" for="password2">PASSWORD: </label>
                            <input id="password2" name="register_password2" type="password" required="true" placeholder="Repite el password">
                            <div class="divError"><?php if (isset($_GET["errorlabelpassword2"])) { echo $_GET["errorlabelpassword2"];}?></div>
                        </div>

                        <div>
                            <label class="labelLogin" id="labelemail" for="email">E-MAIL: </label>
                            <input id="email" name="register_email" type="text" required="true" placeholder="E-mail">
                            <!--<br>Para disfrutar todos los servicios de la suscripcion, debe ser una cuenta gmail.-->
                        </div>

                        <div>
                            <label class="labelLogin" id="labelname" for="name">NOMBRE: </label>
                            <input id="name" name="register_name" type="text" required="true" placeholder="Nombre">
                        </div>

                        <div>
                            <label class="labelLogin" id="labelsurname" for="surname">APELLIDOS: </label>
                            <input id="surname" name="register_surname" type="text" required="true" placeholder="Apellidos">
                        </div>

                        <div>
                            <label class="labelLogin" id="labeldocument" for="document">DNI/NIE/NIF: </label>
                            <input id="document" name="register_document" type="text" required="true" placeholder="DNI/NIF/NIE">
                        </div>

                        <div>
                            <button type="submit">Registar</button>
                        </div> 
                        
                        <div class="divError"><?php echo $_GET["errorForm"];?></div>

                    </form>
                </div>

                <div class="col-sm-6">
                    <div>
                        
                        <h2>Acceso gratuito: 4 dias sin coste ni compromiso.</h2>

                    </div>
                    <div>
                        <img class="img-fluid" src="static/images/illustration-4-days-free-trial-4.jpg" alt="Campus FORMA TIC - Prueba de 4 dias gratuita">
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    El servicio de autoevalución por test de OpositandoBien.es tiene un coste de suscripcion de 6,05€/mes.
                </div>
                <div class="col-sm-12">
                        Una vez registrado, desde tu pagina de perfil podrás suscribirte al servicio para tener acceso ilimitado al examinador.
                </div>        
                <div class="col-sm-12">        VENTAJAS: </div>
                
                <div class="col-sm-12">
                    <ul>
                        
                        <li>Podras evaluar tu avance con mas de 5000 preguntas de examenes reales adaptadas y clasificadas por cada tema.</li>
                        <li>Hacer simulacros de examen con preguntas del temario de cada cuerpo.</li>
                        <li>Hacer tanda de preguntas filtrando por tema o palabras para consolidar conocimientos después de estudiar un tema.</li>
                        <li>Filtrar por la fecha de la oferta o examen, por la modalidad de la convocatoria.</li>
                        <li>Marcar preguntas para repasar y localizarlas.</li>
                        <li>Estadísticas personales de aciertos/errores/nsnc por pregunta, tema y sesión de test. comprobar tus estadisticas por temas, puntos flojos y puntos fuertes segun el temario del curso/oposicion.</li>
                        <li>Filtrar preguntas con cierto ratio de error.</li>
                        <li>Acceso y adaptable a PC, movil, tablet y desde cualquier lugar para repasar una preguntas del último tema.</li>
                        
                    </ul>                        
                </div>
            </div>

            <div class="row">
                <div>
                    LEY DE PROTECCIÓN DE DATOS
                    De acuerdo con lo dispuesto en la normativa vigente en materia de protección de datos personales:
                    Reglamento General de Protección de Datos (RGPD) y Ley Orgánica de Protección de Datos Personales y garantía de los derechos digitales.
                    Le informamos que al rellenar los datos de alta de nuevo usuario en el sistema y enviarlos, da su consentimiento a OpositandoBien.es para tratar sus datos personales con el único fin de tramitar su solicitud de alta de usuario y acceso al sistema de preparacion de oposiciones gestionado por OpostitandoBien.es. Sus datos no serán cedidos ni comunicados a terceros, salvo obligación legal.
                    Puede ejercitar los derechos de acceso, rectificación y supresión, así como los demás derechos recogidos en la normativa de protección de datos personales, mediante solicitud dirigida por escrito al delegado de protección de datos en el correo electrónico opositandobien@gmail.com, acompañando la petición de un documento que acredite su identidad.
                    Puede obtener más información sobre el tratamiento de sus datos personales puede ponerse en contacto en el mismo email.
                </div>
            </div>

        </div>     
        
        <div class="container-fluid">
    		<div class="col-sm-12">
                <div class="" id="statusConsole">
                    <br>
                </div>
            </div>
        </div>

    </div>    

	<?php include 'campusFooter.php';?>
    
</body>
</html>