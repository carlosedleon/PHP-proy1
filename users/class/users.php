<?php

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
		$this->objSe->set('user', 'Carlos');
		$user = isset($_SESSION['user']) ? $_SESSION['user'] : null ;
		//$_SESSION['user']
		//if($this->rows > 0){
		if($user!=''){
			//if($row=mysql_fetch_array($this->result)){
				
				//$this->objSe->init();
				//$this->objSe->set('user', $row["loginUsers"]);
				//$this->objSe->set('iduser', $row["idUsers"]);
				//$this->objSe->set('idprofile', $row["idprofile"]);
				
				//$this->useropc = $row["nameProfi"];
				header('Location: user/index.php');
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