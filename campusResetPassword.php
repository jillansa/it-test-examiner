 
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
                <h1>Restablecer tu contraseña:</h1>
                     
                <form method="post" action="campusControllerResetPassword.php">
                    
                    <div>
                        <label class="labelLogin" id="labelusername" for="username">USER: </label>
                        <input id="username" name="session_username" type="text" required="true" readonly value="<?php echo $_GET["username"];?>">                     
                    </div>
                        
                    <div>
                        <label class="labelLogin" id="labelpassword" for="password">PASSWORD: </label>
                        <input id="password" name="session_password" type="password" required="true" placeholder="Password">
                    </div>

                    <div>
                        <label class="" id="labelpassword2" for="password2">PASSWORD: </label>
                        <input id="password2" name="session_password2" type="password" required="true" placeholder="Repite el password">
                        <div class="divError"><?php echo $_GET["errorlabelpassword2"];?></div>
                    </div>

                    <input id="hash" name="hash" type="hidden" required="true" readonly value="<?php echo $_GET["hash"];?>">    

                    <br>

                    <div>
                        <button type="submit">Restablecer contraseña</button>
                    </div>   
                    
                    <div class="divError"><?php echo $_GET["errorForm"];?></div>
                    
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