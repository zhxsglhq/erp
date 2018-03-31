<?php
require_once 'login.class.php';
require_once ROOT_PATH . '/protected/app/function.php';
require_once ROOT_PATH . '/protected/Class/db/pdosql.class.php';
class login{
	public function logins(){
		if(isset($_POST["usercode"]) or isset($_POST["password"])){
			$Auth=new Auth();
		  $login=$Auth->login($_POST["usercode"],$_POST["password"]);
			if($login){
				$return_arr = $login;
			}else{
				$return_arr = 0;
			}
			echo json_encode($return_arr);
		}
	}

	public function logout(){
		$Auth=new Auth();
		$login=$Auth->logout();
		echo json_encode($login);
  }
  
	public function check_login(){
		if($_SESSION["uid"]){
      $return_arr = 1;
    }else{
      $return_arr = 0;
    }
    echo json_encode($return_arr);
  }            
}
?>
