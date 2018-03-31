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
	if(!is_file($controlFile))
	{
		error($controlFile,$core.'/'.$two,2);
	}
	require($controlFile);
	$instance = new $one();
	$instance->$two($three);
	}
}
?>
