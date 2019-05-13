<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysVipLevelAction extends PageBase
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
		$arrVipLevelList = $this->objMasterBLL->getVipLevel(0);
		$arrTags=array('VipLevelList'=>$arrVipLevelList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysVipLevelList.html');
	}	 
	/**
	 * 显示添加黄钻等级表单
	 */
	public function showAddVipLevelHtml()
	{
		$VipID = Utility::isNumeric('VipID',$_POST);
		if($VipID){
			$arrVipLevelList = $this->objMasterBLL->getVipLevel($VipID);
			if(is_array($arrVipLevelList) && count($arrVipLevelList)>0)
			{
				$arrVipInfo['VipID']=$arrVipLevelList[0]['VipID'];
				$arrVipInfo['FwMoney']=$arrVipLevelList[0]['FwMoney'];
				$arrVipInfo['Discount']=$arrVipLevelList[0]['Discount'];
				$arrVipInfo['AdditivePower']=round($arrVipLevelList[0]['AdditivePower'],2);
			}
		}
		else 
			$arrVipInfo=array('VipID'=>1,'FwMoney'=>0,'Discount'=>0,'AdditivePower'=>0);
		$arrTags = array('VipInfo'=>$arrVipInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysVipLevelEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 添加黄钻等级
	 */
	public function addVipLevel()
	{
		$iResult = -9999;
		$arrParams['VipID'] = Utility::isNumeric('VipID',$_POST);
		$arrParams['FwMoney'] = Utility::isNumeric('FwMoney',$_POST);
		$arrParams['Discount'] = is_numeric($_POST['Discount']) || is_float($_POST['Discount']) ? $_POST['Discount'] : 0;
		$arrParams['AdditivePower'] = is_numeric($_POST['AdditivePower']) || is_float($_POST['AdditivePower']) ? $_POST['AdditivePower'] : 0;
		if($arrParams['VipID'])
			$iResult = $this->objMasterBLL->addVipLevel($arrParams);
		if($iResult==0)
			$msg='黄钻等级设置成功';
		elseif($iResult==-1)
			$msg='黄钻等级设置失败';
		else
			$msg='您提交的黄钻等级数据异常,请重试';	

		echo $msg;
	}	
	/**
	 * 删除黄钻等级
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delVipLevel()
	{
		$iResult = -9999;
		$VipID = Utility::isNumeric('VipID',$_POST);
		if($VipID && $VipID>0)		
		{
			$iResult = $this->objMasterBLL->delVipLevel($VipID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','删除失败,请重试','false','SysVipLevel',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysVipLevel\')','对不起,您提交的数据异常,请重试','false','SysVipLevel',$this->arrConfig);
		echo $html;
	}
}
?>