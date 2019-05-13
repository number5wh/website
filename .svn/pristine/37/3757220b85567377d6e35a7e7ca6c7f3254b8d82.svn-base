<?php
require ROOT_PATH . 'Class/DAL/UserDataDAL.class.php';
class UserDataBLL
{
	private $objUserDataDAL = NULL;
	public function __construct($iRoleID)
	{
		 $this->objUserDataDAL = new UserDataDAL($iRoleID);
	}  
	
	/**
	 * 获取用户体力值
	 */
	function getUserPowerInfo()
	{
		return $this->objUserDataDAL->getUserPowerInfo();	
	}

	/**
	 * 获取用户的游戏种类ID,金币
	 */
	function getUserGameWealth()
	{
		return $this->objUserDataDAL->getUserGameWealth();
	}	
	
	/**
	 * 处理财富——银行存款
	 * @iKindID	INT,
	 * @iMoney		BIGINT,	--金币
	 * @iObjType	TINYINT	--存款转出对象类型,0:游戏,1:背包
	 */
	function editUserBankCunKuan($iKindID, $iMoney, $iObjType)
	{
		return $this->objUserDataDAL->editUserBankCunKuan($iKindID, $iMoney, $iObjType);
	}
	
	/**
	 * 获取背包中金币的值, 背包格总数
	 */
	function getMyKnapsackMoney()
	{
		return $this->objUserDataDAL->getMyKnapsackMoney();
	}
	
	/**
	 * 获取银行中金币的值
	 */
	function getUserBankMoney()
	{
		return $this->objUserDataDAL->getUserBankMoney();
	}
	
	/**
	 * 获取用户的月礼包信息
	 */
	function getMonthGiftPackage()
	{
		return $this->objUserDataDAL->getMonthGiftPackage();
	}
	
	/**
	 * 返回已使用的格子数 space 1:背包  2:衣柜
	 */
	function getUsedMyKnapsacksByRoleID($Space)
	{
		return $this->objUserDataDAL->getUsedMyKnapsacksByRoleID($Space);
	}
	
	/**
	 * 返回玩过的游戏种类ID
	 */
	function getUserGameData($strWhere)
	{
		return $this->objUserDataDAL->getUserGameData($strWhere);
	}
	
	/**
	 * 冻结财富，修改冻结的金币数量和龙币数量
	 * @iMoney		BIGINT,		--冻结金额(金币)
	 * @iFwMoney	BIGINT		--冻结金额(龙币)
	 * @iStatus		TINYINT		--0:冻结,1:返还
	 */
	function editUserBankMoney($iMoney, $iFwMoney, $iStatus)
	{
		return $this->objUserDataDAL->editUserBankMoney($iMoney, $iFwMoney, $iStatus);
	}
	
	/**
	 * 检查用户锁定状态（T_UserBank,T_MyKnapsack,T_MyArmoire,T_UserStageProperty）
	 */
	function checkUserLockInfo()
	{
		return $this->objUserDataDAL->checkUserLockInfo();
	}
	
	/**
	 * 锁住/解锁角色仓库数据
	 * @param $Locked
	 */
	function editUserDataLocked($Locked)
	{
		return $this->objUserDataDAL->editUserDataLocked($Locked);
	}
	
	/**
	 * 重置背包密码
	 * @param $strPwd
	 */
	function updateMyKnapsackPwd($strPwd)
	{
		return $this->objUserDataDAL->updateMyKnapsackPwd($strPwd);
	}
	
	/**
	 * 重置银行密码
	 * @param $strPwd
	 */
	function updateBankPwd($strPwd)
	{
		return $this->objUserDataDAL->updateBankPwd($strPwd);
	}
	
	/**
	 * 补发道具到用户背包
	 * @author blj
	 * @param $spId
	 */
	public function returnBackStageProperty($spId)
	{
		return $this->objUserDataDAL->returnBackStageProperty($spId);
	}
	
