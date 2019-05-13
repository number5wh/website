<?php
require_once __DIR__.'/DALBase.php';

class BankDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['Bank'],true);
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
        $arrResult = $this->objOperationLogsDB->fetchAssoc("Proc_SelectRecordsCount", $params);
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
                array($arrParam['pagesize'], SQLSRV_PARAM_IN),
                array($arrParam['RowIndex'], SQLSRV_PARAM_IN)
            );
            $arrResult = $this->objOperationLogsDB->fetchAllAssoc("Proc_SelectPages", $params);
            if($flag)
                $this->objMemcache->set($this->iRoleID.$arrParam['memName'].$arrParam['iCurPage'],$arrResult,true,1800);
        }
        return $arrResult;
    }


    /**
	 * 读取系统银行
	 * @return array
	 */
	function getSysBankInfo()
	{
		$arrResults = $this->objBankDataDB->fetchAllAssoc("Proc_SysBank_SelectAll", '');
		return $arrResults;
	}
	/**
	 * 设置系统银行账户
	 * @param $iAccNo 账户号
	 * @param $iAccType　帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6冻结户
	 * $iResult=0:成功,-1:失败
	 */
	public function addSysBankAccNo($iAccNo,$iAccType)
	{
		$iResult = -1;
		$params = array(array($iAccNo, SQLSRV_PARAM_IN),
						array($iAccType, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}

	/**
	 * 转账
	 * @param unknown_type $iFromAccType　转出账户
	 * @param unknown_type $iWealthType　财富类型,1:龙币，2:金币
	 * @param unknown_type $iMoney 转出金额
	 * @param unknown_type $iToAccType　转入账户
	 * @return -4:金额必须大于0,,-3:余额不足，0:成功,-1:失败
	 */
	public function transSysBankMoney($iFromAccType,$iWealthType,$iMoney,$iToAccType)
	{
		//$iResult = -1;
		$params = array(array($iFromAccType, SQLSRV_PARAM_IN),
						array($iWealthType, SQLSRV_PARAM_IN),
						array($iMoney, SQLSRV_PARAM_IN),
						array($iToAccType, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_Trans", $params);
		//if(is_array($arrReturns) && count($arrReturns)>0)
		//	$iResult = $arrReturns['iResult'];
		return $arrReturns;
	}
	/**
	 * 系统银行扩容
	 * @param $iAccType　帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6冻结户
	 * @param $Balance　扩容金额
	 * @param $TypeID　2:金币扩容,1:龙币扩容
	 * $iResult=-3:金额必须大于0,0:成功,-1:失败
	 */
	public function setSysBankMoney($AccType,$Balance,$TypeID)
	{
		$params = array(array($AccType, SQLSRV_PARAM_IN),
						array($Balance, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_UpdateBalance", $params);
		return $arrReturns;
	}
	
	/**
	 * 将冻结财富，存入系统银行
	 * @AccType	INT,	--帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6:冻结户
	 * @Money	BIGINT,	--金币
	 * @FwMoney	BIGINT	--龙币
	 * @iStatus	TINYINT	--0:转入,1:转出
	 */
	function updateSysBankMoney($AccType, $Money, $FwMoney, $iStatus)
	{
		$params = array(array($AccType, SQLSRV_PARAM_IN),
						array($Money, SQLSRV_PARAM_IN),
						array($FwMoney, SQLSRV_PARAM_IN),
						array($iStatus, SQLSRV_PARAM_IN));
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_InMoney", $params);
		return $arrReturns;
	}
    /**
	 * 生成充值卡,判断余额,如果充足,扣除相应金额
	 * @AccType	INT,	--帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6:冻结户
	 * @Money	BIGINT,	--金币
     * @return -4:金额必须大于0,-3:余额不足,0:成功,-1:失败
	 */
	public function updateSysBank($AccType, $Money)
	{
	   //$iResult = -1;
		$params = array(array($AccType, SQLSRV_PARAM_IN),
						array($Money, SQLSRV_PARAM_IN));
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_Update", $params);
        //if(is_array($arrReturns)) $iResult = $arrReturns['iResult'];
        if(empty($arrReturns)) $arrReturns = null;
		return $arrReturns;
	}

    /**
     * @param $AccType
     * @return null
     */
    public function getSysBank($AccType){
        $params = array(array($AccType,SQLSRV_PARAM_IN),
            );
        $arrReturns = $this->objBankDataDB->fetchAssoc("Proc_SysBank_Select",$params);
        $arrReturns['AccTypeName']=$this->arrConfig['BankAccType'][$arrReturns['AccType']];
        return $arrReturns;
    }
	/**
	 * 返回特殊账户信息
	 */
	public function getFinanceInfo()
	{
		$arrReturns = $this->objBankDataDB->fetchAssoc("Proc_Finance_Select", '');
        if(empty($arrReturns)) $arrReturns = null;
		return $arrReturns;
	}
	/**
	 * 获取奖金池记录
	 *
	 * */
	public function getRoomLuckyEggMoneyList(){
	    $arrReturn = $this->objBankDataDB->fetchAllAssoc('P_RoomLuckyEggMoney_SelectALL','');
	    return $arrReturn;
	}
	/**
	 * 获取奖金池记录
	 *
	 * */
	public function getRoomLuckyEggMoney($RoomID){
		$params = array(array($RoomID, SQLSRV_PARAM_IN));
	    $arrReturn = $this->objBankDataDB->fetchAssoc('Proc_RoomLuckyEggMoney_Select',$params);
	    return $arrReturn;
	}


	public function updateDrawBack($orderid,$roleid,$content,$checkuser,$status){
        $params = array(
                    array($orderid, SQLSRV_PARAM_IN),
                    array($roleid, SQLSRV_PARAM_IN),
                    array($content, SQLSRV_PARAM_IN),
                    array($status, SQLSRV_PARAM_IN),
                    array($checkuser, SQLSRV_PARAM_IN)
            );

        $arrReturn = $this->objBankDataDB->fetchAssoc('Proce_userDrawBack_Update',$params);
        return $arrReturn;
    }

    //结算
    public function DrawBackFinish($orderid,$roleid){
        $params = array(
            array($orderid, SQLSRV_PARAM_IN),
            array($roleid, SQLSRV_PARAM_IN)
        );

        $arrReturn = $this->objBankDataDB->fetchAssoc('Proc_DrawBack_Finish',$params);
        return $arrReturn;
    }



    public function getDrawBack($orderId) {
        $params = array(
            array($orderId, SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objDB->fetchAssoc('Proc_DrawBack_select', $params);
        return $arrResult;
    }
	
}