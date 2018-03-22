<?php
// Evitar los warnings the variables no definidas!!!
$err = isset($_GET['error']) ? $_GET['error'] : null ;

?>

<!DOCTYPE html>
<html lang="esp">
<head>
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h3>Formulario de login</h3>
    <form name="user" action="session_init.php" method="post">
        <?php if($err==1){
            echo "<label id='alerta'>Usuario o Contraseña Erróneos.</label> <br />";
        }
        if($err==2){
            echo "<label id='alerta'>Debe iniciar sesion para poder acceder el sitio.</label> <br />";
        }
        ?><br />
        <label>User name</label><br />
        <input type="text" name="usern" id="usern" maxlength="15" /><br />
        <label>Password</label><br />
        <input type="password" name="passwd" id="passwd" maxlength="10" /><br />
        <input type="submit" name="enter" id="enter" value="Entrar" /><br /><br /> 
    </form>
    <form name="userReg" action="register.php" method="post">
        <input type="submit" name="register" id="register" value="Registrar" />       
    </form>
</body>

</html>