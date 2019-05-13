<?php
require 'Include/Init.inc.php';
require 'Include/Smarty.inc.php';

class App
{	
	function __construct()
	{	
		$method=Utility::isNullOrEmpty('f', $_GET)?$_GET['f']:'index';
		switch ($method)
		{
            /*
                case 'test':
                $this->test();
                break; 
            */
            default:
				$this->index();
				break;
		}
	}
	private function index()
	{
		$CFG=unserialize(SYS_CONFIG);
		$strPageName = ucfirst(Utility::isNullOrEmpty('n', $_GET) ? $_GET['n'] : 'Index') . '.class.php';
		$strClassName = ucfirst(Utility::isNullOrEmpty('n', $_GET) ? $_GET['n'] : 'Index') . 'Action';
		$strActionName = lcfirst(Utility::isNullOrEmpty('a', $_GET) ? $_GET['a'] : 'index');
        $file = ROOT_PATH . 'Action/'.$strPageName;
        if(file_exists($file)){
            require_once $file;
    		$actionObj = new $strClassName();//实例化方法
    		$actionObj->setClassName($strClassName);//设置类名
    		$actionObj->setActionName($strActionName);//设置方法名
    		$actionObj->init();		//验证方法是否存在,存在则调用,否则跳转到首页
    		if (method_exists($actionObj, $strActionName))
    		{
    			$actionObj->$strActionName();
    		}
    		else
    		{
    		    header("Location: ".$CFG['URL']['Home']);
    		}	
        }else{
            header("Location: ".$CFG['URL']['Home']);
        }
		
	
	}
}
$theApp=new App();


?>