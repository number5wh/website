<?php
require ROOT_PATH . 'Class/DAL/SetOperationLogsDAL.class.php';
class SetOperationLogsBLL
{
	private $objSetOperationLogsDAL = NULL;
	public function __construct($iRoleID)
    {
        $this->objSetOperationLogsDAL = new SetOperationLogsDAL($iRoleID);
    }
    
    /**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam)
	{
		return $this->objSetOperationLogsDAL->getRecordsCount($arrParam);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageList($arrParam, $flag=0)
	{
		return $this->objSetOperationLogsDAL->getPageList($arrParam, $flag);
	}
	
	/**
	 * 插入修改操作日志
	 * @RoleID		INT,		--角色ID
	 * @CaseSerial	BIGINT,		--案件编号
	 * @OpType		INT,		--操作类型0:锁定角色,1:解锁,2:冻结金币,3:处理财富,4:补发黄钻服务期,
								--		   5:主机绑定解绑,6:返还金币,7:补发金币,8:补发龙币,9:补发积分,10: 补发道具,
								--		   11:绑定主机,12:背包密码重置,13:背包密码修改,14:背包功能冻结,15:解除背包冻结,
								--		   16:银行密码重置,17:银行密码修改,18:银行功能冻结,19:解除银行冻结,20:封号,21:解封 ，23:微信绑定解绑
	 * @OpContent	VARCHAR(256),--操作内容
	 * @OpResult	SMALLINT,	--操作结果,0:成功,-1:失败
	 * @ClientIP	VARCHAR(32),--IP地址
	 * @OpPlace	TINYINT,	--0:运维后台,1:银行,2:商城,3:背包,4:衣柜,5:黄钻,6:个人中心,7:角色信息,8:官网
	 * @TypeID		TINYINT,		--1:前台,2:后台
	 * @SysUserName	VARCHAR(16),--操作客服
	 * @MachineSerial	VARCHAR(50)	--机器序列
	 */
	function addCaseOperationLogs($iRoleID, $CaseSerial, $OpType, $OpContent, $OpResult, $ClientIP, $OpPlace, $TypeID, $SysUserName, $MachineSerial)
	{
		return $this->objSetOperationLogsDAL->addCaseOperationLogs($iRoleID, $CaseSerial, $OpType, $OpContent, $OpResult, $ClientIP, $OpPlace, $TypeID, $SysUserName, $MachineSerial);
	}
	
	/**
	 * 查询操作日志
	 * @param $iRoleID
	 * @param $CaseSerial
	 */
	public function getOperationLogs($iRoleID, $CaseSerial)
	{
		return $this->objSetOperationLogsDAL->getOperationLogs($iRoleID, $CaseSerial);
	}
	
	/**
	 * 添加锁定/封号日志
	 * @CaseSerial		BIGINT,			--案件编号
	 * @RoleID			INT,			--角色ID
	 * @Reason			VARCHAR(256),	--原因
	 * @Result			TINYINT,	--处理结果,0:锁定,1:申请解锁,2:已解锁, 3:封号,4:申请解封,5:已解封,6:拒绝
	 * @LockExpireTime	VARCHAR(16),	--锁号/封号期限
	 * @Requirement	VARCHAR(16),	--解封要求
	 * @SysUserName	VARCHAR(16),	--操作客服
	 * @Remarks		VARCHAR(256),	--备注
	 * @ClientIP		VARCHAR(32)		--操作时的IP
	 */
	function addLockUserLogs($CaseSerial, $Reason, $Result, $LockExpireTime, $Requirement, $SysUserName, $Remarks)
	{
		return $this->objSetOperationLogsDAL->addLockUserLogs($CaseSerial, $Reason, $Result, $LockExpireTime, $Requirement, $SysUserName, $Remarks);
	}
	
}
?>