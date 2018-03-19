<?php
// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;

?>

<!DOCTYPE html>

<html lang="esp">

	<head>
    	<meta charset="utf-8" />
    	<title>Session Form - Registro</title>
    </head>
    <body>
    
    	<form name="userReg" action="session_register.php" method="post">
        	<?php if($err==1){
				echo "Usuario o Contraseña Erróneos <br />";
			}
			if($err==2){
				echo "Debe registrar sesion para poder acceder el sitio. <br />";
			}
			?>
        	<label>User name</label><br />
            <input type="text" name="usern" id="usern" maxlength="15" /><br />
            <label>Password</label><br />
            <input type="password" name="passwd" id="passwd" maxlength="10" /><br />
            <input type="submit" name="register" id="register" value="Registrarse" /><br /><br />   
            <input type="submit" name="regBack" id="back" value="Volver" />
        </form>
        <!--<form name="userRegBack" action="session_register.php" method="post">
            <input type="submit" name="back" id="back" value="Volver" />
        </form>-->
    </body>
    
</html>