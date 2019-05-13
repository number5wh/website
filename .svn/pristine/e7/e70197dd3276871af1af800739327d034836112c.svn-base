<?php
require_once __DIR__.'/DALBase.php';

class BankChangeLogsDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['BankChangeLogs'],true);
	}
	/**
	 * 读取系统银行操作日志数据
	 * @param $startTime int 开始时间
	 * @param $endTime int 结束时间
	 * @return array
	 */
	
	public function getBankChangeLogsData($startTime,$endTime){
	    $params = array(
	        array($startTime,SQLSRV_PARAM_IN),
	        array($endTime,SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objBankChangeLogsDB->fetchAllAssoc("Proc_BankChangeLogsData_Count", $params);
	    return $arrReturns;
	}
	
}