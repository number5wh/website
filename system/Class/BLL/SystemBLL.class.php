<?php
require ROOT_PATH . 'Class/DAL/SystemDAL.class.php';
class SystemBLL
{
	private $objSystemDAL = NULL;
	public function __construct()
    {
        $this->objSystemDAL = new SystemDAL();
    }
    
	
	/**
	 * 修改操作
	 * @CaseSerial		BIGINT,		--案件编号,可重复
	 * @RoleID			INT,		--角色ID
	 * @OperationType	TINYINT,	--操作类型,1:锁定角色,2:解除锁定,3:主机解绑,4:重置密码,5:处理财富
								--         6:冻结财富,7:处罚角色,8:解除处罚,9:黄钻服务,10:补发月礼包
	 * @iNumber		BIGINT,		--期限(天数),财富冻结数量
	 * @iNumber1		BIGINT,		--龙币数量
	 * @Reason			VARCHAR(64),--处罚原因
	 * @Remarks		VARCHAR(64),--备注(原因)
	 * @Requirement	VARCHAR(8),	--解封要求
	 * @Status			TINYINT,	--状态，封号/主机解绑/重置密码类型
	 * @ShowType		TINYINT,	--1:操作审核中显示,2:授权中心显示
	 * @SysUserName		INT			--操作人
	 * @Step			SMALLINT	--流程状态,默认(“修改操作”触发):0,同意:1,拒绝:-1,2:申请返还(冻结财富有效),-2:不需审核
	 */
	function editCaseProcess($CaseSerial, $RoleID, $OperationType, $iNumber, $iNumber1, $Reason, $Remarks, $Requirement, $Status, $ShowType, $SysUserName, $Step)
	{	
		return $this->objSystemDAL->editCaseProcess($CaseSerial, $RoleID, $OperationType, $iNumber, $iNumber1, $Reason, $Remarks, $Requirement, $Status, $ShowType, $SysUserName, $Step);
	}
	
