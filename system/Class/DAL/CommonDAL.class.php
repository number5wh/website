<?php
require_once __DIR__ . '/DALBase.php';

class CommonDAL extends DALBase {
	private $iRoleID = 0;
	public function __construct($MapType, $iRoleID) {
		parent::__construct();
		$this->arrConfig = unserialize(SYS_CONFIG);
		$this->iRoleID = $iRoleID;
		parent::initDBObj($this->iRoleID, $MapType, true);
	}

	/**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCount($arrParam) {
		$iRecordsCount = 0;
		//如果是金币排行调用,先从缓存取
		$strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'] . $arrParam['where']) . 'RecordsCount';
		if (isset($arrParam['function']) && $arrParam['function'] == 'HappyBeanSort') {
			$iRecordsCount = $this->objMemcache->get($strSelectKeySys);
		}

		if (!$iRecordsCount) {
			$params = array(array($arrParam['tableName'], SQLSRV_PARAM_IN),
				array($arrParam['where'], SQLSRV_PARAM_IN),
			);
			$arrResult = $this->objDB->fetchAssoc("Proc_GetRecordsCount", $params);
			if (is_array($arrResult) && count($arrResult) > 0) {
				$iRecordsCount = $arrResult['RecordsCount'];
			}

			//设置缓存，不压缩，缓存30分钟
			$this->objMemcache->set($strSelectKeySys, $iRecordsCount, 0, $this->arrConfig['CacheTime']);
		}
		return $iRecordsCount;
	}

	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageList($arrParam, $curPage) {
		$arrResult = null;
		//如果是金币排行调用,先从缓存取
		$strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'] . $arrParam['fields']) . $curPage . 'PageList';
		if (isset($arrParam['function'])) {
			if ($arrParam['function'] == 'HappyBeanSort' || $arrParam['function'] == 'HappyBeanSortTop100') {
				$strSelectKeySys .= $arrParam['function'];
				$arrResult = $this->objMemcache->get($strSelectKeySys);
			}
		}

		if (!$arrResult) {
			$params = array(array($arrParam['fields'], SQLSRV_PARAM_IN),
				array($arrParam['tableName'], SQLSRV_PARAM_IN),
				array($arrParam['where'], SQLSRV_PARAM_IN),
				array($arrParam['order'], SQLSRV_PARAM_IN),
				array($curPage, SQLSRV_PARAM_IN),
				array($arrParam['pagesize'], SQLSRV_PARAM_IN),
			);

			$arrResult = $this->objDB->fetchAllAssoc("Proc_GetPages", $params);
			//设置缓存，不压缩，缓存30分钟
			$this->objMemcache->set($strSelectKeySys, $arrResult, 0, $this->arrConfig['CacheTime']);
		}
		if (empty($arrResult)) {
			$arrResult = null;
		}

		//print_r($arrResult);
		return $arrResult;
	}

	/**
	 * 总记录数
	 * @param $arrParam
	 */
	function getRecordsCountSelect($arrParam) {
		$iRecordsCount = 0;
		//先从缓存取
		//$strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'].$arrParam['where']) . 'RecordsCountSelect';
		//$iRecordsCount = $this->objMemcache->get($strSelectKeySys);
		if (!$iRecordsCount) {
			$params = array(array($arrParam['tableName'], SQLSRV_PARAM_IN),
				array($arrParam['where'], SQLSRV_PARAM_IN),
			);
			$arrResult = $this->objDB->fetchAssoc("Proc_SelectRecordsCount", $params);
			if (is_array($arrResult) && count($arrResult) > 0) {
				$iRecordsCount = $arrResult['RecordsCount'];
			}

			//设置缓存，不压缩，缓存30分钟
			//$this->objMemcache->set($strSelectKeySys,$iRecordsCount,0,$this->arrConfig['CacheTime']);
		}
		return $iRecordsCount;
	}

	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
     * @param $useCache
	 */
	function getPageListSelect($arrParam, $curPage, $useCache = true) {
		$arrResult = null;
		//如果是金币排行调用,先从缓存取
		//$strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'].$arrParam['fields'].$arrParam['order']).$curPage . 'PageListSelect';
		//$arrResult = $this->objMemcache->get($strSelectKeySys);
        if ($useCache) {
            $strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'] . $arrParam['fields'] . $arrParam['where'] . $arrParam['order']) . $curPage . 'PageListSelect';
            $arrResult = $this->objMemcache->get($strSelectKeySys);
        }

		if (!$arrResult) {
			$params = array(array($arrParam['fields'], SQLSRV_PARAM_IN),
				array($arrParam['tableName'], SQLSRV_PARAM_IN),
				array($arrParam['where'], SQLSRV_PARAM_IN),
				array($arrParam['order'], SQLSRV_PARAM_IN),
				array($curPage, SQLSRV_PARAM_IN),
				array($arrParam['pagesize'], SQLSRV_PARAM_IN),
			);
			$arrResult = $this->objDB->fetchAllAssoc("Proc_SelectPages", $params);
			//设置缓存，不压缩，缓存60分钟
            if ($useCache) {
                $this->objMemcache->set($strSelectKeySys, $arrResult, 0, 600);
            }

		}
		if (empty($arrResult)) {
			$arrResult = null;
		}

		//print_r($arrResult);
		return $arrResult;
	}
	/**
	 * 分页
	 * @param $arrParam
	 * @param $page
	 */
	function getPageListSelectCache($arrParam, $curPage) {
		$arrResult = null;
		//如果是金币排行调用,先从缓存取
		$strSelectKeySys = $this->strSelectKeySys . md5($arrParam['tableName'] . $arrParam['fields'] . $arrParam['order']) . $curPage . 'PageListSelect';
		$arrResult = $this->objMemcache->get($strSelectKeySys);
		if (!$arrResult) {
			$arrResult = $this->getPageListSelect($arrParam, $curPage);
			$this->objMemcache->set($strSelectKeySys, $arrResult, 0, $this->arrConfig['CacheTime']);
		}
		return $arrResult;
	}
	/**
	 * 删除记录
	 * @param $RoleID 角色ID
	 * @param $AddTime 添加时间
	 * @param $tableName 表名
	 * @return 0:成功 -1:失败
	 */
	function delPageListSelect($RoleID, $AddTime, $tableName) {
		$iResult = -1;
		$params = array(
			array($RoleID, SQLSRV_PARAM_IN),
			array($AddTime, SQLSRV_PARAM_IN),
			array($tableName, SQLSRV_PARAM_IN),
		);
		$arrReturns = $this->objDB->fetchAssoc("Proc_Pages_Delete", $params);
		if (is_array($arrReturns) && count($arrReturns) > 0) {
			$iResult = $arrReturns['iResult'];
		}

		return $iResult;
	}

	/**
	 * 删除简单分页的缓存
	 * @param $mName	缓存变量名
	 * @param $allPage	缓存分页记录数
	 */
	function delSimplePageMemcache($mName, $allPage) {
		for ($i = 1; $i <= $allPage; $i++) {
			$memName = $this->iRoleID . $mName . $i;
			$this->objMemcache->delete($memName);
		}
	}
	/***
		 * @param string $TableName  表明前缀
		 * @param int @RoleID  角色ID
		 *
	*/
	public function delLogs($TableName, $RoleID) {
		$params = array(
			array($TableName, SQLSRV_PARAM_IN),
			array($RoleID, SQLSRV_PARAM_IN),
		);
		$arrResult = $this->objDB->fetchAssoc('Proc_Logs_Delete', $params);
		return $arrResult;
	}
	/***
		 * @param string $TableName  表明前缀
		 * @param int @RoleID  角色ID
		 *
	*/
	public function delAllLogs($TableName, $RoleID) {
		$params = array(
			array($TableName, SQLSRV_PARAM_IN),
			array($RoleID, SQLSRV_PARAM_IN),
		);
		$arrResult = $this->objDB->fetchAssoc('Proc_Logs_DeleteAll', $params);
		return $arrResult;
	}


}