<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';

class SysLoginIDAction extends PageBase
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
		$arrResult = $this->getPagerLoginID();
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'LoginIDList'=>$arrResult['arrLoginIDList']);
		Utility::assign($this->smarty,$arrTags);	
		
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysLoginIDList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerLoginID()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage<=0 ? 1 : $curPage;
		$arrParam['fields']='LoginID,IsUsed,Sales';
		$arrParam['tableName']='T_ConfineLoginID';
		$arrParam['where']='';
		$arrParam['order']='IsUsed,LoginID';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrLoginIDList = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		
		return array('arrLoginIDList'=>$arrLoginIDList,'Page'=>$Page);
	}
	/**
	 * 分页读取玩家编号 
	 */
	public function getPagerLoginIDList()
	{
		$arrResult = $this->getPagerLoginID();
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'LoginIDList'=>$arrResult['arrLoginIDList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysLoginIDListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 删除玩家编号
	 * $iResult: 0:成功,-1:失败
	 */
	public function delLoginID()
	{
		$iResult = -1;
		$LoginID = Utility::isNumeric('LoginID',$_POST);
		if($LoginID && $LoginID>0)		
		{
			$iResult = $this->objMasterBLL->delLoginID($LoginID);
			if($iResult==0)
				$html=$iResult;
			else
				$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysLoginID\')','删除失败,请重试','false','SysLoginID',$this->arrConfig);
		}
		else 
			$html=Utility::echoResultHtml($this->smarty,'取 消','main.CloseMsgBox(false,\'SysLoginID\')','对不起,您提交的数据异常,请重试','false','SysLoginID',$this->arrConfig);
		echo $html;
	}
	/**
	 * 显示添加玩家编号表单
	 */
	public function showAddLoginIDHtml()
	{
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysLoginIDEdit.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));

		echo $html;
	}
	/**
	 * 添加角色等级
	 */
	public function addLoginID()
	{
		$IsOver = false;
		$LoginID = 0;
		$iResult = -1;
		$iSuccess = 0;
		$iFail = 0;
		$iCount = 0;
		$StartLoginID = Utility::isNumeric('StartLoginID',$_POST);
		$EndLoginID = Utility::isNumeric('EndLoginID',$_POST);
		
		if($StartLoginID && $StartLoginID>0 && $EndLoginID && $StartLoginID<=$EndLoginID)
		{
			for ($LoginID=$StartLoginID;$LoginID<=$EndLoginID;$LoginID++)
			{
				$Sales = 0;
				$Pattern = '';
				foreach ($this->arrConfig['LoginIdRule'] as $R)
				{
					$Sales = preg_match('/'.$R['Regx'].'/', $LoginID);					
					if($Sales==1)
					{
						$Pattern = $R['Pattern'];
						break;
					}
				}
				$iResult = $this->objMasterBLL->addLoginID($LoginID,$Sales,$Pattern);
				if($iResult==0)
					$iSuccess++;
				else
					$iFail++;
				$iCount++;
				if($iCount==500) break;				
			}
		}			
		if($StartLoginID>=$EndLoginID) $IsOver = true;
		echo json_encode(array('iSuccess'=>$iSuccess,'iFail'=>$iFail,'iCount'=>$iCount,'IsOver'=>$IsOver,'StartLoginID'=>$LoginID+1,'EndLoginID'=>$EndLoginID));
	}	
	
}
?>