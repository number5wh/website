<?php
require 'Include/Init.inc.php';
require 'Include/Smarty.inc.php';
require_once ROOT_PATH . 'Common/Session.class.php';
require_once ROOT_PATH.'Link/CheckAccount.php';
require_once ROOT_PATH.'Link/BuyHappyBean.php';
require_once ROOT_PATH.'Link/AddPayLogs.php';
require_once ROOT_PATH.'Link/CreatePayOrder.php';
require_once ROOT_PATH.'Link/FindPayOrder.php';
require_once ROOT_PATH.'Link/SetPayOrderStatus.php';
require_once ROOT_PATH.'Link/SetPayOrderTransactionID.php';
require_once ROOT_PATH.'Link/GetPayOrder.php';
require_once ROOT_PATH.'Link/GetPayOrderID.php';
require_once ROOT_PATH.'Link/GetPayConfig.php';
require_once ROOT_PATH.'Link/UpdatePayConfig.php';





class App
{	
	function __construct()
	{	
		$method=Utility::isNullOrEmpty('f', $_GET)?$_GET['f']:'index';
		switch ($method)
		{
			case 'index':
				$this->index();
				break;
            case 'test':
                $this->test();break;
		}
	}
	private function index()
	{
		$CFG=unserialize(SYS_CONFIG);
		$strPageName = (Utility::isNullOrEmpty('n', $_GET) ? $_GET['n'] : 'Index') . '.class.php';
		$strClassName = ucfirst(Utility::isNullOrEmpty('n', $_GET) ? $_GET['n'] : 'Index') . 'Action';
		$strActionName = lcfirst(Utility::isNullOrEmpty('a', $_GET) ? $_GET['a'] : 'index');


		require_once ROOT_PATH . 'Action/'.$strPageName;
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
			Utility::output("error!没有{$strActionName}方法！");
		}		
	}
    private function test(){
		//echo phpinfo();
      //  $out_data = ASCheckAccount('a634771197');
      //  $out_data = DCBuyHappyBean('60009', '100000','2');
     //  $out_data = ASGetAccountInfo("zhuliangying123");

     //  $out_data['iAddTime'] = date('Y-m-d H:i:s',$out_data['iAddTime']);
       // $out_data =  ASMakeSecCode('oSBgrwHFj6aHJboTMDURLPua_6Nw');
       // $out_data =  ASGetSecCode('oSBgrwHFj6aHJboTMDURLPua_6Nw');
        //$ret['code'] = 'c634771197';
       // $ret['open_id'] = 'c634771197';
      //  $out_data = ASBindWeChat($ret['code'],$ret['open_id']);
        //$out_data = ASRegisterAccount("zhuliangying123",md5("123456"),"192.168.1.123","朱良英","330304199310042131","18968044846","291207352");
      //  print_r($out_data);
     // $out_data = OSCreatePayOrder( 1, 1, "", "20151127210630837004", 500 * 100, 800143);
    // $out_data = OSFindPayOrder(  1, "", "20151127210630837004", 0);
    // $out_data = OSUpdatePayConfig('PrivateAmount',100);
   //$out_data = OSGetPayConfig();
     $out_data = OSGetPayOrderID("2015083700");
      print_r($out_data);
    }
}
$theApp=new App();


?>