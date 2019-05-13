<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysBlackAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{		
		$arrResult = $this->getPagerSysBlack(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysBlackList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysBlacKList.html');
	}	 
	
	/**
	 * 分页
	 */
	private function getPagerSysBlack($curPage,$strWhere)
	{
		$arrParam['fields']='LimitStr,TypeID';
		$arrParam['tableName']='T_Blacklist';
		$arrParam['where']=' WHERE 1=1'.$strWhere;
		$arrParam['order']='LimitStr DESC';
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
	public function getPagerSysBlackList()
	{
		$strWhere = '';
		$Key = Utility::isNullOrEmpty('Key',$_POST);		
		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($Key) $strWhere .= " AND LimitStr='$Key'";

		$arrResult = $this->getPagerSysBlack($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysBlackList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysBlackListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加
	 */
	public function setSysBlack()
	{
		$iResult = -1;
		$arrParams['LimitStr'] = Utility::isNullOrEmpty('LimitStr',$_POST);
		$arrParams['TypeID'] = Utility::isNumeric('TypeID',$_POST);
		if($arrParams['LimitStr'] && $arrParams['TypeID'])
		{		
		    if($arrParams['TypeID']=='1' && !Utility::testIp($arrParams['LimitStr']))
		           $iResult = -1;
		    else if($arrParams['TypeID']=='2' && strlen($arrParams['LimitStr'])!=32 )
		           $iResult = -1;
		    else if($arrParams['TypeID']=='3' && !Utility::testIpRand($arrParams['LimitStr']))
		           $iResult = -1;
		    else {
			     $objMasterBLL = new MasterBLL();
			     $iResult = $objMasterBLL->setSysBlack($arrParams);
		    }
		}
		echo $iResult;
	}
	/**
	 * 删除
	 */
	public function deleteSysBlack()
	{
		$iResult = -1;
		$LimitStr = Utility::isNullOrEmpty('LimitStr',$_POST);		
		if($LimitStr)
		{
			$objMasterBLL = new MasterBLL();
			$iResult = $objMasterBLL->deleteSysBlack($LimitStr);
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>