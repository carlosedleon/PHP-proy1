
<?php

error_reporting(E_ALL ^ E_DEPRECATED);

//llamado a la clase que hace la conexcion a la base de datos
//require'class/config.php'; // No se requiere
//LLamado a la clase Usuarios para realizar el inicio de sesion
require'class/users.php';
//llamado a la clase que ejecutará los queries de Consulta, Adición y Eliminación
//require'class/dbactions.php';
//llamado a la clase encargada de las sesiones
require'class/sessions.php';

//creación o instanciamiento de un objeto de la Clase Connection
//$objConn = new Connection(); config.php
//objeto de la clase users
$objUser = new Users();

//llamamos la funcion que nos conecta a la base de datos
//$objConn->get_connected(); config.php
//function que realiza la verificación de usuarios e inicio de sesion
$objUser->login_in();

if(isset($_POST['register'])){
	header('Location: register.php');
	$objUser->destroy();
}

?>