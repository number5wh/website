<?php
require ROOT_PATH . 'Class/DAL/PassAccountDAL.class.php';
class PassAccountBLL
{
	private $objPassAccountDAL = NULL;
	function __construct()
	{
		$this->objPassAccountDAL = new PassAccountDAL();
	}
	

	/**
	 * 搜索通行证记录
	 * @param $Key 
	 * @param $TypeID	搜索匹配条件,1:按通行证号搜索,2:按真实改名搜索,3:按身份证号搜索,4:按登陆账号搜索
	 * @return Array
	 * @author xlj
	 */
	public function getUserAccountList($Key,$TypeID)
	{
		return $this->objPassAccountDAL->getUserAccountList($Key,$TypeID);
	}
	/**
     * 重置密码
     * @param $iPassport
     * @param $newPass
     */
    public function resetPassword($iPassport,$newPass,$SecGrade)
    {
    	return $this->objPassAccountDAL->resetPassword($iPassport,$newPass,$SecGrade);
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
		return $this->objPassAccountDAL->getUserAccountInfo($Key,$TypeID);
    }
	/**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam)
	{
		return $this->objPassAccountDAL->getRecordsCount($arrParam);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageList($arrParam,$curPage)
	{
		return $this->objPassAccountDAL->getPageList($arrParam,$curPage);
	}
	/**
	 * 添加IP限制
	 * @param $arrParam
	 */
	function setSysIp($arrParam)
	{
		return $this->objPassAccountDAL->setSysIp($arrParam);
	}
	/**
	 * 删除IP限制
	 * @param $IP
	 */
	function deleteSysIp($IP)
	{
		return $this->objPassAccountDAL->deleteSysIp($IP);
	}
	/**
	 * 添加机器码限制
	 * @param $arrParam
	 */
	function setSysMs($arrParam)
	{
		return $this->objPassAccountDAL->setSysMs($arrParam);
	}
	/**
	 * 删除机器码限制
	 * @param $MS
	 */
	function deleteSysMs($MS)
	{
		return $this->objPassAccountDAL->deleteSysMs($MS);
	}
	/**
	 * 添加黑名单
	 * @param $arrParam
	 */
	function setSysBlack($arrParam)
	{
		return $this->objPassAccountDAL->setSysBlack($arrParam);
	}
	/**
	 * 删除黑名单
	 * @param $Black
	 */
	function deleteSysBlack($LimitStr)
	{
		return $this->objPassAccountDAL->deleteSysBlack($LimitStr);
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
		return $this->objPassAccountDAL->updateUserAccountInfo($Passport,$Content,$TypeID);
	}
	/**
	 * 添加IP限制段
	 * @param $arrParam
	 */
	function setSysIntervalIp($arrParam)
	{
		return $this->objPassAccountDAL->setSysIntervalIp($arrParam);
	}
	/**
	 * 删除IP限制段
	 * @param $ID
	 */
	function deleteSysIntervalIp($ID)
	{
		return $this->objPassAccountDAL->deleteSysIntervalIp($ID);
	}
	/**
	 * 删除账号
	 * @param $Passport
	 */
	public function DelPlayer($Passport)
	{
		return $this->objPassAccountDAL->DelPlayer($Passport);
	}
}
?>