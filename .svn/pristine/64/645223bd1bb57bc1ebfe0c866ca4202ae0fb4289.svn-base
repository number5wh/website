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
    		    Utility::Log("home_access_error", "url", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
    		    if(strtolower($strActionName)=="sign_in"){   //客户端签到
    		        $url = $CFG['URL']['Sign'];
    		    }else if(strtolower($strActionName)=="get_card"){   //实卡领取
    		        $url = $CFG['URL']['RealCard'];
    		    }else if(strtolower($strActionName)=="cardrecharge"){   //客户端签到
    		        $url = $CFG['URL']['CardCharge'];
    		    }else{
    		         $url = $CFG['URL']['Safety'];
    		    }
                if($url != $CFG['URL']['Home'] && $_SERVER['QUERY_STRING']){
                    $url = $url."?".$_SERVER['QUERY_STRING'];
                }
                header("Location: ".$url);
    		}	
        }else{
    		Utility::Log("home_access_error", "url", 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            if(strtolower($strClassName)=="tdcodeaction"){   //微信绑定二维码站点
                $url = $CFG['URL']['QRCode'];
            }else if(strtolower($strClassName)=="signaction"){  // 签到站点
                $url = $CFG['URL']['Sign'];
            }else {  //官网
                $url = $CFG['URL']['Home'];
            }
            if( $url != $CFG['URL']['Home'] && ($_SERVER['QUERY_STRING'])){
                $url = $url."?".$_SERVER['QUERY_STRING'];
            }
            header("Location: ".$url);
        }
		
	
	}
}
$theApp=new App();


?>
