<?php
// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;

?>

<!DOCTYPE html>

<html lang="esp">

	<head>
    	<meta charset="utf-8" />
    	<title>Registro</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h3>Formulario de registro</h3>
    	<form name="userReg" action="session_register.php" method="post">
        	<?php if($err==1){
				echo "Usuario o Contraseña Erróneos <br />";
			}
            if($err==2){
                echo "El usuario ya existe, favor intente con otro. <br />";
            }
			?><br>
        	<label>User name</label><br />
            <input type="text" name="usern" id="usern" maxlength="15" /><br />
            <label>Password</label><br />
            <input type="password" name="passwd" id="passwd" maxlength="10" /><br />
            <input type="submit" name="register" id="register" value="Registrarse" /><br /><br />   
            <input type="submit" name="regBack" id="back" value="Volver" />
        </form>
    </body>
    
</html>