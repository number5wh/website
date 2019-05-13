<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysIntervalIpAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function index()
	{		
		//$objMasterBLL = new MasterBLL();
		//$arrDeptList = $objMasterBLL->getDepartmentList();
		$arrResult = $this->getPagerSysIntervalIp(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysIntervalIpList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysIntervalIpList.html');
	}	 
	
	/**
	 * 分页
	 */
	private function getPagerSysIntervalIp($curPage,$strWhere)
	{
		$arrParam['fields']='dbo.UF_ConvertIPToStr(StartIP) AS StartIP,dbo.UF_ConvertIPToStr(EndIP) AS EndIP,ID';
		$arrParam['tableName']='T_IntervalIP';
		$arrParam['where']=$strWhere;
		$arrParam['order']='ID DESC';
		$arrParam['pagesize']=20;

		$objPassAccountBLL = new PassAccountBLL();
		$iRecordsCount = $objPassAccountBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objPassAccountBLL->getPageList($arrParam,$Page['CurPage']);
		return array('List'=>$arrResult,'Page'=>$Page);
	}
	
	/**
	 * 分页读取通行证列表
	 */
	public function getPagerSysIntervalIpList()
	{
		$strWhere = '';
		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;

		$arrResult = $this->getPagerSysIntervalIp($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysIntervalIpList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysIntervalIpListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加
	 */
	public function setSysIntervalIp()
	{
		$iResult = -1;
		$arrParams['StartIP'] = Utility::isNullOrEmpty('StartIP',$_POST);
		$arrParams['EndIP'] = Utility::isNullOrEmpty('EndIP',$_POST);	

		if($arrParams['StartIP'] && $arrParams['EndIP'])
		{		
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->setSysIntervalIp($arrParams);
		}
		echo $iResult;
	}
	/**
	 * 删除
	 */
	public function deleteSysIntervalIp()
	{
		$iResult = -1;
		$ID = Utility::isNullOrEmpty('ID',$_POST);		
		if($ID)
		{
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->deleteSysIntervalIp($ID);
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>