	/**
	 * 补发积分到用户游戏中
	 * @author blj
	 * @param $kindId
	 * @param $num
	 */
	public function returnBackGameScore($kindId,$num)
	{
		return $this->objUserDataDAL->returnBackGameScore($kindId,$num);
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
		return $this->objUserDataDAL->updateUserBankStatus($Status,$Minites);
	}
	
	/**
	 * 解冻用户背包
	 * @param $Status	SMALLINT,--锁定状态,0:正常,1:丢弃道具时输入密码操作锁定状态,2:修改密码操作淘宝状态
	 * @param $Minites	@Minites	SMALLINT --冻结有效时间,单位:分钟	
	 * @Return: 0:成功,-1:失败
	 */
	function updateMyKnapsackStatus($Status,$Minites)
	{
		return $this->objUserDataDAL->updateMyKnapsackStatus($Status,$Minites);
	}
	
	/**
	 * 我的背包和银行是否被锁定
	 */
	function checkBankMyKnapsackStatus()
	{
		return $this->objUserDataDAL->checkBankMyKnapsackStatus();
	}
	
	/**
	 * 检查我的背包和银行是否设置密码
	 */
	function checkBankMyKnapsackPWD()
	{
		return $this->objUserDataDAL->checkBankMyKnapsackPWD();
	}
	
	/**
	 * 获取用户游戏中的所有财富
	 */
	function getUserGameWealthAllMoney()
	{
		return $this->objUserDataDAL->getUserGameWealthAllMoney();
	}
	/**
	 * 添加角色银行，背包，衣柜
	 */
	function addUserBox()
	{
		return $this->objUserDataDAL->addUserBox();
	}
	/**
	 * 更新玩家在房间状态
	 */
	function updateInGame($iRoleID,$KindID)
	{
		return $this->objUserDataDAL->updateInGame($iRoleID,$KindID);	
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
		return $this->objUserDataDAL->getRechargeOrderCount($StartTime,$EndTime);
	}
	/**
	 * 返回玩家金币存货总数
	 * @author xlj
	 * @return array
	 */
	public function getUserWealth()
	{
		return $this->objUserDataDAL->getUserWealth();
	}
	/**
	 * 玩家是否游戏中
	 */
	public function getUserWealthInGame($RoleID)
	{
		return $this->objUserDataDAL->getUserWealthInGame($RoleID);
	}
	/**
	 * 读取充值记录信息
	 */
	function getRechargeOrderInfo($RoleID,$OrderSerial)
	{
		return $this->objUserDataDAL->getRechargeOrderInfo($RoleID,$OrderSerial);
	}
	/**
	 * 补发金币充值
	 */
	function setUserBankRecharge($OrderSerial,$PayMoney,$PayID,$Amount,$payMethod)
	{
		return $this->objUserDataDAL->setUserBankRecharge($OrderSerial,$PayMoney,$PayID,$Amount,$payMethod);
	}
	/**
	 * 补发金币充值回滚
	 */
	function setUserBankRollback($OrderSerial)
	{
		return $this->objUserDataDAL->setUserBankRollback($OrderSerial);
	}
	/**
	 * 玩家游戏金币输赢清零
	 * -1:失败,0:成功
	 */
	function setUserGameDataTotalMoney($RoleID,$KindID,$RoomType)
	{
		return $this->objUserDataDAL->setUserGameDataTotalMoney($RoleID,$KindID,$RoomType);
	}
	/**
	 * 返回玩家游戏房间ID
	 */
	function getUserGameWealthList($RoleID)
	{
		return $this->objUserDataDAL->getUserGameWealthList($RoleID);
	}
	/**
	 * 删除玩家数据信息
	 */
	function DelPlayer($RoleID)
	{
		return $this->objUserDataDAL->DelPlayer($RoleID);
	}
	/**
	 * 返回玩家是否存有金币
	 */
	function getUserWealthByRoleID($RoleID)
	{
		return $this->objUserDataDAL->getUserWealthByRoleID($RoleID);
	}
}
?>