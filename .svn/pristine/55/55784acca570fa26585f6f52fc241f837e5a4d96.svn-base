<?php
require ROOT_PATH . 'Class/DAL/UserDAL.class.php';
class UserBLL
{
	private $objUserDAL = NULL;
	function __construct($iRoleID=0)
	{
		$this->objUserDAL = new UserDAL($iRoleID);
	}
	
	/**
	 * 返回角色信息
	 */
	function getRoleInfo($iRoleID='')
	{
		return $this->objUserDAL->getRoleInfo($iRoleID);
	}
	/**
	 * 获取角色信息
	 * @param $TypeID 搜索条件类型,0:按角色ID,1:按游戏编号LoginID
	 */
	function getRole($TypeID,$iRoleID=0)
	{
		return $this->objUserDAL->getRole($TypeID,$iRoleID);
	}
	/**
	 * 获取角色信息
	 * @param $TypeID 搜索条件类型,0:按角色ID,1:按游戏编号LoginID
	 */
	function getRoleList($TypeID,$iRoleID=0)
	{
		return $this->objUserDAL->getRoleList($TypeID,$iRoleID);
	}
	/**
	 * 主机解除绑定
	 */
	function UpdateMoorMachine()
	{
		return $this->objUserDAL->UpdateMoorMachine();
	}	
	
	/**
	 * 判断角色是否被锁定
	 */
	function checkUserLockStatus()
	{
		return $this->objUserDAL->checkUserLockStatus();
	}
	
	/**
	 * 锁定/解除角色
	 * @LockDays	SMALLINT,	--锁定天数
	 * @Locked		TINYINT		--状态,0:正常,1:锁定
	 */
	function LockUserLockedStatus($LockDays, $Locked)
	{
		return $this->objUserDAL->LockUserLockedStatus($LockDays, $Locked);
	}
	
	/**
	 * 封号/解封
	 * @Locked TINYINT,--状态,0:正常,1:封号
	 * @Intro	VARCHAR(256)--封号原因
	 */
	function blockUserLockedStatus($Locked, $Intro)
	{
		return $this->objUserDAL->blockUserLockedStatus($Locked, $Intro);
	}
	
	/**
	 * 补发黄钻服务
	 * @author blj
	 * @param $dayNum
	 */
	public function ReturnBackUserVip($dayNum)
	{
		return $this->objUserDAL->ReturnBackUserVip($dayNum);
	}
	/**
	 * 添加玩家
	 * @author xlj
	 * @param $arrParams
	 */
	public function addPlayer($arrParams)
	{
		return $this->objUserDAL->addPlayer($arrParams);
	}
	/**
	 * 添加玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function addUserPrivilege($arrParams)
	{
		return $this->objUserDAL->addUserPrivilege($arrParams);
	}
	/**
	 * 删除玩家
	 * @author xlj
	 * @param $arrParams
	 */
	public function deletePlayer()
	{
		return $this->objUserDAL->deletePlayer();
	}
	/**
	 * 读取玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function getUserPrivilegeInfo()
	{
		return $this->objUserDAL->getUserPrivilegeInfo();
	}
	/**
	 * 读取角色信息
	 * @author xlj
	 * @param $LoginName
	 */
	public function getUserInfo($LoginName)
	{
		return $this->objUserDAL->getUserInfo($LoginName);
	}
	/**
	 * 删除玩家权限
	 * @author xlj
	 * @param $arrParams
	 */
	public function delUserPrivilege($Passport)
	{
		return $this->objUserDAL->delUserPrivilege($Passport);
	}
	/**
	 * 设置/取消IP段锁定控制
	 * @author 
	 * @param $arrParams
	 * 0:成功,-1:失败
	 */
	public function setIpLocked($TitleID)
	{
		return $this->objUserDAL->setIpLocked($TitleID);
	}



	public function getpayWay($RoleID){
        return $this->objUserDAL->getpayWay($RoleID);
    }

    public function setpayWay($arrPayWay){
        return $this->objUserDAL->setpayWay($arrPayWay);
    }
}
?>