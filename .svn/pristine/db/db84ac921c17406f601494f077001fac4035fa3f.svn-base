<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysLuckyAction extends PageBase
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
		$arrLuckyList = $this->objMasterBLL->getLuckyList();
		$arrTags=array('LuckyList'=>$arrLuckyList);
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysLuckyList.html');
	}	 
	/**
	 * 显示添加运势级别
	 */
	public function showAddLuckyHtml()
	{
		$LuckyID = Utility::isNumeric('LuckyID',$_POST);
		if($LuckyID){
			$arrLuckyList = $this->objMasterBLL->getLuckyList();
			if(is_array($arrLuckyList) && count($arrLuckyList)>0)
			{
				foreach ($arrLuckyList as $val)
				{
					if($val['LuckyID']==$LuckyID)
					{
						$arrLuckyInfo['LuckyID']=$val['LuckyID'];
						$arrLuckyInfo['LuckyName']=$val['LuckyName'];
						$arrLuckyInfo['RndProb']=round($val['RndProb'],2);
						$arrLuckyInfo['SpProb']=round($val['SpProb'],2);
						$arrLuckyInfo['DropNum']=$val['DropNum'];
						$arrLuckyInfo['Probability']=round($val['Probability'],2);
						break;
					}
				}
			}
		}
		else 
			$arrLuckyInfo=array('LuckyID'=>1,'LuckyName'=>'','Status'=>1,'DropNum'=>0,'Probability'=>0);
		$arrTags = array('Lucky'=>$arrLuckyInfo);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysLuckyEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 显示添加掉落概率
	 */
	public function showAddLuckyProbHtml()
	{
		$arrLuckyProbList=null;
		$LuckyID = Utility::isNumeric('LuckyID',$_POST);
		if($LuckyID)
			$arrLuckyProbList = $this->objMasterBLL->getLuckyProbList($LuckyID);

		$arrTags = array('LuckyProbList'=>$arrLuckyProbList,'LuckyID'=>$LuckyID);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysLuckyProbEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 添加运势级别
	 */
	public function addLucky()
	{
		$iResult = -9999;
		$arrParams['LuckyID'] = Utility::isNumeric('LuckyID',$_POST);
		$arrParams['LuckyName'] = Utility::isNullOrEmpty('LuckyName',$_POST);
		$arrParams['RndProb'] = is_numeric($_POST['RndProb']) || is_float($_POST['RndProb']) ? $_POST['RndProb'] : 0;
		$arrParams['SpProb'] = is_numeric($_POST['SpProb']) || is_float($_POST['SpProb']) ? $_POST['SpProb'] : 0;
		if($arrParams['LuckyID'])
			$iResult = $this->objMasterBLL->addLucky($arrParams);
		if($iResult==0)
			$msg='运势级别设置成功';
		elseif($iResult==-1)
			$msg='运势级别设置失败';
		else
			$msg='您提交的运势级别数据异常,请重试';		

		echo $msg;
	}	
	/**
	 * 删除运势级别
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delLucky()
	{
		$iResult = -9999;
		$LuckyID = Utility::isNumeric('LuckyID',$_POST);
		if($LuckyID && $LuckyID>0)		
		{
			$iResult = $this->objMasterBLL->delLucky($LuckyID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysLucky\')','删除失败,请重试','false','SysLucky',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysLucky\')','对不起,您提交的数据异常,请重试','false','SysLucky',$this->arrConfig);
		echo $html;
	}
	/**
	 * 添加运势掉落概率
	 */
	public function addLuckyProb()
	{
		$iResult = -9999;
		$iID = 0;
		$arrParams['ID'] = Utility::isNumeric('ID',$_POST);
		$arrParams['LuckyID'] = Utility::isNumeric('LuckyID',$_POST);
		$arrParams['DropNum'] = Utility::isNumeric('DropNum',$_POST);
		$arrParams['Probability'] = is_numeric($_POST['Probability']) || is_float($_POST['Probability']) ? $_POST['Probability'] : 0;
		if($arrParams['LuckyID'])
		{
			$arrReturns = $this->objMasterBLL->addLuckyProb($arrParams);
			if(is_array($arrReturns) && count($arrReturns)>0)
			{
				$iResult = $arrReturns['iResult'];
				$iID = $arrReturns['ID'];
			}
		}
		if($iResult==0)
			$msg='运势掉落概率设置成功';
		elseif($iResult==-1)
			$msg='运势掉落概率设置失败';
		else
			$msg='您提交的运势级别数据异常,请重试';		

		echo json_encode(array('Msg'=>$msg,'iResult'=>$iResult,'iID'=>$iID));
	}	
	/**
	 * 删除运势掉落概率
	 * $iResult: 大于0:成功,0:失败,-2:数据库异常
	 */
	public function delLuckyProb()
	{
		$iResult = -9999;
		$ID = Utility::isNumeric('ID',$_POST);
		if($ID && $ID>0)		
			$iResult = $this->objMasterBLL->delLuckyProb($ID);
		echo json_encode(array('iResult'=>$iResult,'ID'=>$ID));
	}
}
?>