<?php
error_reporting(E_ALL ^ E_DEPRECATED);

function consultaUsuarios($user, $pass)
{
	
    $filename = "./usuarios/usuarios.txt";
    $handle2 = file($filename);
    print $user.$pass;
    while(list($var, $val) = each($handle2)){ // var =0, val = test.txt|68
        //print "$var: $val <br/>"; // test.txt|68
        $val= explode('|', trim($val));
	    if ($val[0]==$user && $val[1] == $pass) {
	    	return true;
	    }
    }
    //closedir($handle2);
    return false;
}

class Users{
	
	public $objDb;
	public $objSe;
	public $result;
	public $rows;
	public $useropc;
	
	public function __construct(){
		
		//$this->objDb = new Database();
		$this->objSe = new Sessions();
		
	}
	
	public function login_in(){
		
		//$query = "SELECT * FROM users, profiles WHERE users.loginUsers = '".$_POST["usern"]."' 
		//	AND users.passUsers = '".$_POST["passwd"]."' AND users.idprofile = profiles.idProfile ";
		//$this->result = $this->objDb->select($query);
		//$this->rows = mysql_num_rows($this->result);
		$this->objSe->init();
		//$this->objSe->set('user', 'Carlos');
		
		$this->objSe->set('user', $_POST["usern"]);
		//print $_POST['usern'];
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;
		//$_SESSION['user']
		//if($this->rows > 0){
		if($user!=''){
			//if($row=mysql_fetch_array($this->result)){
				
				//$this->objSe->init();
				//$this->objSe->set('user', $row["loginUsers"]);
				//$this->objSe->set('iduser', $row["idUsers"]);
				//$this->objSe->set('idprofile', $row["idprofile"]);
				if(consultaUsuarios($_POST["usern"], $_POST["passwd"])){
					if (file_exists("./user/".$user)) {
						header('Location: user/index.php');
						//print "Existe";
					}else{
						print "Lo sentimos pero no existe el folder";
					}
					
				}else{					
					header('Location: index.php');
				}
				//$this->useropc = $row["nameProfi"];
				//header('Location: user/index.php');
				/*switch($this->useropc){
					
					case 'Standard':
						header('Location: user/index.php');
						break;
						
					case 'Admin':
						header('Location: admin/index.php');
						break;
					
				}*/
				
			//}
			
		}else{
			
			header('Location: index.php?error=1');
			
		}
		
	}
	public function register_in(){
		
		//$query = "SELECT * FROM users, profiles WHERE users.loginUsers = '".$_POST["usern"]."' 
		//	AND users.passUsers = '".$_POST["passwd"]."' AND users.idprofile = profiles.idProfile ";
		//$this->result = $this->objDb->select($query);
		//$this->rows = mysql_num_rows($this->result);
		$this->objSe->init();

		$this->objSe->set('user', $_POST["usern"]);
		$password=$_POST["passwd"];
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;
		//$_SESSION['user']
		//if($this->rows > 0){
		if($user!=''){
			//if($row=mysql_fetch_array($this->result)){
			// if !existe 
			// verficar si existe el usuario antes de crearle una carpeta
			// crear una carpeta para el usuario en usuarios/'nombreUsuario' 
				//$this->objSe->init();
				//$this->objSe->set('user', $row["loginUsers"]);
				//$this->objSe->set('iduser', $row["idUsers"]);
				//$this->objSe->set('idprofile', $row["idprofile"]);
				
				//$this->useropc = $row["nameProfi"];
				// comprobar si usuario existe en usuarios/usuarios.txt a traves de comparacion secuencial NO SIRVE
				/*if (file_exists('usuarios/usuarios.txt')) {
					$filearray = file('usuarios/usuarios.txt');
					if ($filearray) {
						while (list($num, $lin) = each($filearray)) {
							if (isset($lin) && trim($lin)) {
								$usuarios = explode("|", trim($lin));
								if ($usuarios[0] == trim($user)) {
									print "El usuario ya existe, seleccione otro";
									return;
								}
							}
						}
					}
				}*/
				if(file_exists("./user/".$user)){//file_exists($folder)
					echo "Ya existe: $user";
					//header('Location: index.php');
				}else{
					$userData = $user."|".$password."\r\n";

					$usersFile = fopen('usuarios/usuarios.txt', "a");
					fwrite($usersFile, $userData);
					fclose($usersFile);
					mkdir("./user/".$user, 0700);
					print "Registrado satisfactoriamente";
					header('Location: user/index.php');
				}

				//$userData = $name."|".$lastName."|".$user."|".$password;
				/*$userData = $user."|".$password;
				$usersFile = fopen('usuarios/usuarios.txt', "a");
				fwrite($usersFile, $userData);
				fclose($usersFile);
				#Creación de directorio para archivos de usuario creado
				mkdir("./user/".$user, 0700);
				print "Registrado satisfactoriamente";*/
				//header('Location: user/index.php');
				/*switch($this->useropc){
					
					case 'Standard':
						header('Location: user/index.php');
						break;
						
					case 'Admin':
						header('Location: admin/index.php');
						break;
					
				}*/
				
			//}
			
		}else{
			
			header('Location: index.php?error=1');
			
		}
		
	}
}

?>