	/**
	 * 修改操作——插入操作审核
	 * @RoleID			INT,		--角色ID
	 * @LoginID	INT,			--玩家编号
	 * @LoginName	VARCHAR(16),	--角色名
	 * @OperationType	TINYINT,	--操作类型,1:重置银行密码,2:重置背包密码,3:主机解绑,4:解除锁定,5:解除处罚
									--        6:返回金币,7:补发金币,8:补发龙币,9:补发积分,10:补发道具,
									--		  11:补发黄钻,12:补发月礼包,13:解除银行冻结,14:解除背包冻结
     * @ExtendID		INT,		--道具序号/游戏编号，补发道具和补发积分用，其他传0，
	 * @iNumber		BIGINT,		--期限(天数),财富冻结数量(金币)
	 * @Payment		VARCHAR(50),	--支出账户，默认传空值表示918系统账户，其他:可以是其他玩家账号
	 * @Reason			VARCHAR(256),--处罚原因
	 * @Remarks		VARCHAR(256),--备注(原因)
	 * @Requirement	TINYINT,	--解封要求,0:无，1:财富清零，2:积分清零
	 * @ShowType		TINYINT,	--1:操作审核中显示,2:授权中心显示
	 * @SysUserName	VARCHAR(16),--操作人
	 * @FID			INT			--财富冻结自增ID，返回金币用，其他传0
	 */
	public function addAuthProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$ExtendID,$iNumber,$Payment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID)
	{
		return $this->objSystemDAL->addAuthProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$ExtendID,$iNumber,$Payment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID);
	}


    public function addWeathBackProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$iNumber,$Payment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID)
    {
        return $this->objSystemDAL->addWeathBackProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$iNumber,$Payment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID);
    }



	/**
	 * 修改操作——插入不需审核记录
	 * @CaseSerial		VARCHAR(256),--案件编号(多个请用逗号隔开)
	 * @RoleID			INT,		--角色ID
	 * @LoginID	INT,			--玩家编号
	 * @LoginName	VARCHAR(16),	--角色名
	 * @OperationType	TINYINT,	--操作类型,1:冻结金币,2:冻结龙币,3:锁定角色,4:处罚角色,5:解除银行冻结,6:解除背包冻结
	 * @iNumber		BIGINT,		--财富冻结数量(金币)
	 * @Reason			VARCHAR(256),--冻结原因
	 * @Remarks		VARCHAR(256),--冻结备注
	 * @Requirement	VARCHAR(16),--解封要求
	 * @SysUserName	VARCHAR(16)--操作人
	 */
	function addCaseOperateUser($CaseSerial, $iRoleID, $iLoginID, $strLoginName, $iOperationType, $iNumber, $strReason, $strRemarks, $strRequirement, $strSysUserName)
	{
		return $this->objSystemDAL->addCaseOperateUser($CaseSerial, $iRoleID, $iLoginID, $strLoginName, $iOperationType, $iNumber, $strReason, $strRemarks, $strRequirement, $strSysUserName);
	}
	
	/**
	 * 取得修改操作的审核记录
	 * @RoleID			BIGINT,		--角色ID
	 * @OperationType	TINYINT --操作类型,1:重置银行密码,2:重置背包密码,3:主机解绑,4:解除锁定,5:解除处罚,11:补发黄钻,12:补发月礼包 23微信解绑
	 */
	function getCaseAuthProcess($iRoleID, $OperationType, $iType=0)
	{
		return $this->objSystemDAL->getCaseAuthProcess($iRoleID, $OperationType, $iType);
	}

	/**
	 * 取得修改操作的非审核记录
	 * @RoleID			BIGINT,		--角色ID
	 * @OperationType	TINYINT --操作类型,1:冻结金币,2:冻结龙币,3:锁定角色,4:处罚角色,5:解除银行冻结,6:解除背包冻结
	 */
	function getCaseOperateUser($iRoleID, $OperationType)
	{
		return $this->objSystemDAL->getCaseOperateUser($iRoleID, $OperationType);	
	}
	
	/**
	 * 案件录入
	 * @author blj
	 * @param $iCaseType 	案件类型
	 * @param $iLoginId		玩家编号
	 * @param $strLoginName	玩家昵称
	 * @param $strCardId	玩家身份证
	 * @param $happenTime	案发时间
	 * @param $iAmount		涉案数量--金币
	 * @param $iSysUserId	操作人ID
	 */
	public function addCaseInfo($iCaseType,$iLoginId,$iRoleID,$strLoginName,$strRealName,$strCardId,$strMobile,$strTelephone,$happenTime,$iAmount,$strDescription,$strSysUserName)
	{
		return $this->objSystemDAL->addCaseInfo($iCaseType,$iLoginId,$iRoleID,$strLoginName,$strRealName,$strCardId,$strMobile,$strTelephone,$happenTime,$iAmount,$strDescription,$strSysUserName);
	}
	
	/**
	 * 添加案件操作日志
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strNote
	 */
	public function addCaseOperateLog($iCaseSerial,$strNote,$strSysUserName)
	{
		return $this->objSystemDAL->addCaseOperateLog($iCaseSerial,$strNote,$strSysUserName);
	}
	
	/**
	 * 根据案件ID获取案件详细信息
	 * @author blj
	 * @param $caseId
	 */
	public function getCaseInfoByID($iCaseSerial)
	{
		return $this->objSystemDAL->getCaseInfoByID($iCaseSerial);
	}
	
	/**
	 * 获取案件操作日志
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getOperateLogs($iCaseSerial)
	{
		return $this->objSystemDAL->getOperateLogs($iCaseSerial);
	}
	
	/**
	 * 更新案件状态
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iStep
	 */
	public function updateCaseStatus($iCaseSerial,$iStep)
	{
		return $this->objSystemDAL->updateCaseStatus($iCaseSerial,$iStep);
	}
	
	/**
	 * 添加案件进展
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strProgress
	 */
	public function addCaseProgress($iCaseSerial,$strProgress,$strSysUserName)
	{
		return $this->objSystemDAL->addCaseProgress($iCaseSerial,$strProgress,$strSysUserName);
	}
	
	/**
	 * 读取案件进展
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseProgress($iCaseSerial)
	{
		return $this->objSystemDAL->getCaseProgress($iCaseSerial);
	}
	
	/**
	 * 更新案件信息，添加追回金额
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iAmount
	 */
	public function updateCaseReturnAmount($iCaseSerial,$iAmount)
	{
		return $this->objSystemDAL->updateCaseReturnAmount($iCaseSerial,$iAmount);
	}
	
	/**
	 * 更新案件信息，添加处罚内容
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strDecision
	 */
	public function updateCaseDecision($iCaseSerial,$strDecision)
	{
		return $this->objSystemDAL->updateCaseDecision($iCaseSerial,$strDecision);
	}
	
	/**
	 * 更新案件信息，添加案件备注信息
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strRemark
	 */
	public function updateCaseRemark($iCaseSerial,$strRemark)
	{
		return $this->objSystemDAL->updateCaseRemark($iCaseSerial,$strRemark);
	}
	
	/**
	 * 更新案件信息-案件描述
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strCaseIntro
	 */
	public function updateCaseIntro($iCaseSerial,$strCaseIntro)
	{
		return $this->objSystemDAL->updateCaseIntro($iCaseSerial,$strCaseIntro);
	}
		
	/**
	 * 读取案件-附件信息
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseFiles($iCaseSerial,$flag)
	{
		return $this->objSystemDAL->getCaseFiles($iCaseSerial,$flag);
	}
	
	/**
	 * 添加案件-附件
	 * @param $iCaseSerial
	 * @param $strFileName
	 * @param $strFilePath
	 * @param $iServerID
	 * @param $strSysUserName
	 */
	public function addCaseFiles($iCaseSerial,$strFileName,$strFilePath,$iServerID,$strSysUserName)
	{
		return $this->objSystemDAL->addCaseFiles($iCaseSerial,$strFileName,$strFilePath,$iServerID,$strSysUserName);
	}
	
	/**
	 * 删除案件附件
	 * @author blj
	 * @param $fileId
	 */
	public function deleteFile($fileId)
	{
		return $this->objSystemDAL->deleteFile($fileId);
	}
	
	/**
	 * 添加案件涉案人员
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iRoleId
	 * @param $iLoginId
	 * @param $strLoginName
	 */
	public function addCaseSuspect($iCaseSerial,$iRoleId,$iLoginId,$strLoginName)
	{
		return $this->objSystemDAL->addCaseSuspect($iCaseSerial,$iRoleId,$iLoginId,$strLoginName);
	}
	
	/**
	 * 删除案件涉案人员
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iRoleId
	 */
	public function deleteCaseSuspect($iCaseSerial,$iRoleId)
	{
		return $this->objSystemDAL->deleteCaseSuspect($iCaseSerial,$iRoleId);
	}
	
	/**
	 * 读取案件-涉案人员列表
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseSuspectList($iCaseSerial,$flag)
	{
		return $this->objSystemDAL->getCaseSuspectList($iCaseSerial,$flag);
	}
	
	/**
	 * 获取修改操作的信息
	 * @RoleID			BIGINT,		--角色ID
	 * @OperationType	VARCHAR(64) --操作类型,1:锁定角色,2:解除锁定,3:主机解绑,4:重置密码,5:处理财富,
								    --	      6:冻结财富,7:处罚角色,8:解除处罚,9:黄钻服务,10:补发月礼包
	 */
	function getCaseProcessInfo($RoleID, $OperationType)
	{
		return $this->objSystemDAL->getCaseProcessInfo($RoleID, $OperationType);
	}
	
	/**
	 * 操作审核
	 * @author blj
	 * @param $iPId
	 * @param $iStep
	 * @param $strRemark
	 * @param $strSysUserName
	 */
	public function OperateVerify($iPId, $iStep, $strRemark, $strSysUserName)
	{
		return $this->objSystemDAL->OperateVerify($iPId, $iStep, $strRemark, $strSysUserName);
	}



    public function WeathBackOperateVerify($iPId, $iStep, $strRemark, $strSysUserName)
    {
        return $this->objSystemDAL->WeathBackOperateVerify($iPId, $iStep, $strRemark, $strSysUserName);
    }
	
	/**
	 * 添加财富补偿申请附件
	 * @author blj
	 * @param $iPID
	 * @param $strFilePath
	 * @param $iServerID
	 */
	public function addAuthProcessFile($iPID,$strFilePath,$iServerID)
	{
		return $this->objSystemDAL->addAuthProcessFile($iPID,$strFilePath,$iServerID);
	}
	
	/**
	 * 读取财富补偿申请附件
	 * @param $iPID
	 */
	public function getAuthProcessFile($iPID)
	{
		return $this->objSystemDAL->getAuthProcessFile($iPID);
	}
	
	/**
	 * 申请财富返还/拒绝申请财富返还--更新操作用户记录表
	 * @author blj
	 * @param $iFId
	 * @param $iNumber
	 * @param $type	1:申请财富返还，2：拒绝申请财富返还
	 */
	public function updateCaseOperateUser($iFId,$iNumber,$type)
	{
		return $this->objSystemDAL->updateCaseOperateUser($iFId,$iNumber,$type);
	}
	
	/**
	 * 读取操作用户记录相关案件编号
	 * @author blj
	 * @param $iFId
	 */
	public function getCaseOperateUserSn($iFId)
	{
		return $this->objSystemDAL->getCaseOperateUserSn($iFId);
	}
	
	/**
	 * CaseOperateUserSn表中插入新的案件编号
	 * @param $iFId
	 * @param $CaseSerial
	 */
	function insertCaseOperateUserSn($iFId, $CaseSerial)
	{
		return $this->objSystemDAL->insertCaseOperateUserSn($iFId, $CaseSerial);
	}
	
	/**
	 * 删除CaseOperateUserSn案件编号
	 * @param unknown_type $iFId
	 * @return unknown
	 */
	function delCaseOperateUserSn($iFId, $CaseSerial)
	{
		return $this->objSystemDAL->delCaseOperateUserSn($iFId, $CaseSerial);
	}
	
	/**
	 * 更新审核案件的备注
	 * @PID			INT,	
	 * @Remarks		VARCHAR(256)		--备注原因
	 */
	function updateAuthProcessRemarks($PID, $Remarks)
	{
		return $this->objSystemDAL->updateAuthProcessRemarks($PID, $Remarks);
	}
	
	/**
	 * 更新非审核案件的备注
	 * @param $iFId
	 * @param $Reason
	 */
	function updateCaseOperateUserReason($iFId, $strRemarks)
	{
		return $this->objSystemDAL->updateCaseOperateUserReason($iFId, $strRemarks);
	}
	
	/**
	 * 服务事件编辑
	 * @param $id
	 * @param $evtName
	 * @param $evtDescription
	 * @param $evtSort
	 */
	function addServerEvent($id,$evtName,$evtDescription,$evtSort)
	{
		return $this->objSystemDAL->addServerEvent($id,$evtName,$evtDescription,$evtSort);
	}
	
	/**
	 * 根据ID获取单个服务事件信息
	 * @param $evtId
	 */
	function getServerEventByID($evtId)
	{
		return $this->objSystemDAL->getServerEventByID($evtId);
	}
}
?>