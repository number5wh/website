<?php
require ROOT_PATH . 'Class/DAL/PassSecurityDAL.class.php';
class PassSecurityBLL
{
	private $objPassSecurityDAL = NULL;
	function __construct()
	{
		$this->objPassSecurityDAL = new PassSecurityDAL();
	}
	

	/**
	 * 搜索通行证记录
	 * @param $strWhere 	
	 * @return Array
	 * @author xlj
	 */
	public function getSecurityInfoList($strWhere)
	{
		return $this->objPassSecurityDAL->getSecurityInfoList($strWhere);
	}
	/**
	 * 搜索通行证安全产品记录
	 * @author xlj
	 */
	public function getSecurityInfo($Passport,$SID)
	{
		return $this->objPassSecurityDAL->getSecurityInfo($Passport,$SID);
	}
	
	/**
	 * 删除通行证安全产品记录
	 * @return 0:成功,-1:失败
	 * @author xlj
	 */
	public function deleteSecurityInfo($Passport,$SID)
	{
		return $this->objPassSecurityDAL->deleteSecurityInfo($Passport,$SID);
	}
	/**
	 * 删除通行证安全产品信息
	 * @return 0:成功,-1:失败
	 * @author xlj
	 */
	public function DelPlayer($Passport)
	{
		return $this->objPassSecurityDAL->DelPlayer($Passport);
	}
}
?>