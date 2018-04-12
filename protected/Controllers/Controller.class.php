<?php
class Controller
{
	public function Run()
	{
		if(isset($_GET['r']))
		{
			$path = trim($_GET['r'], '/');
			$paths = explode('/', $path);
			$one = array_shift($paths);
			$two = array_shift($paths);
			$three = array_shift($paths);
		}
	$_GET['one'] = $one;
	$_GET['two'] = $two;
	$_GET['three'] = $three;
	$one = $_GET['one']=!empty($one) ? $one : "index";
	$two = $_GET['two']=!empty($two) ? $two : "index";
	$controlFile = ROOT_PATH . '/protected/app/'.$one.'/index.php';
	if(!is_file($controlFile)){
		error($controlFile,$one.'/'.$two,app不存在);
	}
	require($controlFile);
	if(!class_exists($one)){
		error($controlFile,$one.'/'.$two,类不存在);
	}
	$instance = new $one();
	if(!method_exists($instance,$two)){  
    error($controlFile,$one.'/'.$two,方法不存在);
	}
	$instance->$two($three);
	}
}
?>
