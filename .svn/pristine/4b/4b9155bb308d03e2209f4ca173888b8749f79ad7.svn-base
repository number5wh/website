<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysWarnAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{		
		$arrResult = $this->getPagerSysWarn(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysWarnList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysWarnList.html');
	}	 
	 
	/**
	 * 分页
	 */
	private function getPagerSysWarn($curPage,$strWhere)
	{
		$arrParam['fields']='WarnStr,TypeID';
		$arrParam['tableName']='T_Warnlist';
		$arrParam['where']=' WHERE 1=1'.$strWhere;
		$arrParam['order']='WarnStr DESC';
		$arrParam['pagesize']=20;
		$objCommonBLL = new CommonBLL(0);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam, $Page['CurPage']);
		return array('List'=>$arrResult,'Page'=>$Page);
	}
	
	/**
	 * 分页读取通行证列表
	 */
	public function getPagerSysWarnList()
	{
		$strWhere = '';
		$Key = Utility::isNullOrEmpty('Key',$_POST);		
		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($Key) $strWhere .= " AND WarnStr='$Key'";

		$arrResult = $this->getPagerSysWarn($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysWarnList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysWarnListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加
	 */
	public function setSysWarn()
	{
		$iResult = -1;
		$arrParams['WarnStr'] = Utility::isNullOrEmpty('WarnStr',$_POST);
		$arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);
	

		if($arrParams['WarnStr'] && $arrParams['TypeID'])
		{		
			$objMasterBLL = new MasterBLL();
			$iResult = $objMasterBLL->setSysWarn($arrParams);
		}
		echo $iResult;
	}
	/**
	 * 删除
	 */
	public function deleteSysWarn()
	{
		$iResult = -1;
		$WarnStr = Utility::isNullOrEmpty('WarnStr',$_POST);		
		if($WarnStr)
		{
			$objMasterBLL = new MasterBLL();
			$iResult = $objMasterBLL->deleteSysWarn($WarnStr);
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>