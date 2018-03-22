<?php
error_reporting(E_ALL ^ E_DEPRECATED);

function consultaUsuarios($user, $pass)
{
	
    $filename = "./usuarios/usuarios.txt";
    $handle2 = file($filename);
    print $user.$pass;
    while(list($var, $val) = each($handle2)){ 
        $val= explode('|', trim($val));
	    if ($val[0]==$user && $val[1] == $pass) {
	    	return true;
	    }
    }
    return false;
}

class Users{
	
	public $objDb;
	public $objSe;
	public $result;
	public $rows;
	public $useropc;
	
	public function __construct(){
		$this->objSe = new Sessions();
	}
	
	public function login_in(){

		$this->objSe->init();
		//$this->objSe->set('user', 'Carlos');
		
		$this->objSe->set('user', $_POST["usern"]);
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

		if($user!=''){
			if(consultaUsuarios($_POST["usern"], $_POST["passwd"])){
				if (file_exists("./user/".$user)) {
					header('Location: user/index.php');
				}else{
					print "Lo sentimos pero no existe el folder";
					echo "<a href='index.php'>Volver<a/>";
				}
				
			}else{								
				header('Location: index.php?error=1');
			}
		}else{
			header('Location: index.php?error=1');
		}
	}

	public function register_in(){
		$this->objSe->init();

		$this->objSe->set('user', $_POST["usern"]);
		$password=$_POST["passwd"];
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;

		if($user!=''){
			if(file_exists("./user/".$user)){
				header('Location: register.php?error=2');
			}else{
				$userData = $user."|".$password."\r\n";

				$usersFile = fopen('usuarios/usuarios.txt', "a");
				fwrite($usersFile, $userData);
				fclose($usersFile);
				mkdir("./user/".$user, 0700);
				print "Registrado satisfactoriamente";
				header('Location: user/index.php');
			}
		}else{
			header('Location: register.php?error=1');
		}
	}
}

?>