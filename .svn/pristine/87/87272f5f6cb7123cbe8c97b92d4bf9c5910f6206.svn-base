<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';

class SysMsAction extends PageBase
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
		$arrResult = $this->getPagerSysMs(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysMsList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/SysMsList.html');
	}	 
	
	/**
	 * 分页
	 */
	private function getPagerSysMs($curPage,$strWhere)
	{
		$arrParam['fields']='MachineSerial,Interval,Times';
		$arrParam['tableName']='T_LimitMS';
		$arrParam['where']=' WHERE 1=1'.$strWhere;
		$arrParam['order']='MachineSerial DESC';
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
	public function getPagerSysMsList()
	{
		$strWhere = '';
		$Key = Utility::isNullOrEmpty('Key',$_POST);		
		
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		if($Key) $strWhere .= " AND MachineSerial='$Key'";

		$arrResult = $this->getPagerSysMs($curPage,$strWhere);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'SysMsList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/SysMsListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}	
	/**
	 * 添加
	 */
	public function setSysMs()
	{
		$iResult = -1;
		$arrParams['MS'] = Utility::isNullOrEmpty('MS',$_POST);
		$arrParams['Interval'] = Utility::isNumeric('Interval',$_POST);
		$arrParams['Times'] = Utility::isNumeric('Times',$_POST);
	

		if($arrParams['MS'] && $arrParams['Interval'] && $arrParams['Times'])
		{		
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->setSysMs($arrParams);
		}
		echo $iResult;
	}
	/**
	 * 删除
	 */
	public function deleteSysMs()
	{
		$iResult = -1;
		$MS = Utility::isNullOrEmpty('MS',$_POST);		
		if($MS)
		{
			$objPassAccountBLL = new PassAccountBLL();
			$iResult = $objPassAccountBLL->deleteSysMs($MS);
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>