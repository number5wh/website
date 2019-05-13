<?php
require ROOT_PATH . 'Class/DAL/MatchDAL.class.php';
class MatchBLL
{
	private $objMatchDAL = NULL;
	public function __construct()
    {
        $this->objMatchDAL = new MatchDAL();
    }
	
	/**
	 * 读取66人单元赛信息
	 * @author xlj
	 * @param $MatchUnitID
	 */
	public function getGameMatchUnitInfo($MatchUnitID)
	{
		return $this->objMatchDAL->getGameMatchUnitInfo($MatchUnitID);
	}
	/**
	 * 设置玩家手机充值的状态
	 * @author xlj
	 * @param $RID
	 * @param $RetCode 充值状态
	 * @param $RetMsg 充值结果描述
	 * @return 0:成功,-1:失败
	 */
	public function setRecharge($RID,$RetCode,$RetMsg)
	{
		return $this->objMatchDAL->setRecharge($RID,$RetCode,$RetMsg);
	}
	
	/**
	 * 统计报表，按充值方式和金额分组汇总
	 * @author xlj
	 * @param $StartTime
	 * @param $EndTime
	 * @return array
	 
	public function getRechargeDetailCount($StartTime,$EndTime)
	{
		return $this->objMatchDAL->getRechargeDetailCount($StartTime,$EndTime);
	}*/
	
}
?>