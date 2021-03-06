<?php
require_once __DIR__.'/DALBase.php';

class PayLogsDAL extends DALBase
{
	private $iRoleID = 0;
	public function __construct($iRoleID)
	{
		parent::__construct();
		$this->arrConfig=unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID,$this->arrConfig['MapType']['PayLogs'],true);
	}
	

	/**
	 * 插入修改支付日志
	 */
	function addPayLogs($pay_result,$pay_info,$bill_date,$bargainor_id,$transaction_id,$sp_billno,$total_fee,$burden,$fee_type,$LoginID)
	{
	    $params = array(array($pay_result, SQLSRV_PARAM_IN),
	        array($pay_info, SQLSRV_PARAM_IN),
	        array($bill_date, SQLSRV_PARAM_IN),
	        array($bargainor_id, SQLSRV_PARAM_IN),
	        array($transaction_id, SQLSRV_PARAM_IN),
	        array($sp_billno, SQLSRV_PARAM_IN),
	        array($total_fee, SQLSRV_PARAM_IN),
	        array($burden, SQLSRV_PARAM_IN),
	        array($fee_type, SQLSRV_PARAM_IN),
	        array($LoginID, SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayLogs_Insert", $params);
	    return $arrReturns;
	}
	/**
	 * 插入支付订单
	 * */
	function addPayOrder($PayType,$transaction_id,$sp_billno,$total_fee,$LoginID){
	    $params = array(
	        array($PayType,SQLSRV_PARAM_IN),
	        array($transaction_id, SQLSRV_PARAM_IN),
	        array($sp_billno, SQLSRV_PARAM_IN),
	        array($total_fee, SQLSRV_PARAM_IN),
	        array($LoginID, SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayOrder_Insert", $params);
	    return $arrReturns;
	}
	
	/**
	 * 修改支付订单状态
	 * */
	
	function setPayOrderStatus($PayType,$transaction_id,$sp_billno,$status){
	    $params = array(
	        array($PayType,SQLSRV_PARAM_IN),
	        array($transaction_id,SQLSRV_PARAM_IN),
	        array($sp_billno,SQLSRV_PARAM_IN),
	        array($status,SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayOrderStatus_Update", $params);
	    return $arrReturns;
	}
	
	/**
	 * 
	 * 查询支付订单
	 * */
	function findPayOrder($PayType,$transaction_id,$sp_billno,$status){
	    $params = array(
	        array($PayType,SQLSRV_PARAM_IN),
	        array($transaction_id,SQLSRV_PARAM_IN),
	        array($sp_billno,SQLSRV_PARAM_IN),
	        array($status,SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayOrder_Select", $params);
	    return $arrReturns;
	}
	/**查询支付配置
	 * 
	 * */
	function getPayConfig(){
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayConfig_Select", '');
	    return $arrReturns;
	}
	/**
	 * 更新支付配置
	 * */
	function updatePayConfig($column,$amount){
	    $params = array(
	        array($column,SQLSRV_PARAM_IN),
	        array($amount,SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayConfig_Update", $params);
	    return $arrReturns;
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
        $arrResult = $this->objPayLogsDB->fetchAssoc("P_GetRecordsCount", $params);
        if(is_array($arrResult) && count($arrResult)>0)
            $iRecordsCount = $arrResult['RecordsCount'];
        return $iRecordsCount;
    }

    function getPayOrderSummary($date, $where,$offline)
    {
        $arrResult = [];
        $params = array(
            array($date, SQLSRV_PARAM_IN),
            array($where, SQLSRV_PARAM_IN),
            array($offline, SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objPayLogsDB->fetchAssoc("P_PayOrderCout_get", $params);
        return $arrResult;
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
        //if($flag)
            //$arrResult = $this->objMemcache->get($this->iRoleID.$arrParam['memName'].$arrParam['iCurPage']);
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
            $arrResult = $this->objPayLogsDB->fetchAllAssoc("P_GetPages", $params);
            /*foreach($arrResult as $key =>$val){
                switch($val['LoginType']){
                    case 1:$arrResult[$key]['LoginType'] = "账号验证";
                    case 2:$arrResult[$key]['LoginType'] = "登陆大厅";
                    case 3:$arrResult[$key]['LoginType'] = "登陆房间";
                    case 4:$arrResult[$key]['LoginType'] = "登陆银行";
                }
            }*/
            //if($flag)
                //$this->objMemcache->set($this->iRoleID.$arrParam['memName'].$arrParam['Page'],$arrResult,true,1800);
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
    public function getRechargeOrderCount($startTime,$endTime){
        $params = array(
            array($startTime,SQLSRV_PARAM_IN),
            array($endTime,SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objPayLogsDB->fetchAllAssoc("P_PayOrder_Count", $params);
        return $arrReturns;
    }
    /**
     * 根据商户订单号获取订单信息
     * @param varchar $SpOrderNo
     * @return array
     * */
    function getPayOrder($SpOrderNo,$cardType){
        $params = array(
            array($SpOrderNo,SQLSRV_PARAM_IN),
            array($cardType,SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objPayLogsDB->fetchAssoc("Proc_PayOrder_Find", $params);
        return $arrReturns;
    }
    
    /**
     * 添加体验卡
     * @param $CardNo 卡号
     * @param $CardPass 密码
     *
     * */
    function addTestCard($CardNo,$CardPass){
        $params = array(
            array($CardNo,SQLSRV_PARAM_IN),
            array($CardPass,SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objPayLogsDB->fetchAssoc("P_TestCard_Insert", $params);
        return $arrReturns;
    }
    
    /**
     * @return mixed
     */
    public function summaryRechargeCard(){
        $params = array(
        );
        $arrResults = $this->objPayLogsDB->fetchAssoc("Proc_TestCard_Select_By_State", $params);
        if($arrResults['UntokenCard']===null)
            $arrResults['UntokenCard'] = 0;
        if($arrResults['TokenCard']===null)
            $arrResults['TokenCard'] = 0;
        return $arrResults;
    }
    /***
     * 销毁实体卡
     *
     *
     * **/
    function delTestCard($state){
        $params = array(
            array($state,SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objPayLogsDB->fetchAssoc("Proc_TestCard_Delete_By_State", $params);
        return $arrResults;
    }

    //获取订单数据
    function orderData(){
        $params = array();
        $arrResults = $this->objPayLogsDB->fetchAssoc("P_IndexDashBoard_OrderData", $params);
        return $arrResults;
    }


    function getplaylog($orderid,$querytime){
        $params = array(
            array($orderid,SQLSRV_PARAM_IN),
            array($querytime,SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objPayLogsDB->fetchAssoc("Proc_playlogAmout_get", $params);
        return $arrResults;
    }
}
?>