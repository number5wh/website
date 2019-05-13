<?php
require ROOT_PATH . 'Class/DAL/BankDAL.class.php';
class BankBLL
{
	private $objBankDAL = NULL;
	public function __construct()
    {
        $this->objBankDAL = new BankDAL();
    }



    function getRecordsCount($arrParam)
    {
        return $this->objBankDAL->getRecordsCount($arrParam);
    }
    /**
     * 分页
     * @param $arrParam
     * @param $page
     */
    function getPageList($arrParam, $flag=0)
    {
        return $this->objBankDAL->getPageList($arrParam, $flag);
    }
    
	/**
	 * 读取系统银行
	 * @return array
	 */
	function getSysBankInfo()
	{
		return $this->objBankDAL->getSysBankInfo();
	}

    /**
     * @param $accNo
     */
    function getSysBank($accNo){
        return $this->objBankDAL->getSysBank($accNo);
    }
	/**
	 * 设置系统银行账户
	 * @param $iAccNo 账户号
	 * @param $iAccType　帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6冻结户
	 * $iResult=0:成功,-1:失败
	 */
	public function addSysBankAccNo($iAccNo,$iAccType)
	{
		return $this->objBankDAL->addSysBankAccNo($iAccNo,$iAccType);
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
		return $this->objBankDAL->transSysBankMoney($iFromAccType,$iWealthType,$iMoney,$iToAccType);
	}
	/**
	 * 系统银行扩容
	 * @param $iAccType　帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6冻结户
	 * @param $Balance　扩容金额
	 * @param $TypeID　1:金币扩容,2:龙币扩容
	 * $iResult=-3:金额必须大于0,0:成功,-1:失败
	 */
	public function setSysBankMoney($AccType,$Balance,$TypeID)
	{
		return $this->objBankDAL->setSysBankMoney($AccType,$Balance,$TypeID);
	}
	
	/**
	 * 将冻结财富，存入系统银行
	 * @AccType	INT,	--帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6:冻结户
	 * @Money		BIGINT,	--金币
	 * @FwMoney	BIGINT	--龙币
	 * @iStatus	TINYINT	--0:转入,1:转出
	 */
	function updateSysBankMoney($AccType, $Money, $FwMoney, $iStatus)
	{
		return $this->objBankDAL->updateSysBankMoney($AccType, $Money, $FwMoney, $iStatus);
	}
    /**
	 * 生成充值卡,判断余额,如果充足,扣除相应金额
	 * @AccType	INT,	--帐户类型,1基本户,2税收户 ,3充值户,4推广户,5消费户,6:冻结户
	 * @Money	BIGINT,	--金币
     * @return -4:金额必须大于0,-3:余额不足,0:成功,-1:失败
	 */
	public function updateSysBank($AccType, $Money)
	{
	   return $this->objBankDAL->updateSysBank($AccType, $Money);
	}
	/**
	 * 返回特殊账户信息
	 */
	public function getFinanceInfo()
	{
		return $this->objBankDAL->getFinanceInfo();
	}
	/**
	 * 获取奖金池记录
	 * 
	 * */
	public function getRoomLuckyEggMoneyList(){
	    return $this->objBankDAL->getRoomLuckyEggMoneyList();
	}
	/**
	 * 获取奖金池记录
	 *
	 * */
	public function getRoomLuckyEggMoney($RoomID){
	    return $this->objBankDAL->getRoomLuckyEggMoney($RoomID);
	}


	public function updateDrawBack($orderid,$roleid,$content,$checkuser,$status)
    {
        return $this->objBankDAL->updateDrawBack($orderid,$roleid,$content,$checkuser,$status);
    }

    public function DrawBackFinish($orderid,$roleid){
        return $this->objBankDAL->DrawBackFinish($orderid,$roleid);
    }


    public function getDrawBack($orderId){
        return $this->objBankDAL->getDrawBack($orderId);
    }

}
?>