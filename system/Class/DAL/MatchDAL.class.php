<?php
require_once __DIR__.'/DALBase.php';

class MatchDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['Match'],true);
	}	
	/**
	 * 读取66人单元赛信息
	 * @author xlj
	 * @param $MatchUnitID
	 */
	public function getGameMatchUnitInfo($MatchUnitID)
	{
		$params = array(array($MatchUnitID,SQLSRV_PARAM_IN));			
		$arrResults = $this->objMatchDB->fetchAssoc("Proc_GameMatchUnit_Select",$params);	
		return $arrResults;
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
		$params = array(array($RID,SQLSRV_PARAM_IN),
						array($RetCode,SQLSRV_PARAM_IN),
						array($RetMsg,SQLSRV_PARAM_IN));			
		$arrResults = $this->objMatchDB->fetchAssoc("Proc_UserRecharge_Update",$params);	
		return $arrResults;
	}
	
	/**
	 * 统计报表，按充值方式和金额分组汇总
	 * @author xlj
	 * @param $StartTime
	 * @param $EndTime
	 * @return array
	
	public function getRechargeDetailCount($StartTime,$EndTime)
	{
		$params = array(array($StartTime,SQLSRV_PARAM_IN),
						array($EndTime,SQLSRV_PARAM_IN));			
		$arrResults = $this->objMatchDB->fetchAllAssoc("Proc_RechargeDetail_Count",$params);
		if(empty($arrResults)) $arrResults=null;	
		return $arrResults;
	} */
}
?>