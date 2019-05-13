<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class CardChargeRateAction extends PageBase
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
		$arrRateList = $this->objMasterBLL->getCardChargeRate(0);
		foreach ($arrRateList as $key=>$val){
		    $arrRateList[$key]['CardName']=Utility::gb2312ToUtf8($val['CardName']);
		}
		$arrTags=array('RateList'=>$arrRateList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/CardChargeRateList.html');
	}	 
	/**
	 * 显示添加黄钻等级表单
	 */
	public function showAddCardChargeRateHtml()
	{
		$CardID = Utility::isNumeric('CardID',$_POST);
		if($CardID){
			$arrRateList = $this->objMasterBLL->getCardChargeRate($CardID);
			if(is_array($arrRateList) && count($arrRateList)>0)
			{
				$arrConfigInfo['CardID']=$arrRateList[0]['CardID'];
				$arrConfigInfo['ChargeRate']=$arrRateList[0]['ChargeRate'];
				$arrConfigInfo['CardName']=Utility::gb2312ToUtf8($arrRateList[0]['CardName']);
			}
		}
		$arrTags = array('RateInfo'=>$arrConfigInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/CardChargeRateEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 添加黄钻等级
	 */
	public function addCardChargeRate()
	{
		$iResult = -9999;
		$arrParams['CardID'] = Utility::isNumeric('CardID',$_POST);
		$arrParams['ChargeRate'] = Utility::isNumeric('ChargeRate',$_POST);
		if($arrParams['CardID']!=null)
			$iResult = $this->objMasterBLL->addCardChargeRate($arrParams);
		if($iResult==0)
			$msg='充值折扣设置成功';
		elseif($iResult==-1)
			$msg='充值折扣设置失败';
		else
		    $ms='参数错误';
		echo $msg;
	}	
	/**
	 * 删除黄钻等级
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delGameConfig()
	{
		$iResult = -9999;
		$TypeID = Utility::isNumeric('TypeID',$_POST);
		if($TypeID && $TypeID>0)		
		{
			$iResult = $this->objMasterBLL->delGameConfig($TypeID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysGameConfig\')','删除失败,请重试','false','SysGameConfig',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysGameConfig\')','对不起,您提交的数据异常,请重试','false','SysGameConfig',$this->arrConfig);
		echo $html;
	}
}
?>