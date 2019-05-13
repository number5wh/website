<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';
require_once ROOT_PATH. 'Link/GetRoleBaseInfo.php';
session_start();
class LoginWarnLogsAction extends PageBase
{	
	private $objMatchBLL = null;	
	private $objCommonBLL = null;
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);
		$this->objCommonBLL = new CommonBLL($this->arrConfig['MapType']['SetOperationLogs']);
	}
	public function index()
	{
		$arrResult = null;
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'LogsList'=>$arrResult['arrLogsList'],'EndTime'=>date('Y-m-d'));
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunYing/LoginWarnLogsList.html');
	}	 

	/**
	 * 分页
	 */
	public function getPagerLogs($pagesize)
	{
		$arrLogsList = null;
		$strWhere = '';
		$curPage = Utility::isNumeric('curPage',$_POST);
		$EndTime = Utility::isNullOrEmpty('EndTime',$_POST) ? $_POST['EndTime'] : date('Y-m-d');
		$RoleID = Utility::isNumeric('RoleID',$_POST) ? $_POST['RoleID'] : '';

		$curPage = $curPage<=0 ? 1 : $curPage;

		if($RoleID)
		{
			//$objUserBLL = new UserBLL(0);
			$arrUserInfo = getUserBaseInfo($RoleID);//$objUserBLL->getRole(1,$LoginID);
			if(!empty($arrUserInfo))
				$strWhere .= " Where RoleID=".$arrUserInfo['RoleID'];
			else 
				return array('arrLogsList'=>null,'Page'=>null);
		}
		
		$arrParam['fields']='ServerID,RoleID,RoleName,LogType,ClientIP,MachineSerial,Phone,CardNo,CONVERT(VARCHAR(20),AddTime,120) AS AddTime';
		$_SESSION['ttableName'] = $arrParam['tableName']='T_LoginWarnLogs_'.str_replace('-','',$EndTime);
		$arrParam['where']=$strWhere;
		$arrParam['order']='AddTime DESC';
		$arrParam['pagesize']=$pagesize;

		//$this->objCommonBLL = new CommonBLL($this->arrConfig['MapType']['SetOperationLogs']);
		$iRecordsCount = $this->objCommonBLL->getRecordsCountSelect($arrParam);		
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		if($iRecordsCount>0)
			$arrLogsList = $this->objCommonBLL->getPageListSelect($arrParam,$Page['CurPage']);
 		if($arrLogsList)
		{
			$iCount = 0;
			foreach ($arrLogsList as $val)
			{
				$arrLogsList[$iCount]['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
				//$arr = $val['ClientIP'];
				// if($arr['ret'] != 1)
				// 	$arrLogsList[$iCount]['IPPlace']='(ip不可查)';
				// else 
				// 	$arrLogsList[$iCount]['IPPlace']= "(".$arr['province'].$arr['city'].$arr['district'].$arr['isp'].")";
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
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunYing/LoginWarnLogsListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 删除记录
	 */
    public function delPagerLogs()
    {
    	$iResult = -9999;
    	$RoleID = Utility::isNumeric('RoleID',$_POST);
    	$AddTime = Utility::isNullOrEmpty('AddTime', $_POST);
    	if($RoleID && $RoleID>0 && $AddTime && $_SESSION['ttableName'])
    	{
    		$iResult = $this->objCommonBLL->delPageListSelect($RoleID,$AddTime,$_SESSION['ttableName']);
    	}
    	else 
    	{
    		$iResult = -1;
    	}
    	if($iResult==0)
    		$msg='记录删除成功';
    	else if($iResult==-1)
    	      $msg='记录删除失败';
    	else
    		$msg='对不起,您提交的数据异常,请重试';
    	echo json_encode(array('iResult'=>$iResult,'Msg'=>$msg));
    }
}
?>