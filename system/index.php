<?php
require 'Include/Init.inc.php';

class App
{	
	function __construct()
	{	
		$this->index();		
	}
	
	private function index()
	{
		$strDirectoryName = ucfirst(isset($_GET['d']) ? $_GET['d'] : 'Login');
		$strClassName = ucfirst(isset($_GET['c']) ? $_GET['c'] : 'Login') . 'Action';
		$strActionName = lcfirst(isset($_GET['a']) ? $_GET['a'] : 'index');
		
		require_once ROOT_PATH . 'Action/'.$strDirectoryName.'/'.$strClassName.'.class.php';
		
		$actionObj = new $strClassName();//实例化方法
		$actionObj->setClassName($strClassName);//设置类名
		$actionObj->setActionName($strActionName);//设置方法名
		$actionObj->init();		
		
		//验证方法是否存在,存在则调用,否则输出报错信息
		if (method_exists($actionObj, $strActionName))
		{
			$actionObj->$strActionName();
		}
		else
		{
			echo("error!没有{$strActionName}方法！");
		}		
	}	
}
$theApp=new App();

?>