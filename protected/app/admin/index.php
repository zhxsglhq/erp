<?php
//if(!$_SESSION["uid"]){exit()};
require_once ROOT_PATH . '/protected/Class/tree.class.php';
require_once ROOT_PATH . '/protected/app/function.php';
require_once ROOT_PATH . '/protected/Class/db/pdosql.class.php';
class admin{
  function __construct(){
    $this->uid= $_SESSION["uid"];
  }
	public function app(){
    $this->userinfo=user_info($this->uid);
    $this->rows=applist("App_Close=0 && App_Id in (".$this->userinfo["power"].")");
    $bta = new BuildTreeArray($this->rows,'App','App_Id','App_ClassId',0);
    $data = $bta->getTreeArray();
    echo json_encode($data);
	}

	public function userinfo(){
    print_r(user_info($this->uid));
  }
          
}
?>
