<?php
require_once __DIR__.'/DALBase.php';

class UserDataDAL extends DALBase
{
	private $iRoleID = 0;
	public function __construct($iRoleID)
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID,$this->arrConfig['MapType']['UserData'],true);
	}

	/**
	 * 获取用户体力值
	 */
	function getUserPowerInfo()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserPower_SelectInfo", $params);
		return $arrResult;
	}
	
	/**
	 * 获取用户的游戏种类ID,金币
	 */
	function getUserGameWealth()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAllAssoc("Proc_UserGameWealth_Select", $params);
		return $arrResult;
	}
	/**
	 * 处理财富——银行存款
	 * @iKindID	INT,
	 * @iMoney		BIGINT,	--金币
	 * @iObjType	TINYINT	--存款转出对象类型,0:游戏,1:背包
	 */
	function editUserBankCunKuan($iKindID, $iMoney, $iObjType)
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($iKindID, SQLSRV_PARAM_IN),
						array($iMoney, SQLSRV_PARAM_IN),
						array($iObjType, SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserBank_CunKuan", $params);
		return $arrResult;
	}
	
	/**
	 * 获取背包中金币的值,背包格总数,锁定信息
	 */
	function getMyKnapsackMoney()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_MyKnapsack_Select", $params);
		return $arrResult;
	}
	
	/**
	 * 获取银行中金币的值
	 */
	function getUserBankMoney()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserBank_SelectMoney", $params);
		return $arrResult;
	}
	
	/**
	 * 获取用户的月礼包信息
	 */
	function getMonthGiftPackage()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_MonthGiftPackage_SelectReceiveFail", $params);
		return $arrResult;	
	}
	
	/**
	 * 返回已使用的格子数 space 1:背包  2:衣柜
	 */
	function getUsedMyKnapsacksByRoleID($Space)
	{
		$result = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($Space, SQLSRV_PARAM_IN)
		 			);
		$result = $this->objUserDataDB->fetchAssoc("Proc_UserStageProperty_SelectUseSpGridCount", $params);
		if($result) return $result['iCount'];
		return $result;
	}
	
	/**
	 * 返回玩过的游戏种类ID
	 */
	function getUserGameData($strWhere)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($strWhere, SQLSRV_PARAM_IN)
		 			);
		$arrReturns = $this->objUserDataDB->fetchAllAssoc("Proc_UserGameData_Select", $params);
		if(empty($arrReturns)) $arrReturns = null;
		return $arrReturns;
	}
	
	/**
	 * 冻结财富，修改冻结的金币数量和龙币数量
	 * @iMoney		BIGINT,		--冻结金额(金币)
	 * @iFwMoney	BIGINT		--冻结金额(龙币)
	 * @iStatus		TINYINT		--0:冻结,1:返还
	 */
	function editUserBankMoney($iMoney, $iFwMoney, $iStatus)
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($iMoney, SQLSRV_PARAM_IN),
						array($iFwMoney, SQLSRV_PARAM_IN),
						array($iStatus, SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserBank_LockMoney", $params);
		return $arrResult;
	}
	
	/**
	 * 检查用户锁定状态（T_UserBank,T_MyKnapsack,T_MyArmoire,T_UserStageProperty）
	 */
	function checkUserLockInfo()
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserData_CheckLocked", $params);
		return $arrResult;
	}
	
	/**
	 * 锁住/解锁角色仓库数据
	 * @param $Locked BIT	--0:正常,1:锁住
	 */
	function editUserDataLocked($Locked)
	{
		$arrResult = null;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserData_Locked", $params);
		return $arrResult;
	}
	
	/**
	 * 重置背包密码
	 * @param $strPwd
	 */
	function updateMyKnapsackPwd($strPwd)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array(md5($strPwd),SQLSRV_PARAM_IN));
							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_MyKnapsack_UpdatePwd", $params);
		return $arrReturn;
	}
	
	/**
	 * 重置银行密码
	 * @param $strPwd
	 */
	function updateBankPwd($strPwd)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
					    array('',SQLSRV_PARAM_IN),
					    array(md5($strPwd),SQLSRV_PARAM_IN),
					    array(0,SQLSRV_PARAM_IN),
						array('',SQLSRV_PARAM_IN)
						);
							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_UserBank_Update", $params);
		return $arrReturn;
	}
	
	/**
	 * 补发道具到用户背包
	 * @author blj
	 * @param $spId
	 */
	public function returnBackStageProperty($spId)
	{
		$iResult = -1;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
    					array($spId, SQLSRV_PARAM_IN),    				
    					array(1, SQLSRV_PARAM_IN),
    					array(0, SQLSRV_PARAM_IN),
    					array(0, SQLSRV_PARAM_IN),
    					array(0, SQLSRV_PARAM_IN),
    					array(5, SQLSRV_PARAM_IN),
    					array(0, SQLSRV_PARAM_IN),
    					array('', SQLSRV_PARAM_IN));
    	
		$arrReturns = $this->objUserDataDB->fetchAssoc("Proc_UserStageProperty_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iResult = $arrReturns['iResult'];
		}
		return $iResult;
	}
	
	/**
	 * 补发积分到用户游戏中
	 * @author blj
	 * @param $kindId
	 * @param $num
	 */
	public function returnBackGameScore($kindId,$num)
	{
		$iResult = -1;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
    					array($kindId, SQLSRV_PARAM_IN),    				
    					array($num, SQLSRV_PARAM_IN));
		$arrReturns = $this->objUserDataDB->fetchAssoc("Proc_UserGameWealth_AddScore", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iResult = $arrReturns['iResult'];
		}
		return $iResult;
	}
	
	/**
	 * 解冻用户银行
	 * @iRoleID	INT,
	 * @Status	SMALLINT,	--状态，0正常,1锁定修改密码,2锁定交易密码
	 * @Minites	SMALLINT	--冻结有效时间,单位:分钟
	 * Return: 0:成功,-1:失败
	 */
	function updateUserBankStatus($Status,$Minites)
	{
		$iResult = -1;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
    					array($Status, SQLSRV_PARAM_IN),    				
    					array($Minites, SQLSRV_PARAM_IN));
    	
		$arrReturns = $this->objUserDataDB->fetchAssoc("Proc_UserBank_UpdateStatus", $params);
		if(is_array($arrReturns) && $arrReturns['iResult']>0)
		{
			$iResult = $arrReturns['iResult'];
		}
		return $iResult;    					
	}
	
	/**
	 * 解冻用户背包
	 * @param $Status	SMALLINT,--锁定状态,0:正常,1:丢弃道具时输入密码操作锁定状态,2:修改密码操作淘宝状态
	 * @param $Minites	@Minites	SMALLINT --冻结有效时间,单位:分钟	
	 * @Return: 0:成功,-1:失败
	 */
	function updateMyKnapsackStatus($Status,$Minites)
	{
		$iResult = -1;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
    					array($Status, SQLSRV_PARAM_IN),    				
    					array($Minites, SQLSRV_PARAM_IN));
    	
		$arrReturns = $this->objUserDataDB->fetchAssoc("Proc_MyKnapsack_UpdateStatus", $params);
		if(is_array($arrReturns) && $arrReturns['iResult']>0)
		{
			$iResult = $arrReturns['iResult'];
		}
		return $iResult;  
	}
	
	/**
	 * 我的背包和银行是否被锁定
	 */
	function checkBankMyKnapsackStatus()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));
							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_BankAndKnapsack_CheckLocked", $params);
		return $arrReturn;
	}
	
	/**
	 * 检查我的背包和银行是否设置密码
	 */
	function checkBankMyKnapsackPWD()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));
							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_BankAndKnapsack_CheckPwdNull", $params);
		return $arrReturn;
	}
	
	/**
	 * 获取用户游戏中的所有财富
	 */
	function getUserGameWealthAllMoney()
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));
							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_UserGameWealth_SelectSum", $params);
		return $arrReturn;
	}
	
	/**
	 * 添加角色银行，背包，衣柜
	 */
	function addUserBox()
	{
		$iResult = -1;
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN));							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_UserBox_Insert", $params);
		if($arrReturn) $iResult = $arrReturn['iResult'];
		return $iResult;
	}

	/**
	 * 更新玩家在房间状态
	 */
	function updateInGame($iRoleID,$KindID)
	{
		$iResult = -1;
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN)
						);							  
		$arrReturn = $this->objUserDataDB->fetchAssoc("Proc_UserGameWealth_InGame", $params);
		if($arrReturn) $iResult = $arrReturn['iResult'];
		return $iResult;
	}
	/**
	 * 统计报表，按充值方式和金额分组汇总
	 * @author xlj
	 * @param $StartTime
	 * @param $EndTime
	 * @return array
	 */
	public function getRechargeOrderCount($StartTime,$EndTime)
	{
		$params = array(array($StartTime,SQLSRV_PARAM_IN),
						array($EndTime,SQLSRV_PARAM_IN));			
		$arrResults = $this->objUserDataDB->fetchAllAssoc("Proc_RechargeOrder_Count",$params);
		if(empty($arrResults)) $arrResults=null;	
		return $arrResults;
	}
	/**
	 * 返回玩家金币存货总数
	 * @author xlj
	 * @return array
	 */
	public function getUserWealth()
	{		
		$arrResults = $this->objUserDataDB->fetchAssoc("Proc_UserWealth_Select",'');
		if(empty($arrResults)) $arrResults=null;	
		return $arrResults;
	}
	/**
	 * 玩家是否游戏中
	 */
	function getUserWealthInGame($RoleID)
	{
		$iResult = -1;
		$params = array(array($RoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserGameWealth_SelectInGame", $params);
		if(!empty($arrResult)) $iResult = $arrResult['iResult'];
		return $iResult;
	}
	/**
	 * 读取充值记录信息
	 */
	function getRechargeOrderInfo($RoleID,$OrderSerial)
	{
		$params = array(array($RoleID, SQLSRV_PARAM_IN),
						array($OrderSerial, SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_RechargeOrder_SelectInfo", $params);
		if(empty($arrResult)) $arrResult = null;
		return $arrResult;
	}
	/**
	 * 补发金币充值
	 */
	function setUserBankRecharge($OrderSerial,$PayMoney,$PayID,$Amount,$payMethod)
	{
		$params = array(array($OrderSerial, SQLSRV_PARAM_IN),
						array($PayMoney, SQLSRV_PARAM_IN),
						array($PayID, SQLSRV_PARAM_IN),
						array($Amount, SQLSRV_PARAM_IN),
						array($payMethod, SQLSRV_PARAM_IN)
						);	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserBank_Recharge", $params);
		if(empty($arrResult)) $arrResult = null;
		return $arrResult;
	}
	/**
	 * 补发金币充值回滚
	 */
	function setUserBankRollback($OrderSerial)
	{
		$iResult = -1;
		$params = array(array($OrderSerial, SQLSRV_PARAM_IN)						
						);	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserBank_Rollback", $params);
		if(!empty($arrResult)) $iResult = $arrResult['iResult'];
		return $iResult;
	}
	
	/**
	 * 玩家游戏金币输赢清零
	 * -1:失败,0:成功
	 */
	function setUserGameDataTotalMoney($RoleID,$KindID,$RoomType)
	{
		$iResult = -1;
		$params = array(array($RoleID, SQLSRV_PARAM_IN),	
						array($KindID, SQLSRV_PARAM_IN),
						array($RoomType, SQLSRV_PARAM_IN)						
						);
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserGameData_Update", $params);
		if(!empty($arrResult)) $iResult = $arrResult['iResult'];
		return $iResult;
	}
	
	/**
	 * 返回玩家游戏房间ID
	 */
	function getUserGameWealthList($RoleID)
	{
		$params = array(array($RoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAllAssoc("Proc_UserGameWealth_SelectInGameList", $params);
		if(empty($arrResult)) $arrResult = null;
		return $arrResult;
	}
	/**
	 * 删除玩家数据信息
	 */
	function DelPlayer($RoleID)
	{
		$params = array(array($RoleID, SQLSRV_PARAM_IN));	
		$this->objUserDataDB->fetchAssoc("Proc_UserData_Delete", $params);		
	}
	/**
	 * 返回玩家是否存有金币
	 */
	function getUserWealthByRoleID($RoleID)
	{
		$params = array(array($RoleID, SQLSRV_PARAM_IN));	
		$arrResult = $this->objUserDataDB->fetchAssoc("Proc_UserWealth_SelectByRoleID", $params);
		if(empty($arrResult)) $arrResult = null;
		return $arrResult;
	}
	
}
?>