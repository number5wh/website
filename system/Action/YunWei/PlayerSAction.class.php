<?php
require ROOT_PATH . 'Common/PageBase.class.php';
require ROOT_PATH . 'Common/Session.class.php';
require ROOT_PATH . 'Class/BLL/CommonBLL.class.php';
require ROOT_PATH . 'Class/BLL/MasterBLL.class.php';
require ROOT_PATH . 'Class/BLL/PassAccountBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserDataBLL.class.php';
require ROOT_PATH . 'Class/BLL/UserBLL.class.php';
require ROOT_PATH . 'Class/BLL/OperationLogsBLL.class.php';

class PlayerSAction extends PageBase
{	
	public function __construct()
	{
		$this->arrConfig=unserialize(SYS_CONFIG);
		Utility::chkUserLogin($this->arrConfig);		
	}

	
	public function index()
	{
		$arrResult = $this->getPagerPlayerS(1,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerSList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$this->smarty->display($this->arrConfig['skin'].'/YunWei/PlayerSList.html');
	}
	/**
	 * 分页
	 */
	private function getPagerPlayerS($curPage,$strWhere)
	{
		$arrParam['fields']='RID,RoleID,MasterRight';
		$arrParam['tableName']='T_UserPrivilege';
		$arrParam['where']=' WHERE MasterRight=1 '.$strWhere;
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
	public function getPagerPlayerSList()
	{
		$curPage = Utility::isNumeric('curPage',$_POST);
		$curPage = $curPage==0 ? 1 : $curPage;
		$arrResult = $this->getPagerPlayerS($curPage,'');
		$arrTags=array('skin'=>$this->arrConfig['skin'],'Page'=>$arrResult['Page'],'PlayerSList'=>$arrResult['List']); 
		Utility::assign($this->smarty,$arrTags);
		$html = $this->smarty->fetch($this->arrConfig['skin'].'/YunWei/PlayerSListPage.html');
		$html=str_replace("</script>","<\/script>",str_replace("\r\n",'',$html));
		echo $html;
	}

	/**
	 * 批量添加玩家
	 */
	public function SetPlayerS()
	{
		$iResult = -1;
		$PrefixLoginCode = Utility::isNullOrEmpty('LoginCode',$_POST);
		$arrParams['StartNum'] = Utility::isNumeric('StartNum',$_POST);
		$arrParams['EndNum'] = Utility::isNumeric('EndNum',$_POST);
		$arrParams['MasterRight'] = 1;
		if($PrefixLoginCode && $arrParams['StartNum'] && $arrParams['EndNum'])
		{
			$arrParams['Passport'] = 0;
			$arrParams['LoginCode'] = $PrefixLoginCode . $arrParams['StartNum'];
			$iResult = $this->insertPlayerS($arrParams);
		}
		echo json_encode(array('iResult'=>$iResult,'LoginCode'=>$PrefixLoginCode,'StartNum'=>$arrParams['StartNum'],'EndNum'=>$arrParams['EndNum']));
	}
	private function insertPlayerS($arrParams)
	{
		$iResult = -1;
		//读取通行证ID号
		$objPassAccountBLL = new PassAccountBLL();
		$arrAccount = $objPassAccountBLL->getUserAccountList($arrParams['LoginCode'],4);
		if($arrAccount) $arrParams['Passport'] = $arrAccount[0]['Passport'];
		if(isset($arrParams['Passport']) && $arrParams['Passport']>0)
		{
			$objUserBLL = new UserBLL(0);
			//$arrResult = $objUserBLL->addPlayerS($arrParams);//添加玩家
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
		return $iResult;
	}

	/**
	 * 批量删除玩家
	 */
	public function DelPlayerS()
	{
		$iResult = -1;
		$PrefixLoginCode = Utility::isNullOrEmpty('LoginCode',$_POST);
		$arrParams['StartNum'] = Utility::isNumeric('StartNum',$_POST);
		$arrParams['EndNum'] = Utility::isNumeric('EndNum',$_POST);

		if($PrefixLoginCode && $arrParams['StartNum'] && $arrParams['EndNum'])
		{
			$arrParams['Passport'] = 0;
			$arrParams['LoginCode'] = $PrefixLoginCode . $arrParams['StartNum'];
			//读取通行证ID号
			$objPassAccountBLL = new PassAccountBLL();
			$arrAccount = $objPassAccountBLL->getUserAccountList($arrParams['LoginCode'],4);
			if($arrAccount) $arrParams['Passport'] = $arrAccount[0]['Passport'];
			
			if(isset($arrParams['Passport']) && $arrParams['Passport']>0)
			{
				$objUserBLL = new UserBLL(0);
				//删除玩家权限
				$iResult = $objUserBLL->delUserPrivilege($arrParams['Passport']);		
				//添加管理员操作日志
				$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
				$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
				$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
				$objOperationLogsBLL = new OperationLogsBLL(0);
				$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,删除系统玩家,玩家账号:'.$arrParams['LoginCode'], $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
		
			}
		}
		echo json_encode(array('iResult'=>$iResult,'LoginCode'=>$PrefixLoginCode,'StartNum'=>$arrParams['StartNum'],'EndNum'=>$arrParams['EndNum']));
	}
	/**
	 * 删除权限
	 */
	public function deletePlayerS()
	{
		$iResult = -1;
		$ID = Utility::isNumeric('ID',$_POST);	
		$Passport = Utility::isNumeric('Passport',$_POST);	
		$LoginName = Utility::isNullOrEmpty('LoginName',$_POST);		
		if($Passport)
		{
			$objUserBLL = new UserBLL(0);
			//删除玩家权限
			$iResult = $objUserBLL->delUserPrivilege($Passport);		
			//添加管理员操作日志
			$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
			$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
			$SysUserName = $objSessioin->get($this->arrConfig['SessionInfo']['UserName']);
			$objOperationLogsBLL = new OperationLogsBLL(0);
			$objOperationLogsBLL->addCaseOperationLogs($iAdminID, 0, 31, '权限设置,删除系统玩家,玩家昵称:'.$LoginName, $iResult, Utility::getIP(), 0, 2, $SysUserName, '');
		
		}
		echo json_encode(array('iResult'=>$iResult,'ID'=>$ID));
	}
}
?>