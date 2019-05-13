<?php
require_once __DIR__.'/DALBase.php';

class PassSecurityDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['PassSecurity'],true);
	}

	/**
	 * 搜索通行证记录
	 * @param $strWhere 
	 * @param $TypeID	搜索匹配条件,1:按通行证号搜索,2:按真实改名搜索,3:按身份证号搜索,4:按登陆账号搜索
	 * @return Array
	 * @author xlj
	 */
	public function getSecurityInfoList($strWhere)
	{
		$params = array(array($strWhere, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassSecurityDB->fetchAllAssoc("Proc_SecurityInfo_SelectList", $params);
		if(empty($arrResult)) $arrResult=null;	
		return $arrResult;	
	}
	
	/**
	 * 搜索通行证安全产品记录
	 * @author xlj
	 */
	public function getSecurityInfo($Passport,$SID)
	{
		$params = array(array($Passport, SQLSRV_PARAM_IN),
						array($SID, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassSecurityDB->fetchAssoc("Proc_SecurityInfo_SelectInfo", $params);
		if(empty($arrResult)) $arrResult=null;	
		return $arrResult;	
	}
	
	/**
	 * 删除通行证安全产品记录
	 * @return 0:成功,-1:失败
	 * @author xlj
	 */
	public function deleteSecurityInfo($Passport,$SID)
	{
		$iResult = -1;
		$params = array(array($Passport, SQLSRV_PARAM_IN),
						array($SID, SQLSRV_PARAM_IN)
						);
		$arrResult = $this->objPassSecurityDB->fetchAssoc("Proc_SecurityInfo_Delete", $params);
		if(!empty($arrResult)) $iResult = $arrResult['iResult'];	
		return $iResult;	
	}
	/**
	 * 删除通行证安全产品信息
	 * @return 0:成功,-1:失败
	 * @author xlj
	 */
	public function DelPlayer($Passport)
	{
		$params = array(array($Passport, SQLSRV_PARAM_IN));
		$this->objPassSecurityDB->fetchAssoc("Proc_PassSecurity_Delete", $params);
	}
}
?>