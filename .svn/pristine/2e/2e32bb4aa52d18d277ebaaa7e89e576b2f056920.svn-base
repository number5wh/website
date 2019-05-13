<?php
require_once __DIR__.'/DALBase.php';

class PassAccountDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
        //var_dump($this->arrConfig['MapType']['Pass']);
		parent::initDBObj(0,$this->arrConfig['MapType']['Pass'],true);
	}

	/**
	 * 搜索通行证记录
	 * @param $Key 
	 * @param $TypeID	搜索匹配条件,1:按通行证号搜索,2:按真实改名搜索,3:按身份证号搜索,4:按登陆账号搜索,5:根据登陆IP查询，6：根据QQ查询
	 * @return Array
	 * @author xlj
	 */
	public function getUserAccountList($Key,$TypeID)
	{
		$params = array(array($Key, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);
        //var_dump("test");
		$arrResult = $this->objPassAccountDB->fetchAllAssoc("Proc_UserAccount_SelectList", $params);
        //var_dump($arrResult);
		if(empty($arrResult)) $arrResult=null;	
		return $arrResult;	
	}
	/**
     * 重置密码
     * @param $iPassport
     * @param $newPass
     */
    public function resetPassword($iPassport,$newPass,$SecGrade)
    {
    	$params = array(array($iPassport, SQLSRV_PARAM_IN),
						array(md5($newPass), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($SecGrade), SQLSRV_PARAM_IN),
						array('', SQLSRV_PARAM_IN)
						);
		
		$arrResults = $this->objPassAccountDB->fetchAssoc("Proc_Pwd_ResetPwd", $params);
    	if(is_array($arrResults) && count($arrResults)>0)
    	{
    		return $arrResults['iResult'];
    	}else {
    		return -1;
    	}
    }
	/**
	 * 搜索通行证信息
	 * @param $Key 
	 * @param $TypeID	1:根据PassID查询,2:根据Passport查询
	 * @return Array
	 * @author xlj
	 */
	public function getUserAccountInfo($Key,$TypeID)
	{
		$params = array(array($Key, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_UserAccount_SelectInfo", $params);
		if(empty($arrResult)) $arrResult=null;	
		return $arrResult;	
	}
	/**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam)
	{
		$iRecordsCount = 0;
		$params = array(array($arrParam['tableName'], SQLSRV_PARAM_IN),
						array($arrParam['where'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_GetRecordCount", $params);
		if(is_array($arrResult) && count($arrResult)>0)
			$iRecordsCount = $arrResult['RecordsCount'];
		return $iRecordsCount;	
	}
	
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageList($arrParam,$curPage)
	{
		$params = array(array($arrParam['fields'], SQLSRV_PARAM_IN),
						array($arrParam['tableName'], SQLSRV_PARAM_IN),
						array($arrParam['where'], SQLSRV_PARAM_IN),
						array($arrParam['order'], SQLSRV_PARAM_IN),
						array($curPage, SQLSRV_PARAM_IN),
						array($arrParam['pagesize'], SQLSRV_PARAM_IN)		
						);
		$arrResult = $this->objPassAccountDB->fetchAllAssoc("Proc_GetPageList", $params);
		if(empty($arrResult)) $arrResult=null;
		//print_r($arrResult);
		return $arrResult;	
	}
	/**
	 * 添加IP限制
	 * @param $arrParam
	 */
	public function setSysIp($arrParam)
	{
		$iResult = -1;
		$params = array(array($arrParam['IP'], SQLSRV_PARAM_IN),
						array($arrParam['Interval'], SQLSRV_PARAM_IN),
						array($arrParam['Times'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_LimitIP_Insert", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除IP限制
	 * @param $IP
	 */
	public function deleteSysIp($IP)
	{
		$iResult = -1;
		$params = array(array($IP, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_LimitIP_Delete", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 添加机器码限制
	 * @param $arrParam
	 */
	public function setSysMs($arrParam)
	{
		$iResult = -1;
		$params = array(array($arrParam['MS'], SQLSRV_PARAM_IN),
						array($arrParam['Interval'], SQLSRV_PARAM_IN),
						array($arrParam['Times'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_LimitMS_Insert", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除机器码限制
	 * @param $MS
	 */
	public function deleteSysMs($MS)
	{
		$iResult = -1;
		$params = array(array($MS, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_LimitMS_Delete", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 添加黑名单
	 * @param $arrParam
	 */
	public function setSysBlack($arrParam)
	{
		$iResult = -1;
		$params = array(array($arrParam['LimitStr'], SQLSRV_PARAM_IN),
						array($arrParam['TypeID'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_Blacklist_Insert", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除黑名单
	 * @param $LimitStr
	 */
	public function deleteSysBlack($LimitStr)
	{
		$iResult = -1;
		$params = array(array($LimitStr, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_Blacklist_Delete", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 修改手机
	 * @param $Passport
	 * @param $Content 修改内容
	 * @param $TypeID 1:修改真实姓名,2:修改身份证号,3:修改认证手机
	 * @return -1:失败,0:成功
	 */
	public function updateUserAccountInfo($Passport,$Content,$TypeID)
	{
		$iResult = -1;
		$params = array(array($Passport, SQLSRV_PARAM_IN),
						array($Content, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_UserAccount_UpdateInfo", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 添加IP限制段
	 * @param $arrParam
	 */
	public function setSysIntervalIp($arrParam)
	{
		$iResult = -1;
		$params = array(array($arrParam['StartIP'], SQLSRV_PARAM_IN),
						array($arrParam['EndIP'], SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_IntervalIP_Insert", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除IP限制段
	 * @param $ID
	 */
	public function deleteSysIntervalIp($ID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_IntervalIP_Delete", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除账号
	 * @param $Passport
	 */
	public function DelPlayer($Passport)
	{
		$iResult = -1;
		$params = array(array($Passport, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassAccountDB->fetchAssoc("Proc_UserAccount_Delete", $params);
		if(!empty($arrResult)) $iResult=$arrResult['iResult'];	
		return $iResult;	
	}
}
?>