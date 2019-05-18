<?php
require ROOT_PATH . 'Class/DAL/PayLogsDAL.class.php';
class PayLogsBLL
{
	private $objPayLogsDAL = NULL;
	public function __construct($iRoleID)
    {
        $this->objPayLogsDAL = new PayLogsDAL($iRoleID);
    }

    /**
     * 插入修改支付日志
     */
    function addPayLogs($pay_result,$pay_info,$bill_date,$bargainor_id,$transaction_id,$sp_billno,$total_fee,$burden,$fee_type,$LoginID)
    {
        return $this->objPayLogsDAL->addPayLogs($pay_result,$pay_info,$bill_date,$bargainor_id,$transaction_id,$sp_billno,$total_fee,$burden,$fee_type,$LoginID);
    }
    
    /**
     * 插入支付订单
     * */
    function addPayOrder($PayType,$transaction_id,$sp_billno,$total_fee,$LoginID){
        return $this->objPayLogsDAL->addPayOrder($PayType,$transaction_id,$sp_billno,$total_fee,$LoginID);
    }
    /**
     * 修改支付订单状态
     * 
     * */
    
    function setPayOrderStatus($PayType,$transaction_id,$sp_billno,$status){
        return $this->objPayLogsDAL->setPayOrderStatus($PayType,$transaction_id,$sp_billno,$status);
    }
    
    /**
     * 查询 支付订单
     * */
    
    function findPayOrder($PayType,$transaction_id,$sp_billno,$status){
        return $this->objPayLogsDAL-> findPayOrder($PayType,$transaction_id,$sp_billno,$status);
    }
    
    /**
     * 查询支付配置
     * */
    function getPayConfig(){
        return $this->objPayLogsDAL->getPayConfig();
    }
    /**
     * 更新支付配置
     * */
    function updatePayConfig($column,$amount){
        return $this->objPayLogsDAL->updatePayConfig($column,$amount);
    }
    /**
     * 获取分页数量
     */
    function getRecordsCount($arrParam){
        return $this->objPayLogsDAL->getRecordsCount($arrParam);
    }

    function getPayOrderSummary($date, $where,$offline) {
        return $this->objPayLogsDAL->getPayOrderSummary($date, $where,$offline);
    }
    /**
     * 根据商户订单号获取订单信息
     * @param varchar $SpOrderNo
     * @return array
     * */
    function getPayOrder($SpOrderNo,$cardType){
        return $this->objPayLogsDAL-> getPayOrder($SpOrderNo,$cardType);
    }

    /**
     * 分页设置
     * @param $arrParam
     * @param int $flag
     */
    function getPageList($arrParam,$flag=0){
        return $this->objPayLogsDAL->getPageList($arrParam, $flag);
    }

    function getRechargeOrderCount($startTime,$endTime){
        return $this->objPayLogsDAL->getRechargeOrderCount($startTime,$endTime);
    }
    /**
     * 添加体验卡
     * @param $CardNo 卡号
     * @param $CardPass 密码
     * 
     * */
    function addTestCard($CardNo,$CardPass){
        return $this->objPayLogsDAL->addTestCard($CardNo,$CardPass);
    }
    /**
     * 统计实卡领取情况
     *
     */
    function summaryRechargeCard(){
        return $this->objPayLogsDAL->summaryRechargeCard();
    }
    
    
    /***
     * 销毁实体卡
     * 
     * 
     * **/
    function delTestCard($state){
        return $this->objPayLogsDAL->delTestCard($state);
    }

    //获取首页订单数据
    function orderData() {
        return $this->objPayLogsDAL->orderData();
    }
    
    
    function getPlayLog($orderid,$querytime){
        return $this->objPayLogsDAL->getplaylog($orderid,$querytime);
    }
}
?>