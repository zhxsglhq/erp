<?php
require_once 'function.php';
require_once 'Templates.class.php';
require_once ROOT_PATH . '/protected/Class/db/pdosql.class.php';
class index{
	public function init($app,$id){
		$sitetpl=initinfo($app,$id);
		$tpl=new Templates();
		$tpl->start($sitetpl);
	}
}
?>
