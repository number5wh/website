<?php
require_once __DIR__.'/DALBase.php';

class SetOperationLogsDAL extends DALBase
{
	private $iRoleID = 0;
	public function __construct($iRoleID)
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID,$this->arrConfig['MapType']['SetOperationLogs'],true);
	}
	
	/**
	 * 总记录数
	 * @param $arrParam
	 * @strTableName	VARCHAR(100),--表名前缀,如:T_BankTransLogs_
	 * @StartDate		DATETIME,--开始日期,如:2012-01-01
	 * @EndDate		DATETIME,--结束日期,如:2012-04-01
	 * @strWhere		VARCHAR(200)
	 */
	function getRecordsCount($arrParam)
	{
		$iRecordsCount = 0;
		$params = array(array($arrParam['tableName'], SQLSRV_PARAM_IN),
						array($arrParam['StartDate'], SQLSRV_PARAM_IN),
						array($arrParam['EndDate'], SQLSRV_PARAM_IN),
						array($arrParam['where'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objSetOperationLogsDB->fetchAssoc("Proc_GetRecordsCount", $params);
		if(is_array($arrResult) && count($arrResult)>0)
			$iRecordsCount = $arrResult['RecordsCount'];
		return $iRecordsCount;	
	}
	
	/**
	 * 分页
	 * @param $arrParam
	 * @Columns	VARCHAR(256),	--字段
	 * @TableName	VARCHAR(64),	--表名前缀,如:T_BankTransLogs_
	 * @StartDate	DATETIME,		--开始日期,如:2012-01-01
	 * @EndDate	DATETIME,		--结束日期,如:2012-04-01
	 * @Where		VARCHAR(256),	--条件
	 * @Order		VARCHAR(32),	--排序
	 * @PageSize	INT,			--每页显示记录数
	 * @RowIndex	INT				--当前页显示最大rowid
	 */
	function getPageList($arrParam,$flag)
	{
		$arrResult = NULL;
		if($flag)
			$arrResult = $this->objMemcache->get($this->iRoleID.$arrParam['memName'].$arrParam['iCurPage']);	 
		if(!$arrResult)
		{
			$params = array(array($arrParam['fields'], SQLSRV_PARAM_IN),
							array($arrParam['tableName'], SQLSRV_PARAM_IN),
							array($arrParam['StartDate'], SQLSRV_PARAM_IN),
							array($arrParam['EndDate'], SQLSRV_PARAM_IN),
							array($arrParam['where'], SQLSRV_PARAM_IN),
							array($arrParam['order'], SQLSRV_PARAM_IN),					
							array($arrParam['iCurPage'], SQLSRV_PARAM_IN),						
							array($arrParam['pagesize'], SQLSRV_PARAM_IN)		
							);
			$arrResult = $this->objSetOperationLogsDB->fetchAllAssoc("Proc_GetPages", $params);
			if($flag)
				$this->objMemcache->set($this->iRoleID.$arrParam['memName'].$arrParam['iCurPage'],$arrResult,true,1800);
		}		
		return $arrResult;	
	}
	
	/**
	 * 插入修改操作日志
	 * @RoleID		INT,		--角色ID
	 * @CaseSerial	BIGINT,		--案件编号
	 * @OpType		INT,		--操作类型,0:锁定角色,1:解锁,2:冻结金币,3:处理财富,4:补发黄钻服务期,
								--	       5:主机绑定解绑,6:返还金币,7:补发金币,8:补发龙币,9:补发积分,10: 补发道具,
								--		   11:绑定主机,12:背包密码重置,13:背包密码修改,14:背包功能冻结,15:解除背包冻结,
								--		   16:银行密码重置,17:银行密码修改,18:银行功能冻结,19:解除银行冻结,20:封号,21:解封,
								--		   22:购买金币,23:购买道具,24:丢弃服装,25:增加银行账户,26:银行账户转账,27:系统银行扩容
								--		   28:登陆解绑,29:解除卡房,30:案件中心操作,31:权限设置,32:修改通行证注册手机,33:玩家游戏金币输赢清零,
     *                          --         34:输赢排行清零
	 * @OpContent	VARCHAR(256),--操作内容
	 * @OpResult	SMALLINT,	--操作结果,0:成功,-1:失败
	 * @ClientIP	VARCHAR(32),--IP地址
	 * @OpPlace		TINYINT,	--0:运维后台,1:银行,2:商城,3:背包,4:衣柜,5:黄钻,6:个人中心,7:角色信息,8:官网
	 * @TypeID		TINYINT,		--1:前台,2:后台
	 * @SysUserName	VARCHAR(16),--操作客服
	 * @MachineSerial	VARCHAR(50)	--机器序列
	 */
	function addCaseOperationLogs($iRoleID, $CaseSerial, $OpType, $OpContent, $OpResult, $ClientIP, $OpPlace, $TypeID, $SysUserName, $MachineSerial)
	{
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($CaseSerial, SQLSRV_PARAM_IN),
						array($OpType, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($OpContent), SQLSRV_PARAM_IN),
						array($OpResult, SQLSRV_PARAM_IN),
						array($ClientIP, SQLSRV_PARAM_IN),					
						array($OpPlace, SQLSRV_PARAM_IN),						
						array($TypeID, SQLSRV_PARAM_IN),						
						array($SysUserName, SQLSRV_PARAM_IN),						
						array($MachineSerial, SQLSRV_PARAM_IN)		
						);
		$arrReturns = $this->objSetOperationLogsDB->fetchAssoc("Proc_OperationLogs_Insert", $params);	
		return $arrReturns;
	}
	
	/**
	 * 查询操作日志
	 * @param $iRoleID
	 * @param $CaseSerial
	 */
	public function getOperationLogs($iRoleID, $CaseSerial)
	{
		$params = array(array($CaseSerial, SQLSRV_PARAM_IN),
					    array($iRoleID, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSetOperationLogsDB->fetchAllAssoc("Proc_OperationLogs_Select", $params);		
		return $arrReturns;
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
		$params = array(array($CaseSerial, SQLSRV_PARAM_IN),
						array($this->iRoleID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Reason), SQLSRV_PARAM_IN),
						array($Result, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($LockExpireTime), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Requirement), SQLSRV_PARAM_IN),						
						array($SysUserName, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Remarks), SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN)	
						);
		$arrReturns = $this->objSetOperationLogsDB->fetchAssoc("Proc_LockUserLogs_Insert", $params);		
		return $arrReturns;
	}

}
?>