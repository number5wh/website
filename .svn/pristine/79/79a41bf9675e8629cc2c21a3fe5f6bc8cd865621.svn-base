<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

require_once ROOT_PATH . 'Link/GetRoleBaseInfo.php';
class LogsAction extends PageBase
{	
	private $objMatchBLL = null;	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
	}
	public function index()
	{
		$arrResult = null;
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'LogsList'=>$arrResult['arrLogsList'],'EndTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/LogsList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerLogs($pagesize)
	{
		$arrLogsList = null;
		$strWhere = ' WHERE OpType=33';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$LoginID = Utility::isNumeric('LoginID',$_POST) ? $_POST['LoginID'] : '';

		$curPage = $curPage<=0 ? 1 : $curPage;

		/*if($LoginID)
		{
			//$objUserBLL = new UserBLL(0);
			$arrUserInfo = getUserBaseInfo($LoginID);//$objUserBLL->getRole(1,$LoginID);
			if(!empty($arrUserInfo))
				$strWhere .= " AND RoleID=".$arrUserInfo['RoleID'];
			else 
				return array('arrLogsList'=>null,'Page'=>null);
		}*/
		
		$arrParam['fields']='LogsID,RoleID,OpContent,OpResult,ClientIP,SysUserName,CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$arrParam['tableName']='T_OperationLogs_'.str_replace('-','',$EndTime);
		$arrParam['where']=$strWhere;
		$arrParam['order']='LogsID DESC';
		$arrParam['pagesize']=$pagesize;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['OperationLogs']);
		$iRecordsCount = $objCommonBLL->getRecordsCountSelect($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrLogsList = $objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
		if($arrLogsList)
		{
			$iCount = 0;
			foreach ($arrLogsList as $val)
			{
				$arrLogsList[$iCount]['OpContent'] = Utility::gb2312ToUtf8($val['OpContent']);
                $arrLogsList[$iCount]['LoginID'] = $val['RoleID'];
                /*if( !empty($val['RoleID']) ) $arrUserInfo = getUserBaseInfo($val['RoleID']);
				if(is_array($arrUserInfo) && count($arrUserInfo)>0)
				{
					$arrLogsList[$iCount]['LoginName'] = $arrUserInfo['LoginName'];
					$arrLogsList[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
				}
				else 
				{
					$arrLogsList[$iCount]['LoginName'] = '';
					$arrLogsList[$iCount]['LoginID'] = '';
				}
				if(!$LoginID) $arrUserInfo=null;*/
				$iCount++;
			}
		}
		return array('arrLogsList'=>$arrLogsList,'Page'=>$Page);
	}
	/**
	 * 分页读取
	 */
	public function getPagerLogsList()
	{
		$arrResult = $this->getPagerLogs(20);
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'LogsList'=>$arrResult['arrLogsList']);
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/LogsListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	
}
?>