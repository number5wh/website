<?php
require_once __DIR__.'/DALBase.php';

class UserDAL extends DALBase
{
	private $iRoleID = 0;
	public function __construct($iRoleID)
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID,$this->arrConfig['MapType']['User'],true);
	}

	/**
	 * 获取角色信息
	 */
	function getRoleInfo($iRoleID)
	{		
		if(empty($iRoleID)){
			$iRoleID = $this->iRoleID;
		}		
		//$this->strSelectKeySys .= $iRoleID;
		$arrResult = null;//$this->objMemcache->get($this->strSelectKeySys);	

		if(!$arrResult)
		{
			$params = array(array($iRoleID, SQLSRV_PARAM_IN));	
			$arrResult = $this->objUserDB->fetchAssoc("Proc_User_SelectInfo", $params);
			if(is_array($arrResult)){
				$arrResult['RoleID'] = $iRoleID;
				$arrResult['LoginName'] = Utility::gb2312ToUtf8($arrResult['LoginName']);
				$arrResult['Signature'] = str_replace(array("\r\n", "\r", "\n"),'<br />',Utility::gb2312ToUtf8($arrResult['Signature']));
				$arrResult['LockStartTime'] = date('Y-m-d H:i:s', strtotime($arrResult['LockStartTime']));
				$arrResult['LockEndTime'] = date('Y-m-d H:i:s', strtotime($arrResult['LockEndTime']));
				$arrResult['LastLoginTime'] = date('Y-m-d H:i:s', strtotime($arrResult['LastLoginTime']));
				$arrResult['AddTime'] = date('Y-m-d', strtotime($arrResult['AddTime']));
				$arrResult['VipExpireTime'] = date('Y-m-d H:i:s', strtotime($arrResult['VipExpireTime']));
				$arrResult['VipOpeningTime'] = date('Y-m-d H:i:s', strtotime($arrResult['VipOpeningTime']));
			}
			//$this->objMemcache->set($this->strSelectKeySys,$arrResult,true,$this->arrConfig['CacheTime']);
		}
		return $arrResult;
	}
	/**
	 * 获取角色信息
	 * @param $TypeID 搜索条件类型,0:按角色ID,1:按游戏编号LoginID
	 */
	function getRole($TypeID,$RoleID)
	{
		$arrResult = null;
		$params = array(array($RoleID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDB->fetchAssoc("Proc_User_Select", $params);
		
		if(is_array($arrResult) && count($arrResult)>0){
			//$arrResult['RoleID'] = $this->iRoleID;
			$arrResult['LoginName'] = Utility::gb2312ToUtf8($arrResult['LoginName']);
		}
		return $arrResult;
	}
	
	/**
	 * 获取角色信息
	 * @param $TypeID 搜索条件类型,0:按角色ID,1:按游戏编号LoginID
	 */
	function getRoleList($TypeID,$RoleID)
	{
		$params = array(array($RoleID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDB->fetchAllAssoc("Proc_User_Select", $params);
		if(empty($arrResult)) $arrResult = null;
		return $arrResult;
	}
	
	/**
	 * 主机解除绑定
	 */
	function UpdateMoorMachine()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN));
		$arrResult = $this->objUserDB->fetchAssoc("Proc_Role_UpdateMoorMachine", $params);
		return $arrResult;				
	}

	/**
	 * 判断角色是否被锁定
	 */
	function checkUserLockStatus()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDB->fetchAssoc("Proc_User_CheckLocked", $params);
		return $arrResult;
	}
	
	/**
	 * 锁定/解除角色
	 * @LockDays	SMALLINT,	--锁定天数
	 * @Locked		TINYINT		--状态,0:正常,1:锁定
	 */
	function LockUserLockedStatus($LockDays, $Locked)
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($LockDays, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDB->fetchAssoc("Proc_User_Locked", $params);
		return $arrResult;
	}
	
	/**
	 * 封号/解封
	 * @Locked TINYINT,--状态,0:正常,1:封号
	 * @Intro	VARCHAR(256)--封号原因
	 */
	function blockUserLockedStatus($Locked, $Intro)
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Intro), SQLSRV_PARAM_IN));	

		$arrResult = $this->objUserDB->fetchAssoc("Proc_User_Blocked", $params);
		return $arrResult;
	}
	
	/**
	 * 补发黄钻服务
	 * @author blj
	 * @param $dayNum
	 */
	public function ReturnBackUserVip($dayNum)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($this->arrConfig['BuyVipKeyID'], SQLSRV_PARAM_IN),
						array($dayNum, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312("天"), SQLSRV_PARAM_IN));
								
		$arrReturns = $this->objUserDB->fetchAssoc("Proc_UseStageProperty", $params);
				
		return $arrReturns;
	}
	/**
	 * 添加玩家
	 * @author xlj
	 * @param $arrParams
	 */
	public function addPlayer($arrParams)
	{
		$params = array(array($arrParams['Passport'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['LoginName']), SQLSRV_PARAM_IN),
						array($arrParams['Sex'], SQLSRV_PARAM_IN),
						array(1, SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN),
						array(1, SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN),
						array($arrParams['LoginID'], SQLSRV_PARAM_IN),
						array($arrParams['Mobile'], SQLSRV_PARAM_IN),
						array($arrParams['MaxRoleID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Signature']), SQLSRV_PARAM_IN));
								
		$arrReturns = $this->objUserDB->fetchAssoc("Proc_CreateRoleInfo", $params);
				
		return $arrReturns;
	}
	/**
	 * 添加玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function addUserPrivilege($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['Passport'], SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array($arrParams['MasterRight'], SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(date('Y-m-d'), SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN)
						);
		var_dump($params);
		$arrReturns = $this->objUserDB->fetchAssoc("Proc_UserPrivilege_Insert", $params);
		if($arrReturns) $iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	/**
	 * 删除玩家
	 * @author xlj
	 * @param $arrParams
	 */
	public function deletePlayer()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));								
		$this->objUserDB->fetchAssoc("Proc_Role_Delete", $params);
	}
	/**
	 * 读取玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function getUserPrivilegeInfo()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objUserDB->fetchAssoc("Proc_UserPrivilege_Select", $params);
		if(empty($arrReturns)) $arrReturns = null;
		return $arrReturns;
	}
	/**
	 * 读取角色信息
	 * @author xlj
	 * @param $LoginName
	 */
	public function getUserInfo($LoginName)
	{
		$params = array(array(Utility::utf8ToGb2312($LoginName), SQLSRV_PARAM_IN));								
		$arrReturns = $this->objUserDB->fetchAssoc("Proc_User_SelectByLoginName", $params);
		if(empty($arrReturns)) $arrReturns = null;
		return $arrReturns;
	}
	/**
	 * 删除玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function delUserPrivilege($Passport)
	{
		$iResult = -1;
		$params = array(array($Passport, SQLSRV_PARAM_IN));								
		$arrResult = $this->objUserDB->fetchAssoc("Proc_UserPrivilege_Delete", $params);
		if(is_array($arrResult) && count($arrResult)>0) $iResult=$arrResult['iResult'];
		return $iResult;
	}
	/**
	 * 设置/取消IP段锁定控制
	 * @author 
	 * @param $arrParams
	 * 0:成功,-1:失败
	 */
	public function setIpLocked($TitleID)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($TitleID, SQLSRV_PARAM_IN)
						);								
		$arrResult = $this->objUserDB->fetchAssoc("Proc_Role_UpdateTitleID", $params);	
		return $arrResult;
	}


	public function getpayWay($RoleID){
        $params = array(
            array($RoleID, SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objUserDB->fetchAssoc("P_UserPayWay_Select", $params);
        return $arrResult;
    }


    public function setpayWay($arrParams){
        $params =  array(
            array($arrParams['RoleId'], SQLSRV_PARAM_IN),
            array($arrParams['PayWayType'], SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['username']), SQLSRV_PARAM_IN),
            array($arrParams['BankCardNo'], SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['BankName']), SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objUserDB->fetchAssoc("P_UserPayWay_Update", $params);
        return $arrResult;
    }
	
}

?>