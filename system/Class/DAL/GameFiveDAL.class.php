<?php
require_once __DIR__.'/DALBase.php';

class GameFiveDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['GameFive'],true);
	}	
	/**
     * 添加广告位
     * @param $arrParams
     * @return 0:成功,-1:失败
     */
	public function addAdPos($arrParams)
	{
		$iResult = -1;
		$params = array(array(intval($arrParams['PositionTypeID']), SQLSRV_PARAM_IN),
						array(intval($arrParams['PositionID']), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['PositionName']), SQLSRV_PARAM_IN),						
						array(intval($arrParams['PositionWidth']), SQLSRV_PARAM_IN),
						array(intval($arrParams['PositionHeight']), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Intro']), SQLSRV_PARAM_IN)						
						);			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdPosition_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除广告位
	 * @return 0:成功,-1:失败,-3:该广告位含有广告
	 */
	public function delAdPos($PositionID)
	{
		$iResult = -1;
		$params = array(array(intval($PositionID), SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdPosition_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
     * 读取广告位(单条记录)
     * @param $arrParams
     * @return array
     */
	public function getAdPosInfo($PositionID)
	{
		$params = array(array(intval($PositionID), SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdPosition_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['PositionName']=Utility::gb2312ToUtf8($arrReturns['PositionName']);
			$arrReturns['Intro']=Utility::gb2312ToUtf8($arrReturns['Intro']);	
			$arrReturns['Disabled']='disabled="disabled"';	
		}
		return $arrReturns;
	}
	/**
     * 读取广告位(多条记录)
     * @param $arrParams
     * @return array
     */
	public function getAdPosList()
	{
		$params = array(array(0, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAllAssoc("Proc_SysAdPosition_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['PositionName']=Utility::gb2312ToUtf8($val['PositionName']);
				$iCount++;
			}
		}
		return $arrReturns;
	}
	/**
     * 添加广告
     * @param $arrParams
     * @return 0:成功,-1:失败
     */
	public function addAd($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['AdID'], SQLSRV_PARAM_IN),						
						array(Utility::utf8ToGb2312($arrParams['AdName']), SQLSRV_PARAM_IN),
						array($arrParams['PositionID'], SQLSRV_PARAM_IN),
						array($arrParams['FileURL'], SQLSRV_PARAM_IN),
						array($arrParams['LinkURL'], SQLSRV_PARAM_IN),
						array($arrParams['StartTime'], SQLSRV_PARAM_IN),
						array($arrParams['EndTime'], SQLSRV_PARAM_IN),
						array($arrParams['SortID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Intro']), SQLSRV_PARAM_IN),
						array($arrParams['ServerID'], SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdList_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
     * 读取广告
     * @param $AdID
     * @return array
     */
	public function getAdInfo($AdID)
	{
		$params = array(array(intval($AdID), SQLSRV_PARAM_IN),
						array(2, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdList_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['AdName']=Utility::gb2312ToUtf8($arrReturns['AdTitle']);
			$arrReturns['Intro']=Utility::gb2312ToUtf8($arrReturns['Intro']);	
		}
		return $arrReturns;
	}
	/**
	 * 设置广告禁用/启用状态
	 * @param $AdID 广告ID
	 * @return 0:成功,-1:失败
	 */
	public function setAdLocked($AdID)
	{
		$iResult = -1;
		$params = array(array($AdID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdList_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;		
	}
	/**
	 * 删除广告
	 * @param $AdID 广告ID
	 * @return 0:成功,-1:失败
	 */
	public function delAd($AdID)
	{
		$iResult = -1;
		$params = array(array($AdID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_SysAdList_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;	
	}
	
	public function addNewsCategory($CateID, $CateName)
	{
		$iResult = -1;
		$params = array(array(intval($CateID), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($CateName), SQLSRV_PARAM_IN)						
						);			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_Category_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	public function delNewsCategory($CateID)
	{
		$iResult = -1;
		$params = array(array($CateID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_Category_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;	
	}
	
	public function getNewsCategory($CateID)
	{
		$arrReturns = null; 
		$params = array(array($CateID, SQLSRV_PARAM_IN));
		if($CateID){
			$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_Category_Select", $params);
			if($arrReturns){
				$arrReturns['CateName']=Utility::gb2312ToUtf8($arrReturns['CateName']);
			}
		}else{
			$arrReturns = $this->objGameFiveDB->fetchAllAssoc("Proc_Category_Select", $params);
			$i=0;
			foreach($arrReturns as $v){
				$arrReturns[$i]['CateName'] = Utility::gb2312ToUtf8($v['CateName']);
				$i++;
			}
		}
		return $arrReturns;
	}
	
	public function addNews($NewsID, $CateID, $NewsTitle, $NewsContent)
	{
		$iResult = -1;
		$params = array(array(intval($NewsID), SQLSRV_PARAM_IN),
						array(intval($CateID), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($NewsTitle), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($NewsContent), SQLSRV_PARAM_IN)						
						);			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_News_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	public function delNews($NewsID)
	{
		$iResult = -1;
		$params = array(array($NewsID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_News_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;	
	}
	
	public function getNewsDetail($NewsID)
	{
		$arrReturns = null; 
		$params = array(array($NewsID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objGameFiveDB->fetchAssoc("Proc_News_Select", $params);
		if($arrReturns){
			$arrReturns['NewsTitle']=Utility::gb2312ToUtf8($arrReturns['NewsTitle']);
			$arrReturns['NewsContent']=Utility::gb2312ToUtf8($arrReturns['NewsContent']);
		}
		return $arrReturns;
	}
}