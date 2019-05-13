<?php
require_once __DIR__.'/DALBase.php';

class SystemDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['System'],true);
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
		$objUserDAL = new UserDAL($RoleID);
		$arrRoleInfo = $objUserDAL->getRoleInfo($RoleID);
		$arrReturns = null;
		$params = array(array($CaseSerial, SQLSRV_PARAM_IN),
						array($RoleID, SQLSRV_PARAM_IN),
						array($arrRoleInfo['LoginID'], SQLSRV_PARAM_IN),
						array($arrRoleInfo['LoginName'], SQLSRV_PARAM_IN),
						array($OperationType, SQLSRV_PARAM_IN),
						array($iNumber, SQLSRV_PARAM_IN),
						array($iNumber1, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Reason), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Remarks), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Requirement), SQLSRV_PARAM_IN),
						array($Status, SQLSRV_PARAM_IN),
						array($ShowType, SQLSRV_PARAM_IN),
						array($SysUserName, SQLSRV_PARAM_IN),
						array($Step, SQLSRV_PARAM_IN)
					   );
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseProcess_Insert", $params);
		return $arrReturns;
	}

	/**
	 * 修改操作——插入操作审核
	 * @RoleID			INT,		--角色ID
	 * @LoginID	INT,			--玩家编号
	 * @LoginName	VARCHAR(16),	--角色名
	 * @OperationType	TINYINT,	--操作类型,1:重置银行密码,2:重置背包密码,3:主机解绑,4:解除锁定,5:解除处罚
									--        6:返回金币,7:补发金币,8:补发龙币,9:补发积分,10:补发道具,
									--		  11:补发黄钻,12:补发月礼包,13:解除银行冻结,14:解除背包冻结,15黄钻回收
     * @ExtendID	INT,		--道具序号/游戏编号，补发道具和补发积分用，其他传0，
	 * @iNumber		BIGINT,		--期限(天数),财富冻结数量(金币)
	 * @Payment		VARCHAR(50),	--支出账户，默认传空值表示918系统账户，其他:可以是其他玩家账号
	 * @Reason		VARCHAR(256),--处罚原因
	 * @Remarks		VARCHAR(256),--备注(原因)
	 * @Requirement	TINYINT,	--解封要求,0:无，1:财富清零，2:积分清零
	 * @ShowType	TINYINT,	--1:操作审核中显示,2:授权中心显示
	 * @SysUserName	VARCHAR(16),--操作人
	 * @FID			INT			--财富冻结自增ID，返回金币用，其他传0
	 */
	public function addAuthProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$ExtendID,$iNumber,$strPayment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID)
	{
		/*if(!$iLoginID && !$strLoginName){
			$objUserDAL = new UserDAL($iRoleID);
			$arrRoleInfo = $objUserDAL->getRoleInfo($iRoleID);
			$iLoginID = $arrRoleInfo['LoginID'];
			$strLoginName = $arrRoleInfo['LoginName'];
		}*/
        $iLoginID = $iRoleID;
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($iLoginID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strLoginName), SQLSRV_PARAM_IN),
						array($iOperationType, SQLSRV_PARAM_IN),
						array($ExtendID, SQLSRV_PARAM_IN),
						array($iNumber, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strPayment), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strReason), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRemarks), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($iRequirement), SQLSRV_PARAM_IN),
						array($ShowType, SQLSRV_PARAM_IN),
						array($SysUserName, SQLSRV_PARAM_IN),
						array($iFID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_AuthProcess_Insert", $params);
		return $arrReturns;
	}



    public function addWeathBackProcess($iRoleID,$iLoginID,$strLoginName,$iOperationType,$iNumber,$strPayment,$strReason,$strRemarks,$iRequirement,$ShowType,$SysUserName,$iFID)
    {
        /*if(!$iLoginID && !$strLoginName){
            $objUserDAL = new UserDAL($iRoleID);
            $arrRoleInfo = $objUserDAL->getRoleInfo($iRoleID);
            $iLoginID = $arrRoleInfo['LoginID'];
            $strLoginName = $arrRoleInfo['LoginName'];
        }*/
        $iLoginID = $iRoleID;
        $params = array(array($iRoleID, SQLSRV_PARAM_IN),
            array($iLoginID, SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($strLoginName), SQLSRV_PARAM_IN),
            array($iOperationType, SQLSRV_PARAM_IN),
            array($iNumber, SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($strPayment), SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($strReason), SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($strRemarks), SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($iRequirement), SQLSRV_PARAM_IN),
            array($ShowType, SQLSRV_PARAM_IN),
            array($SysUserName, SQLSRV_PARAM_IN),
            array($iFID, SQLSRV_PARAM_IN));
        $arrReturns = $this->objSystemDB->fetchAssoc("Proc_WeathBackProcess_Insert", $params);
        return $arrReturns;
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
		if(!$iLoginID && !$strLoginName){
			$objUserDAL = new UserDAL($iRoleID);
			$arrRoleInfo = $objUserDAL->getRoleInfo($iRoleID);
			$iLoginID = $arrRoleInfo['LoginID'];
			$strLoginName = $arrRoleInfo['LoginName'];
		}
		$params = array(array($CaseSerial, SQLSRV_PARAM_IN),
						array($iRoleID, SQLSRV_PARAM_IN),
						array($iLoginID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strLoginName), SQLSRV_PARAM_IN),
						array($iOperationType, SQLSRV_PARAM_IN),
						array($iNumber, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strReason), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRemarks), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRequirement), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseOperateUser_Insert", $params);
		return $arrReturns;
	}
	
	/**
	 * 取得修改操作的审核记录
	 * @RoleID			BIGINT,	--角色ID
	 * @OperationType	TINYINT --操作类型,1:重置银行密码,2:重置背包密码,3:主机解绑,4:解除锁定,5:解除处罚,11:补发黄钻,12:补发月礼包
	 * @iType 			TINYINT --搜索类型,0:根据RoleID和OperationType搜索，1:根据FID搜索
	 */
	function getCaseAuthProcess($iRoleID, $OperationType, $iType)
	{
		$arrResult = null;
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),						
						array($OperationType, SQLSRV_PARAM_IN),						
						array($iType, SQLSRV_PARAM_IN));
		$arrReturn = $this->objSystemDB->fetchALLAssoc("Proc_AuthProcess_Select", $params);
		if(is_array($arrReturn) && count($arrReturn)>0)
		{
			$i=0;
			foreach ($arrReturn as $v)
			{
				$arrReturn[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);
				$arrReturn[$i]['Remarks'] = Utility::gb2312ToUtf8($v['Remarks']);
				$arrReturn[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);
				$arrReturn[$i]['SysUserName'] = Utility::gb2312ToUtf8($v['SysUserName']);
				$arrReturn[$i]['Checker'] = Utility::gb2312ToUtf8($v['Checker']);
				$arrReturn[$i]['CheckRemarks'] = Utility::gb2312ToUtf8($v['CheckRemarks']);
				$i++;
			}
			
			$arrResult = $arrReturn;
		}
		return $arrResult;
	}
	
	/**
	 * 取得修改操作的无需审核记录
	 * @RoleID			BIGINT,	--角色ID
	 * @OperationType	TINYINT --操作类型,1:冻结金币,2:冻结龙币,3:锁定角色,4:处罚角色,5:解除银行冻结,6:解除背包冻结
	 */
	function getCaseOperateUser($iRoleID, $OperationType)
	{
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),						
						array($OperationType, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_CaseOperateUser_Select", $params);
		if($arrReturns && count($arrReturns)>0){
			$i=0;
			foreach($arrReturns as $v){
				$arrReturns[$i]['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);
				$arrReturns[$i]['Requirement'] = Utility::gb2312ToUtf8($v['Requirement']);
	    		$arrReturns[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);
	    		$arrReturns[$i]['Remarks'] = Utility::gb2312ToUtf8($v['Remarks']);
	    		$i++;
			}			
		}
		return $arrReturns;
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
	 */
	public function addCaseInfo($iCaseType,$iLoginId,$iRoleID,$strLoginName,$strRealName,$strCardId,$strMobile,$strTelephone,$happenTime,$iAmount,$strDescription,$strSysUserName)
	{
		$params = array(array($iCaseType, SQLSRV_PARAM_IN),
						array($iLoginId, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strLoginName), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRealName), SQLSRV_PARAM_IN),
						array($strCardId, SQLSRV_PARAM_IN),
						array($strMobile, SQLSRV_PARAM_IN),
						array($strTelephone, SQLSRV_PARAM_IN),
						array($happenTime, SQLSRV_PARAM_IN),
						array($iAmount, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strDescription), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN),
						array($iRoleID, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_Insert", $params);
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns;
		}else{
			return array('iResult'=>-99);//数据库返回异常
		}
	}
	
	/**
	 * 添加案件操作日志
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strNote
	 */
	public function addCaseOperateLog($iCaseSerial,$strNote,$strSysUserName)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strNote), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN));


		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseOperationLogs_Insert", $params);

		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}

	
	/**
	 * 根据案件ID获取案件详细信息
	 * @author blj
	 * @param $caseId
	 */
	public function getCaseInfoByID($iCaseSerial)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_Select", $params);		
		if(empty($arrReturns)) $arrReturns=null;
		return $arrReturns;
	}
	
	/**
	 * 获取案件操作日志
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getOperateLogs($iCaseSerial)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_CaseOperationLogs_Select", $params);
		if (is_array($arrReturns) && count($arrReturns) > 0)
		{
			$i = 0 ;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$i]['AddTime'] = date('m-d H:i:s',strtotime($val['AddTime']));
				$arrReturns[$i]['Intro'] = Utility::gb2312ToUtf8($val['Intro']);
				$arrReturns[$i]['SysUserName'] = Utility::gb2312ToUtf8($val['SysUserName']);
				$i++;
			}
		}else{
			$arrReturns = null;
		}
		return $arrReturns;
	}
	
	/**
	 * 更新案件状态
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iStep
	 */
	public function updateCaseStatus($iCaseSerial,$iStep)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array($iStep, SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_UpdateStep", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 添加案件进展
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strProgress
	 */
	public function addCaseProgress($iCaseSerial,$strProgress,$strSysUserName)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strProgress), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseProgress_Insert", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 读取案件进展
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseProgress($iCaseSerial)
	{
		$strProgress = '';
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_CaseProgress_Select", $params);
		if (is_array($arrReturns) && count($arrReturns) > 0)
		{
			$i = 0 ;
			foreach ($arrReturns as $val)
			{
				$strProgress .= date('m-d H:i:s',strtotime($val['AddTime'])).'&nbsp;&nbsp;';
				$strProgress .= Utility::gb2312ToUtf8($val['SysUserName']).'&nbsp;&nbsp;';
				$strProgress .= Utility::gb2312ToUtf8($val['CaseIntro']).'<br/>';
				$i++;
			}
		}
		return trim($strProgress,'<br/>');
	}
	
	/**
	 * 更新案件信息，添加追回金额
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iAmount
	 */
	public function updateCaseReturnAmount($iCaseSerial,$iAmount)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array($iAmount, SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_UpdateReturnNumber", $params);
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 更新案件信息，添加处罚内容
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strDecision
	 */
	public function updateCaseDecision($iCaseSerial,$strDecision)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strDecision), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_UpdateDecision", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 更新案件信息，添加案件备注信息
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strRemark
	 */
	public function updateCaseRemark($iCaseSerial,$strRemark)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRemark), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_UpdateRemarks", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 更新案件信息-案件描述
	 * @author blj
	 * @param $iCaseSerial
	 * @param $strCaseIntro
	 */
	public function updateCaseIntro($iCaseSerial,$strCaseIntro)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strCaseIntro), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseInfo_UpdateCaseIntro", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 读取案件-附件信息
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseFiles($iCaseSerial,$flag)
	{
		$strFiles = '';
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_CaseFiles_Select", $params);
		if (is_array($arrReturns) && count($arrReturns) > 0)
		{
			$i = 0 ;
			$masterBLL = new MasterBLL();
			$arrServerList = $masterBLL->getServerList($this->arrConfig['ServerTypeWeb'][8]['TypeID'],0);		
			foreach ($arrReturns as $val)
			{
				$strDomain = '';
				if(is_array($arrServerList) && count($arrServerList)>0)
				{
					foreach ($arrServerList as $v)
					{
						if($val['ServerID']==$v['ServerID'] && !empty($v['ServerIP']))
						{
							$arrServer = explode(',',$v['ServerIP']);
							$strDomain = 'http://'.$arrServer[0];
							break;
						}
					}
				}
				$fileName = Utility::gb2312ToUtf8($val['FileName']);
				$fileUrl = $strDomain.$val['FilePath'];
				$fileID = $val['FileID'];
				$strFiles .= "<a class='blue' href='$fileUrl' target='_blank'>$fileName</a>";
				if($flag){
					$strFiles .= "<a href='javascript:deleteFile($fileID,\"$fileName\");'><img src='/images/common/cancel.gif' /></a>";
				}
				$strFiles .= "&nbsp;&nbsp;";
				$i++;
			}
		}
		return $strFiles;
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
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strFileName), SQLSRV_PARAM_IN),
						array($strFilePath, SQLSRV_PARAM_IN),
						array($iServerID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseFiles_Insert", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 删除案件附件
	 * @author blj
	 * @param $fileId
	 */
	public function deleteFile($fileId)
	{
		$params = array(array($fileId, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseFiles_Delete", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
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
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array($iLoginId, SQLSRV_PARAM_IN),
						array($iRoleId, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strLoginName), SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseSuspect_Insert", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 删除案件涉案人员
	 * @author blj
	 * @param $iCaseSerial
	 * @param $iRoleId
	 */
	public function deleteCaseSuspect($iCaseSerial,$iRoleId)
	{
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN),
						array($iRoleId, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseSuspect_Delete", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 读取案件-涉案人员列表
	 * @author blj
	 * @param $iCaseSerial
	 */
	public function getCaseSuspectList($iCaseSerial,$flag)
	{
		$strSuspectList = '';
		$params = array(array($iCaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_CaseSuspect_Select", $params);
		if (is_array($arrReturns) && count($arrReturns) > 0)
		{
			$i = 0 ;				
			foreach ($arrReturns as $val)
			{				
				$loginName = Utility::gb2312ToUtf8($val['LoginName']);
				$loginID = $val['LoginID'];
				$iRoleID = $val['RoleID'];
				$strSuspectList .= "<a class='blue' href='javascript:showUserInfo($iRoleID,\"$loginName($loginID)\");'>$loginName($loginID)</a>";
				$strSuspectList .= "<a href='javascript:getOperateLogs($iRoleID);'><img src='/images/common/search.png' /></a>";
				if($flag){
					$strSuspectList .= "<a href='javascript:deleteCaseSuspect($iRoleID,\"$loginName($loginID)\");'><img src='/images/common/cancel.gif' /></a>";
				}
				$strSuspectList .= "&nbsp;&nbsp;";
				$i++;
			}
		}
		return $strSuspectList;
	}

	/**
	 * 获取修改操作的信息
	 * @RoleID			BIGINT,		--角色ID
	 * @OperationType	VARCHAR(64) --操作类型,1:锁定角色,2:解除锁定,3:主机解绑,4:重置密码,5:处理财富,
								    --	      6:冻结财富,7:处罚角色,8:解除处罚,9:黄钻服务,10:补发月礼包
	 */
//	function getCaseProcessInfo($RoleID, $OperationType)
//	{
//		$arrResult = null;
//		$params = array(array($RoleID, SQLSRV_PARAM_IN),
//						array($OperationType, SQLSRV_PARAM_IN));
//						
//		$arrResult = $this->objSystemDB->fetchAllAssoc("Proc_CaseProcess_Select", $params);	
//		if($arrResult){
//			$i=0;
//			foreach($arrResult as $v){				
//				$arrResult[$i]['Reason'] = Utility::gb2312ToUtf8($v['Reason']);
//				$arrResult[$i]['Remarks'] = Utility::gb2312ToUtf8($v['Remarks']);
//				$arrResult[$i]['Requirement'] = Utility::gb2312ToUtf8($v['Requirement']);
//				$i++;
//			}	
//		}
//		return $arrResult;
//	}
	
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
		$params = array(array(Utility::utf8ToGb2312($strRemark), SQLSRV_PARAM_IN),
						array($iStep, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN),
						array($iPId, SQLSRV_PARAM_IN));
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_AuthProcess_Check", $params);
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}




    public function WeathBackOperateVerify($iPId, $iStep, $strRemark, $strSysUserName)
    {
        $params = array(array(Utility::utf8ToGb2312($strRemark), SQLSRV_PARAM_IN),
            array($iStep, SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($strSysUserName), SQLSRV_PARAM_IN),
            array($iPId, SQLSRV_PARAM_IN));
        $arrReturns = $this->objSystemDB->fetchAssoc("Proc_WeathBackProcess_Check", $params);
        if(is_array($arrReturns) && count($arrReturns) > 0)
        {
            return $arrReturns['iResult'];
        }else{
            return -99;//数据库返回异常
        }
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
		$params = array(array($iPID, SQLSRV_PARAM_IN),
						array($strFilePath, SQLSRV_PARAM_IN),
						array($iServerID, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_AuthProcessFile_Insert", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 读取财富补偿申请附件
	 * @param $iPID
	 */
	public function getAuthProcessFile($iPID)
	{
		$params = array(array($iPID, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAllAssoc("Proc_AuthProcessFile_Select", $params);		
		return $arrReturns;		
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
		$params = array(array($iFId, SQLSRV_PARAM_IN),
						array($iNumber, SQLSRV_PARAM_IN),
						array($type, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objSystemDB->fetchAssoc("Proc_CaseOperateUser_Apply", $params);		
		
		if(is_array($arrReturns) && count($arrReturns) > 0)
		{
			return $arrReturns['iResult'];
		}else{
			return -99;//数据库返回异常
		}
	}
	
	/**
	 * 读取操作用户记录相关案件编号
	 * @author blj
	 * @param $iFId
	 */
	public function getCaseOperateUserSn($iFId)
	{
		$arrReturn = null;
		$params = array(array($iFId, SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAllAssoc("Proc_CaseOperateUserSn_Select", $params);
		
		return $arrReturn;
	}
	
	/**
	 * CaseOperateUserSn表中插入新的案件编号
	 * @param $iFId
	 * @param $CaseSerial
	 */
	function insertCaseOperateUserSn($iFId, $CaseSerial)
	{
		$arrReturn = null;
		$params = array(array($iFId, SQLSRV_PARAM_IN),
						array($CaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_CaseOperateUserSn_Insert", $params);
		
		return $arrReturn;
	}
	
	/**
	 * 删除CaseOperateUserSn案件编号
	 * @param unknown_type $iFId
	 * @return unknown
	 */
	function delCaseOperateUserSn($iFId, $CaseSerial)
	{
		$arrReturn = null;
		$params = array(array($iFId, SQLSRV_PARAM_IN),
						array($CaseSerial, SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_CaseOperateUserSn_Delete", $params);
		
		return $arrReturn;
	}
	
	/**
	 * 更新审核案件的备注
	 * @PID			INT,	
	 * @Remarks		VARCHAR(256)		--备注原因
	 */
	function updateAuthProcessRemarks($PID, $Remarks)
	{
		$arrReturn = null;
		$params = array(array($PID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($Remarks), SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_AuthProcess_Update", $params);
		
		return $arrReturn;
	}
	
	/**
	 * 更新非审核案件的备注
	 * @param $iFId
	 * @param $Reason
	 */
	function updateCaseOperateUserReason($iFId, $strRemarks)
	{
		$arrReturn = null;
		$params = array(array($iFId, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($strRemarks), SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_CaseOperateUser_Update", $params);
		
		return $arrReturn;
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
		$params = array(array($id, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($evtName), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($evtDescription), SQLSRV_PARAM_IN),
						array($evtSort, SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_ServiceEvent_Insert", $params);
		
		return $arrReturn;
	}
	
	/**
	 * 根据ID获取单个服务事件信息
	 * @param $evtId
	 */
	function getServerEventByID($evtId)
	{
		$params = array(array($evtId, SQLSRV_PARAM_IN));
						
		$arrReturn = $this->objSystemDB->fetchAssoc("Proc_ServiceEvent_Select", $params);
		
		if(is_array($arrReturn) && count($arrReturn)>0)
		{			
			$arrReturn['EvtName'] = Utility::gb2312ToUtf8($arrReturn['EvtName']);
			$arrReturn['EvtContent'] = Utility::gb2312ToUtf8($arrReturn['EvtContent']);			
		}else{
			$arrReturn = null;
		}
		
		return $arrReturn;
	}
}