<?php
require ROOT_PATH . 'Class/DAL/CommonDAL.class.php';
class CommonBLL
{
	private $objCommonDAL = NULL;
	public function __construct($MapType, $iRoleID=0)
    {
        $this->objCommonDAL = new CommonDAL($MapType,$iRoleID);
    }
    
    /**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam)
	{
		return $this->objCommonDAL->getRecordsCount($arrParam);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageList($arrParam,$curPage)
	{
		return $this->objCommonDAL->getPageList($arrParam,$curPage);
	}
	
	/**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCountSelect($arrParam)
	{
		return $this->objCommonDAL->getRecordsCountSelect($arrParam);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageListSelect($arrParam,$curPage, $useCache = true)
	{
		return $this->objCommonDAL->getPageListSelect($arrParam,$curPage, $useCache);
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageListSelectCache($arrParam,$curPage)
	{
	    return $this->objCommonDAL->getPageListSelectCache($arrParam,$curPage);
	}
	/**
     * 删除记录
	 * @param $RoleID 角色ID
	 * @param $AddTime 添加时间
	 * @param $tableName 表名
	 * @return 0:成功 -1:失败 
	 */
	function delPageListSelect($RoleID,$AddTime,$tableName)
	{
		return $this->objCommonDAL->delPageListSelect($RoleID,$AddTime,$tableName);
	}
	
	/**
	 * 删除简单分页的缓存
	 * @param $mName	缓存变量名
	 * @param $allPage	缓存分页记录数
	 */
	function delSimplePageMemcache($mName, $allPage)
	{
		$this->objCommonDAL->delSimplePageMemcache($mName, $allPage);
	}
	
	/***
	 * @param string $TableName  表明前缀
	 * @param int @RoleID  角色ID
	 *
	 * */
	public function delLogs($TableName,$RoleID){
	    return $this->objCommonDAL->delLogs($TableName, $RoleID);
	}
	/***
	 * @param string $TableName  表明前缀
	 * @param int @RoleID  角色ID
	 *
	 * */
	public function delAllLogs($TableName,$RoleID){
	    return $this->objCommonDAL->delAllLogs($TableName, $RoleID);
	}




}
?>