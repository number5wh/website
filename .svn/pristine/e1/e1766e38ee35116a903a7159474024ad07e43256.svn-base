<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/BankBLL.class.php';

class SysConfigAction extends PageBase
{	
	private $objMasterBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objMasterBLL = new MasterBLL();
	}
	public function index()
	{
		$arrSysConfig = $this->objMasterBLL->getSysConfigInfo();

		$arrTags=array('Sys'=>$arrSysConfig);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysConfigEdit.html');
	}	 
	
	/**
	 * 添加配置表
	 */
	public function addSysConfig()
	{
		$iResult=-9999;
		$arrParams['MaxRooms'] = Utility::isNumeric('MaxRooms',$_POST);
		$arrParams['DaySalary'] = Utility::isNumeric('DaySalary',$_POST);
		$arrParams['BasePower'] = Utility::isNumeric('BasePower',$_POST);
		$arrParams['MaxAccPowerPer'] = is_numeric($_POST['MaxAccPowerPer']) || is_float($_POST['MaxAccPowerPer']) ? $_POST['MaxAccPowerPer'] : 0;
		$arrParams['PowerPer'] = is_numeric($_POST['PowerPer']) || is_float($_POST['PowerPer']) ? $_POST['PowerPer'] : 0;
		if($arrParams['MaxRooms'])
			$arrReturns = $this->objMasterBLL->addSysConfig($arrParams);
		if(isset($arrReturns) && is_array($arrReturns) && count($arrReturns)>0)
			$iResult=$arrReturns['iResult'];
			
		if($iResult==0)
			$msg='系统配置设置成功';		
		elseif($iResult==-1)
			$msg='系统配置设置失败';
		else
			$msg='请输入正确的最大房间数';		

		echo $msg;
	}	
}
?>