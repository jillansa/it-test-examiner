 
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

            <div class="col-sm-6">
                <h1>Mail para restablecer tu contraseña:</h1>

                <br>
                     
                <form method="post" action="campusControllerLinkResetContraseña.php">
                
                    <div>
                        <label class="" id="labelusername" for="username">USER: </label>
                        <input id="username" name="session_username" type="text" required="true" placeholder="Username/email/dni">                     
                    </div>                        
                  
                    <br>

                    <div>
                        <button type="submit">Enviar mail</button>
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