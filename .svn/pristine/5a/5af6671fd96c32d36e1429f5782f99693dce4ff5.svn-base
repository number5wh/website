<?php
require_once __DIR__.'/DALBase.php';

class DataChangeLogsDAL extends DALBase
{
	private $iRoleID = NULL;
	public function __construct($iRoleID)
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID,$this->arrConfig['MapType']['DataChangeLogs'],true);
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
		$arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_GetRecordsCount", $params);
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
							array($arrParam['Page'], SQLSRV_PARAM_IN),
							array($arrParam['PageSize'], SQLSRV_PARAM_IN)
							);
            /**
             * 通过帐号验证	1
             * 登录大厅	2
             * 登录房间	3
             * 登录银行	4
             */
			$arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_GetPages", $params);
			/*foreach($arrResult as $key =>$val){
                switch($val['LoginType']){
                    case 1:$arrResult[$key]['LoginType'] = "账号验证";
                    case 2:$arrResult[$key]['LoginType'] = "登陆大厅";
                    case 3:$arrResult[$key]['LoginType'] = "登陆房间";
                    case 4:$arrResult[$key]['LoginType'] = "登陆银行";
                }
            }*/
            if($flag)
				$this->objMemcache->set($this->iRoleID.$arrParam['memName'].$arrParam['Page'],$arrResult,true,1800);
		}
		/*if(empty($arrReturns))
			$arrReturns = null;
		else 
		{
			$i=0;		
			foreach($arrReturns as $val)
			{				
				$CurDate = date('Y-m-d',strtotime($val['ReceiveTime']));
				//记录上一页的状态位,哪张表的哪条记录(表后缀,RowIndex)
				if(!isset($arrReturns[0]['PrevStartDate']) && !isset($arrReturns[0]['PrevLogsID']))
				{
					$arrReturns[0]['PrevStartDate']=strtotime($CurDate);
					if($arrParam['RowIndex']==0)
						$arrReturns[0]['PrevLogsID']=0;
					else
						$arrReturns[0]['PrevLogsID']=$val['LogsID']+1;
				}
				//记录下一页的状态位,哪张表的哪条记录(表后缀,RowIndex)
				if(!isset($arrReturns[0]['NextStartDate']) || (strtotime($arrReturns[0]['NextStartDate'])-strtotime($CurDate))<=0)
				{
					$arrReturns[0]['NextStartDate']=strtotime($CurDate);
					$arrReturns[0]['NextLogsID']=$val['LogsID'];
				}
				if(!isset($arrReturns[0]['NextLogsID']) || ((strtotime($arrReturns[0]['NextStartDate'])-strtotime($CurDate))<0 && $arrReturns[0]['NextLogsID']>$val['LogsID']))
					$arrReturns[0]['NextLogsID']=$val['LogsID'];				
				$i++;	
			}
		}*/
		return $arrResult;	
	}

	/**
	 * 游戏结算表玩过的同一款游戏的记录数
	 * * @param $StartDate 开始日期
	 * @param $EndDate 结束日期
	 * @param $strWhere	VARCHAR(200)
	 * @return int 总记录数
	 */
	function getUserGameLogsRecordsCount($arrParam)
	{		
		$iRecordsCount = 0;
		$params = array(array($arrParam['TableName'], SQLSRV_PARAM_IN),
						array($arrParam['StartDate'], SQLSRV_PARAM_IN),
						array($arrParam['EndDate'], SQLSRV_PARAM_IN),
						array($arrParam['where'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objDataChangeLogsDB->fetchAssoc("Proc_UserGameLogs_SelectRecordCount", $params);
		if(is_array($arrResult) && count($arrResult)>0)
			$iRecordsCount = $arrResult['RecordsCount'];
		return $iRecordsCount;	
	}
	/**
	 * 返回游戏结算表每天汇总数据(按角色,游戏种类,日期,房间类型分组汇总)
	 * @param $StartDate 开始日期
	 * @param $EndDate 结束日期
	 * @param $strWhere	VARCHAR(200)
	 * @param $iPageSize 每页显示记录数
	 * @return array
	 */
	function getUserGameLogsPage($arrParam)
	{
		$arrResult = $this->objMemcache->get($this->iRoleID.'UserGameLogsPage'.$arrParam['iCurPage']);
		if(!$arrResult)
		{
			$params = array(array($arrParam['StartDate'], SQLSRV_PARAM_IN),
							array($arrParam['EndDate'], SQLSRV_PARAM_IN),
							array($arrParam['where'], SQLSRV_PARAM_IN),
							array($arrParam['pagesize'], SQLSRV_PARAM_IN)
							);
			$arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_UserGameLogs_Select", $params);
			if(empty($arrResult)) $arrResult = null;
			$this->objMemcache->set($this->iRoleID.'UserGameLogsPage'.$arrParam['iCurPage'],$arrResult,true,1800);
		}
		return $arrResult;	
	}
	/**
	 * 返回游戏结算表每天汇总数据(按角色,游戏种类,日期,房间类型分组汇总)
	 * @param $StartDate 开始日期
	 * @param $EndDate 结束日期
	 * @param $strWhere	VARCHAR(200)
	 * @param $iPageSize 每页显示记录数
	 * @return array
	 */
	function getUserGameSpLogsPage($arrParam)
	{
		$arrResult = $this->objMemcache->get($this->iRoleID.'UserGameSpLogsPage'.$arrParam['iCurPage']);
		if(!$arrResult)
		{
			$params = array(array($arrParam['StartDate'], SQLSRV_PARAM_IN),
							array($arrParam['EndDate'], SQLSRV_PARAM_IN),
							array($arrParam['sqlWhere'], SQLSRV_PARAM_IN),
							array($arrParam['KindID'], SQLSRV_PARAM_IN),
							array($arrParam['IsAll'], SQLSRV_PARAM_IN),
							array($arrParam['pagesize'], SQLSRV_PARAM_IN)
							);
			$arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_UserGameSpLogs_Select", $params);
			if(empty($arrResult)) $arrResult = null;
			$this->objMemcache->set($this->iRoleID.'UserGameSpLogsPage'.$arrParam['iCurPage'],$arrResult,true,1800);
		}
		return $arrResult;	
	}
	
	/**
	 * 插入龙币返回\补发日志
	 * @iRoleID		--角色ID
	 * @$iFwMoney	--龙币消费数
	 * @iBalance	--龙币余额
	 * @iDcFlag		--1：入  2：出
	 * @iTransType	--交易类型：1、充值,6、财富补偿,3、消费 
	 * @strNote		--备注信息
	 * @SysUserID	--系统用户ID
	 * @return unknown
	 */
	public function insertBankFwMoneyTransLogs($iRoleID,$iFwMoney,$iBalance,$iDcFlag,$iTransType,$strNote,$SysUserName)
	{
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($iFwMoney, SQLSRV_PARAM_IN),
						array($iBalance, SQLSRV_PARAM_IN),
						array($iDcFlag, SQLSRV_PARAM_IN),
						array($iTransType, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($strNote), SQLSRV_PARAM_IN),
						array($SysUserName, SQLSRV_PARAM_IN),
						array(Utility::getIP(),SQLSRV_PARAM_IN)
				  );
		$result = $this->objDataChangeLogsDB->fetchAssoc("Proc_BankFwMoneyTransLogs_Insert", $params);
		
		return $result;
	}

	/**
	 * 插入金币返回\补发日志
	 * @RoleID			INT,		--角色ID
	 * @LoginName		VARCHAR(50),--角色名
	 * @TransType		TINYINT,	--交易类型：0银行(游戏)存取 ,1用户转帐,2税收, 3背包存取,4:充值,5:系统返还,6:财富补偿
	 * @TargetAccNo		INT,		--用户互转，写对方RoleID;游戏存取，写游戏KindID;扣税消费，写系统ACcNo;背包存取,写背包ID
	 * @TargetLoginName	VARCHAR(50),--目标角色名称/游戏名称
	 * @Flag			TINYINT,	--1借入，2贷出
	 * @TransAmount		BIGINT,		--交易数量
	 * @Balance			BIGINT,		--余额
	 * @ClientIP		VARCHAR(32),--交易时的IP
	 * @MachineSerial	VARCHAR(100),--机器码
	 * @LoginID			INT,		--游戏编号
	 * @Note			VARCHAR(255)--操作内容
	 */
	public function writeBankTransLog($iRoleID,$strLoginName,$iTransType,$iTargetAccNo,$strTargetLoginName,$iFlag,$iTransAmount,$Balance,$LoginID,$Note)
	{
		list($usec, $sec)=explode(" ", microtime());
		$strTransNo = strval($sec) . strval($usec*1000000);
		
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($strTransNo, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($strLoginName), SQLSRV_PARAM_IN),
						array($iTransType, SQLSRV_PARAM_IN),
						array($iTargetAccNo, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($strTargetLoginName), SQLSRV_PARAM_IN),
						array($iFlag, SQLSRV_PARAM_IN),
						array($iTransAmount, SQLSRV_PARAM_IN),
						array($Balance, SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($Note), SQLSRV_PARAM_IN));
						
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_BankTransLogs_Insert", $params);
	}
	
	/**
	 * 插入背包道具补发日志
	 * @param $intro 日志详情
	 */
	public function insertKnapsackDataLogs($iRoleID,$intro)
	{
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($intro), SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN));
						
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_KnapsackDataLogs_Insert", $params);
	}
	
	/**
	 * 插入财富冻结日志
	 * @RoleID			INT,			--角色ID
	 * @CaseSerial		BIGINT,			--案件编号,可重复
	 * @iMoney			BIGINT,			--冻结数量(金币)
	 * @iFwMoney		BIGINT,			--冻结数量(龙币)	
	 * @Step			TINYINT,		--状态,0:冻结,1:申请返还,2:已返还3:拒绝
	 * @LoginName		VARCHAR(16),	--返还给玩家昵称
	 * @SysUserName		VARCHAR(16),	--冻结操作人	
	 * @Remarks			VARCHAR(256)	--备注	
	 */
	function insertLockMoneyLogs($iRoleID, $iCaseSerial, $iMoney, /*$iFwMoney,*/ $Step, $LoginName, $SysUserName, $Remarks)
	{
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($iCaseSerial, SQLSRV_PARAM_IN),
						array($iMoney, SQLSRV_PARAM_IN),
						array($Step, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($LoginName), SQLSRV_PARAM_IN),
						array($SysUserName, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($Remarks), SQLSRV_PARAM_IN));
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_LockMoneyLogs_Insert", $params);
	}
	
	/**
	 * 插入系统银行交易日志
	 * @TransNo		VARCHAR(25),--流水号
	 * @AccType		TINYINT,	--账号类型 1基本户,2税收户 ,3充值户,4推广户,5消费户,6冻结户
	 * @TargetID		INT,		--目标对象，角色ID/系统银行账号类型;
	 * @KindID			SMALLINT,	--游戏KindID
	 * @TransType		TINYINT,	--交易类型：1:内转,2:扩容,3:充值,4:转到游戏(赠送帐户),5:税收,6:道具兑换,7:角色冻结,8:商城消费,9:系统补偿
	 * @DCFlag			TINYINT,	--1借入，2贷出
	 * @Amount			BIGINT,		--交易金额
	 * @PreBalance		BIGINT,		--之前余额
	 * @Balance		BIGINT,		--当前余额	
	 * @ClientIP		VARCHAR(32),--交易时的IP
	 * @MachineSerial	VARCHAR(100)--机器码
	 */
	function insertSysBankTransLogs($AccType, $TargetID, $KindID, $TransType, $DCFlag, $Amount, $PreBalance, $Balance, $MachineSerial)
	{
		$params = array(array($AccType, SQLSRV_PARAM_IN),
						array($TargetID, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array($TransType, SQLSRV_PARAM_IN),
						array($DCFlag, SQLSRV_PARAM_IN),
						array($Amount, SQLSRV_PARAM_IN),
						array($PreBalance, SQLSRV_PARAM_IN),
						array($Balance, SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN),
						array($MachineSerial, SQLSRV_PARAM_IN));
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_SysBankTransLogs_Insert", $params);
	}
	
	/**
	 * 返回同一桌游戏各玩家输赢信息
	 * @param $SerialNumber	BIGINT,
	 * @param $DateTime	CHAR(8)--日期,如:20120401
     * @param $ServerID    INT
     * @param $LogTime CHAR(20) 2015-11-11 17:46:09
     * 修改过
	 */
	function getSelectGameResult($SerialNumber, $DateTime,$ServerID,$LogTime)
	{
		$params = array(array($SerialNumber, SQLSRV_PARAM_IN),
                        array($ServerID,SQLSRV_PARAM_IN),
						array($DateTime, SQLSRV_PARAM_IN),
                        array($LogTime,SQLSRV_PARAM_IN)
        );
		$arrReturns = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_UserGameChangeLogs_SelectGameResult", $params);
		return $arrReturns;
	}
	
	/**
	 * 返回寻宝房间同桌玩家输赢信息
	 * @param $SerialNumber
	 * @param $DateTime
	 * @return unknown
	 */
	function getSelectSpGameResult($SerialNumber, $DateTime)
	{
		$params = array(array($SerialNumber, SQLSRV_PARAM_IN),
						array($DateTime, SQLSRV_PARAM_IN));
		$arrReturns = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_UserGameSpLogs_SelectGameResult", $params);		
		return $arrReturns;
	}
	/**
	 * 返回单元赛每局记录
	 * @param $PlayDate
	 * @param $MatchUnitID
	 * @param $RoleID
	 * @return array
	 * @author xlj
	 */
	function getGameMatchLogsList($PlayDate,$MatchUnitID,$RoleID)
	{
		$params = array(array($PlayDate, SQLSRV_PARAM_IN),
						array($MatchUnitID, SQLSRV_PARAM_IN),
						array($RoleID, SQLSRV_PARAM_IN)
						);
						
		$arrReturns = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_GameMatchLogs_Select", $params);	
		return $arrReturns;
	}
	/**
	 * 返回单元赛每局同桌玩家记录
	 * @param $PlayDate
	 * @param $SerialNumber
	 * @return array
	 * @author xlj
	 */
	function getGameMatchDeskPlayerList($PlayDate,$SerialNumber)
	{
		$params = array(array($PlayDate, SQLSRV_PARAM_IN),
						array($SerialNumber, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_GameMatchLogs_SelectDeskPlayer", $params);	
		return $arrReturns;
	}
	
	/**
	 * 记录黄钻充值日志
	 * @param $openTime
	 * @param $expireTime
	 * @param $numDays
	 * @param $vipID
	 */
	public function addBuyVipLogs($openTime,$expireTime,$numDays,$vipID)
	{
		$params = array(array($this->iRoleID, SQLSRV_PARAM_IN),
						array($openTime, SQLSRV_PARAM_IN),
						array($expireTime, SQLSRV_PARAM_IN),
						array(3, SQLSRV_PARAM_IN),
						array($numDays, SQLSRV_PARAM_IN),
						array(1, SQLSRV_PARAM_IN),
						array($vipID, SQLSRV_PARAM_IN));
		
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_UserVipLogs_Insert", $params);
		return $arrResults;	
	}
	/**
	 * 插入金币返回\补发日志
	 * @RoleID			INT,		--角色ID
	 * @LoginName		VARCHAR(50),--角色名
	 * @TransType		TINYINT,	--交易类型：0银行(游戏)存取 ,1用户转帐,2税收, 3背包存取,4:充值,5:系统返还,6:财富补偿
	 * @TargetAccNo		INT,		--用户互转，写对方RoleID;游戏存取，写游戏KindID;扣税消费，写系统ACcNo;背包存取,写背包ID
	 * @TargetLoginName	VARCHAR(50),--目标角色名称/游戏名称
	 * @Flag			TINYINT,	--1借入，2贷出
	 * @TransAmount		BIGINT,		--交易数量
	 * @Balance			BIGINT,		--余额
	 * @ClientIP		VARCHAR(32),--交易时的IP
	 * @MachineSerial	VARCHAR(100),--机器码
	 * @LoginID			INT,		--游戏编号
	 * @Note			VARCHAR(255)--操作内容
	 */
	public function insertBankTransLog($iRoleID,$strLoginName,$iTransType,$iTargetAccNo,$strTargetLoginName,$iFlag,$iTransAmount,$Balance,$LoginID,$Note,$PayID,$OrderID)
	{
		list($usec, $sec)=explode(" ", microtime());
		$strTransNo = strval($sec) . strval($usec*1000000);
		
		$params = array(array($iRoleID, SQLSRV_PARAM_IN),
						array($strTransNo, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($strLoginName), SQLSRV_PARAM_IN),
						array($iTransType, SQLSRV_PARAM_IN),
						array($iTargetAccNo, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($strTargetLoginName), SQLSRV_PARAM_IN),
						array($iFlag, SQLSRV_PARAM_IN),
						array($iTransAmount, SQLSRV_PARAM_IN),
						array($Balance, SQLSRV_PARAM_IN),
						array(Utility::getIP(), SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(Utility::Utf8Togb2312($Note), SQLSRV_PARAM_IN),
						array($PayID, SQLSRV_PARAM_IN),
						array($OrderID, SQLSRV_PARAM_IN)
						);
						
		$arrResults = $this->objDataChangeLogsDB->fetchAssoc("Proc_BankTransLogs1_Insert", $params);
	}
	
	/**
	 * 读取上个月玩家某种游戏输赢数据
	 * @param unknown_type $RoleID
	 * @param unknown_type $KindID
	 * @return array
     * 原来是UserGameDataLogs 由于表名变了  所以模型也改下名字 mlgt
	 */
	public function getUserGameChangeLogs($RoleID,$KindID,$Date)
	{
		$arrResult = NULL;
		$this->strSelectKey .= $RoleID.$KindID.$Date;
		$arrResult = $this->objMemcache->get($this->strSelectKey);		$arrResult=null;	
		if(!$arrResult)
		{
			$params = array(array($RoleID, SQLSRV_PARAM_IN),
							array($KindID, SQLSRV_PARAM_IN),
							array($Date, SQLSRV_PARAM_IN)					
						);
            //var_dump($params);
			$arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_UserGameChangeLogs_Select", $params);
			if(empty($arrResult)) $arrResult = null;
			$this->objMemcache->set($this->strSelectKey,$arrResult,true,$this->arrConfig['CacheTime']);
		}
		return $arrResult;
	}
    public function getUserLoginLogs($RoleID,$Date){
        $arrResult = NULL;

            $params = array(array($RoleID, SQLSRV_PARAM_IN),
                            array($Date,SQLSRV_PARAM_IN),
                );
            $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_LoginLogs_Select");
            if(empty($arrResult)) $arrResult = null;

        return $arrResult;
    }
   /**
    * 添加房间机器人配置记录
     * @param int RoomID  房间ID
     * @param int KindID  游戏种类ID
     * @param int RoomType 房间类型
     * @return array
     * 
     * */
    public function addRobotGameMoney($RoomID,$KindID,$RoomType)
    {
        $params = array(array($RoomID, SQLSRV_PARAM_IN),
            array($KindID, SQLSRV_PARAM_IN),
            array($RoomType, SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_RobotGameMoney_Insert", $params);
        return $arrResult['iResult']; 
    }

    public function getUserVipDetailCount($arrParam){
        $iRecordsCount = 0;
        $params = array(array($arrParam['tableName'], SQLSRV_PARAM_IN),
            array($arrParam['StartDate'], SQLSRV_PARAM_IN),
            array($arrParam['EndDate'], SQLSRV_PARAM_IN),
            array($arrParam['where'], SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_UserVIPLogs_GetRecordCount", $params);
        if(is_array($arrResult) && count($arrResult)>0)
            $iRecordsCount = $arrResult['RecordsCount'];
        return $iRecordsCount;
    }

    public function getUserVipDetailList($arrParam,$flag=0){
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
                array($arrParam['Page'], SQLSRV_PARAM_IN),
                array($arrParam['PageSize'], SQLSRV_PARAM_IN)
            );
            /**
             * 通过帐号验证	1
             * 登录大厅	2
             * 登录房间	3
             * 登录银行	4
             */
            $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_UserVIPLogs_GetPages", $params);
            /*foreach($arrResult as $key =>$val){
                switch($val['LoginType']){
                    case 1:$arrResult[$key]['LoginType'] = "账号验证";
                    case 2:$arrResult[$key]['LoginType'] = "登陆大厅";
                    case 3:$arrResult[$key]['LoginType'] = "登陆房间";
                    case 4:$arrResult[$key]['LoginType'] = "登陆银行";
                }
            }*/
            if($flag)
                $this->objMemcache->set($this->iRoleID.$arrParam['memName'].$arrParam['Page'],$arrResult,true,1800);
        }
        /*if(empty($arrReturns))
            $arrReturns = null;
        else
        {
            $i=0;
            foreach($arrReturns as $val)
            {
                $CurDate = date('Y-m-d',strtotime($val['ReceiveTime']));
                //记录上一页的状态位,哪张表的哪条记录(表后缀,RowIndex)
                if(!isset($arrReturns[0]['PrevStartDate']) && !isset($arrReturns[0]['PrevLogsID']))
                {
                    $arrReturns[0]['PrevStartDate']=strtotime($CurDate);
                    if($arrParam['RowIndex']==0)
                        $arrReturns[0]['PrevLogsID']=0;
                    else
                        $arrReturns[0]['PrevLogsID']=$val['LogsID']+1;
                }
                //记录下一页的状态位,哪张表的哪条记录(表后缀,RowIndex)
                if(!isset($arrReturns[0]['NextStartDate']) || (strtotime($arrReturns[0]['NextStartDate'])-strtotime($CurDate))<=0)
                {
                    $arrReturns[0]['NextStartDate']=strtotime($CurDate);
                    $arrReturns[0]['NextLogsID']=$val['LogsID'];
                }
                if(!isset($arrReturns[0]['NextLogsID']) || ((strtotime($arrReturns[0]['NextStartDate'])-strtotime($CurDate))<0 && $arrReturns[0]['NextLogsID']>$val['LogsID']))
                    $arrReturns[0]['NextLogsID']=$val['LogsID'];
                $i++;
            }
        }*/
        return $arrResult;
    }

    /**
     * @param $RoleID 为0 则清空全部该游戏类型的钱
     * @param $KindID
     * @param $RoomType
     */
    public function setUserGameDataTotalMoney($RoleID,$KindID,$RoomType){
        $params = array(
            array($RoleID,SQLSRV_PARAM_IN),
            array($KindID,SQLSRV_PARAM_IN),
            array($RoomType,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_UserGameRank_SetClearCount",$params);
        return $arrResult['iResult'];
    }

    /**
     * @param $RoleID
     * @param $KindID
     */
    public function getUserGameRankInfo($RoleID,$KindID){
        $params = array(
            array($RoleID,SQLSRV_PARAM_IN),
            array($KindID,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_UserGameRank_Select",$params);
        if(is_array($arrResult) && count($arrResult) != 0){
            foreach($arrResult as &$val){
                $val['RoleName'] = Utility::gb2312ToUtf8($val['RoleName']);
            }
            unset($val);
        }
        return $arrResult;
    }
    /**
     * @param $AddTime 查询日期
     */
    function getActiveUserNumber($AddTime){
        $params = array(
            array($AddTime,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("P_ActiveUser_Select",$params);
        return $arrResult;
    }
    
    
    /**
     * 
     * @param unknown $RoldId
     * @param unknown $startDate
     * @param unknown $endDate
     * @return 获取用户指定时间内的转入记录
     */
    function getUserDayOut($RoldId,$startDate,$endDate,$changtype){
        $params = array(
            array($RoldId,SQLSRV_PARAM_IN),
            array($startDate,SQLSRV_PARAM_IN),
            array($endDate,SQLSRV_PARAM_IN),
            array($changtype,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAllAssoc("Proc_getUserMonthOut",$params);
        return $arrResult;
    }
    
    
    
    /*
     * 获取用户游戏信息
     */
    public function getOlineUser($RoleID,$KindID){
        $params = array(
            array($RoleID,SQLSRV_PARAM_IN),
            array($KindID,SQLSRV_PARAM_IN),
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_UserOnlineData",$params);
        return $arrResult;
    }


    public function exitsTable($tablename){
        $params = array(
            array($tablename,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_TableExist",$params);
        return $arrResult;
    }


    public function getRankStatus($roleid){
        $params = array(
            array($roleid,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("Proc_RandUserStaus",$params);
        return $arrResult;
    }


    public function setRankStatus($roleid,$state){
        $params = array(
            array($roleid,SQLSRV_PARAM_IN),
            array($state,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("Proc_RandUserStausSet",$params);
        return $arrResult;
    }

    public function setNickName($roleid, $nickname)
    {
        $params = array(
            array($roleid,SQLSRV_PARAM_IN),
            array($nickname,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("Proc_RandUserSetNickName",$params);
        return $arrResult;
    }

    public function addBankMoneyTop($roleid, $nickname, $totalmoney, $headurl)
    {
        $params = array(
            array($roleid,SQLSRV_PARAM_IN),
            array($nickname,SQLSRV_PARAM_IN),
            array($headurl,SQLSRV_PARAM_IN),
            array($totalmoney,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_BankMoneyTop_Insert",$params);
        return $arrResult;
    }
    public function editBankMoneyTop($roleid, $nickname, $totalmoney, $headurl)
    {
        $params = array(
            array($roleid,SQLSRV_PARAM_IN),
            array($nickname,SQLSRV_PARAM_IN),
            array($headurl,SQLSRV_PARAM_IN),
            array($totalmoney,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_BankMoneyTop_update",$params);
        return $arrResult;
    }
    public function deleteBankMoneyTop($roleid)
    {
        $params = array(
            array($roleid,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDataChangeLogsDB->fetchAssoc("P_BankMoneyTop_delete",$params);
        return $arrResult;
    }


}
?>