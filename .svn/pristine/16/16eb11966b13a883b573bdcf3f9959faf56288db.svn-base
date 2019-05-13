<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

class PlayerAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}
	public function showAddPlayer()
	{		
		$arrTags=array('LoginCode'=>'','MasterRight'=>-1); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/PlayerEdit.html');
	}
	
	public function index()
	{
		$arrResult = $this->getPagerPlayer(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/PlayerList.html');
	}
	/**
	 * 分页
	 */
	private function getPagerPlayer($curPage,$strWhere)
	{
		$arrParam['fields']='RID,RoleID,MasterRight';
		$arrParam['tableName']='T_UserPrivilege';
		$arrParam['where']=' WHERE MasterRight=32 '.$strWhere;
		$arrParam['order']='MasterRight,RID DESC';
		$arrParam['pagesize']=20;

		$objCommonBLL = new CommonBLL($this->arrConfig['MapType']['User']);
		$iRecordsCount = $objCommonBLL->getRecordsCount($arrParam);
		$Page=Utility::setPages($curPage,$iRecordsCount,$arrParam['pagesize']);
		$arrResult = $objCommonBLL->getPageList($arrParam,$Page['CurPage']);
		//print_r($arrResult);exit;
		if($arrResult)
		{
		
			$iCount = 0;
			foreach ($arrResult as $val)
			{
				$objUserBLL = new UserBLL($val['RoleID']);
				$arrUserInfo = $objUserBLL->getRole(0,$val['RoleID']);				
				if($arrUserInfo)
				{
					$arrResult[$iCount]['LoginName'] = $arrUserInfo['LoginName'];	
					$arrResult[$iCount]['LoginID'] = $arrUserInfo['LoginID'];
					$arrResult[$iCount]['Passport'] = $arrUserInfo['Passport'];
				}
				else
				{
					$arrResult[$iCount]['LoginName'] = '';
					$arrResult[$iCount]['LoginID'] = 0;
					$arrResult[$iCount]['Passport'] = 0;
				}
				$iCount++;
			}
		}
		return array('List'=>$arrResult,'Page'=>$Page);
	}
	/**
	 * 分页
	 */
	public function getPagerPlayerList()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrResult = $this->getPagerPlayer($curPage,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/PlayerListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}
	/**
	 * 添加玩家
	 */
	public function addPlayer()
	{
		$iResult = -1;
		$arrParams['LoginCode'] = Utility::isNullOrEmpty('LoginCode',$_POST);
		$arrParams['MasterRight'] = Utility::isNumeric('MasterRight',$_POST);
		if($arrParams['LoginCode'])
		{
			$arrParams['Passport'] = 0;
			//读取通行证ID号
			$objPassAccountBLL = new PassAccountBLL();
			$arrAccount = $objPassAccountBLL->getUserAccountList($arrParams['LoginCode'],4);
			if($arrAccount) $arrParams['Passport'] = $arrAccount[0]['Passport'];
			if(isset($arrParams['Passport']) && $arrParams['Passport']>0)
			{
				$objUserBLL = new UserBLL(0);
				//$arrResult = $objUserBLL->addPlayer($arrParams);//添加玩家
				//if(is_array($arrResult) && count($arrResult)>0)
				//{
				//添加玩家权限
				$iResult = $objUserBLL->addUserPrivilege($arrParams);		
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,添加系统玩家,玩家账号:'.$arrParams['LoginCode'], $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
					
				//}				
			}
		}
		echo $iResult;
	}
	public function resetPlayer()
	{
		$ID = Utility::isNumeric('ID',$_GET);
		$Passport = Utility::isNumeric('Passport',$_GET);
		$MasterRight = Utility::isNumeric('MasterRight',$_GET);
		$LoginCode = '';
		//读取通行证ID号
		$objPassAccountBLL = new PassAccountBLL();
		$arrAccount = $objPassAccountBLL->getUserAccountList($Passport,1);

		if($arrAccount) $LoginCode = $arrAccount[0]['LoginCode'];
		//print_r($LoginCode);exit;
		$arrTags=array('LoginCode'=>$LoginCode,'MasterRight'=>$MasterRight); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/PlayerEdit.html');
	}
	/**
	 * 删除权限
	 */
	public function deletePlayer()
	{
		$iResult = -1;
		$Passport = Utility::isNumeric('Passport',$_POST);		
		if($Passport)
		{
			$objUserBLL = new UserBLL(0);
			$iResult = $objUserBLL->delUserPrivilege($Passport);
			if($iResult==0)
			{
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,删除系统玩家,通行证编号:'.$Passport, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
			}
		}
		echo json_encode(array('iResult'=>$iResult));
	}
}
?>