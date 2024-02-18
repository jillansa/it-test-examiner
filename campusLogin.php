 
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

    <div class="container-fuild">
        <div class="center">

            <div class="col-sm-6">
                <h1>Iniciar sesión</h1>
                     
                <form method="post" action="campusControllerLoginUser.php">
                
                    <div>
                        <label class="labelLogin" id="labelusername" for="username">USER: </label>
                        <input id="username" name="session_username" type="text" required="true" placeholder="Username/email/dni">                     
                    </div>
                        
                    <div>
                        <label class="labelLogin" id="labelpassword" for="password">PASSWORD: </label>
                        <input id="password" name="session_password" type="password" required="true" placeholder="Password">
                    </div>
                    <br>
                    <a href="campusLinkResetPassword.php">¿Has olvidado tu contraseña?</a>
                    <br><br>
                    <div>
                        <button type="submit">Iniciar sesión</button>
                    </div>   
                    
                    <div class="divError"><?php if (isset($_GET["errorForm"])) {echo $_GET["errorForm"];}?></div>
                    
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

    </div> 
    
	<?php include 'campusFooter.php';?>

    
</body>
</html>