<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysIpAction extends PageBase
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
		$arrResult = $this->getPagerSysIp(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysIpList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysIpList.html');
	}	 
	
	/**
	 * 分页
	 */
	private function getPagerSysIp($curPage,$strWhere)
	{
		$arrParam['fields']='IP,Interval,Times';
		$arrParam['tableName']='T_LimitIP';
		$arrParam['where']=' WHERE 1=1'.$strWhere;
		$arrParam['order']='IP DESC';
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
	public function getPagerSysIpList()
	{
		$strWhere = '';
		$Key = Utility::isNullOrEmpty('Key',$_POST);		
		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($Key) $strWhere .= " AND IP='$Key'";

		$arrResult = $this->getPagerSysIp($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysIpList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysIpListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加
	 */
	public function setSysIp()
	{
		$iResult = -1;
		$arrParams['IP'] = Utility::isNullOrEmpty('IP',$_POST);
		$arrParams['Interval'] = Utility::isNumeric('Interval',$_POST);
		$arrParams['Times'] = Utility::isNumeric('Times',$_POST);
	

		if($arrParams['IP'] && $arrParams['Interval'] && $arrParams['Times'])
		{		
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->setSysIp($arrParams);
		}
		echo $iResult;
	}
	/**
	 * 删除
	 */
	public function deleteSysIp()
	{
		$iResult = -1;
		$IP = Utility::isNullOrEmpty('IP',$_POST);		
		if($IP)
		{
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->deleteSysIp($IP);
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>