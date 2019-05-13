<?php
require ROOT_PATH . 'Class/DAL/BankChangeLogsDAL.class.php';
class BankChangeLogsBLL
{
	private $objBankChangeLogsDAL = NULL;
	public function __construct()
    {
        $this->objBankChangeLogsDAL = new BankChangeLogsDAL();
    }
    
	/**
	 * 读取系统银行操作日志数据
	 * @return array
	 */

    function getRechargeOrderCount($startTime,$endTime){
        return $this->objBankChangeLogsDAL->getBankChangeLogsData($startTime,$endTime);
    }
}
?>