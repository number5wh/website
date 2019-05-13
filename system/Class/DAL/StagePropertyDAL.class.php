<?php
require_once __DIR__.'/DALBase.php';

class StagePropertyDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
		parent::initDBObj(0,$this->arrConfig['MapType']['StageProperty'],true);
	}	
	
	/**
	 * 设置游戏道具禁用/启用
	 * @param $SpID 道具ID
	 * $iResult=-1:失败,0:成功
	 */
	public function setSpPublicLocked($SpID)
	{
		$iResult = -1;
		$params = array(array($SpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StagePropertyPublic_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除游戏道具
	 * @param $SpID 道具ID
	 * @return: 0:成功,-1:失败
	 */
	public function delSpPublic($SpID)
	{
		$iResult = -1;
		$params = array(array($SpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StagePropertyPublic_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 获取道具分类
	 * @param $TypeID 道具大类,1:服装,2:道具,3:礼包,9:事件
	 * @param $SearchType 0:返回所有 ,1:按TypeID搜索道具分类,2:按ClassID搜索道具分类,3:查找子类,4:根据typeid查找一级大类
	 * @param $Locked 锁定状态
	 * @return array
	 */
	public function getSpClass($TypeID,$SearchType,$Locked)
	{		
		$params = array(array($TypeID, SQLSRV_PARAM_IN),
						array($SearchType, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_Class_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['CateName']=Utility::gb2312ToUtf8($val['CateName']);
				$iCount++;
			}				
		}
		return $arrReturns;
	}
	/**
	 * 设置道具分类禁用/启用
	 * @param $ClassID
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setSpClassLocked($ClassID)
	{
		$iResult = -1;
		$params = array(array($ClassID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Class_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加道具分类
	 * @param $arrParams
	 * $iResult=-1:失败,0:成功
	 */
	public function addSpClass($arrParams)
	{
		$iResult = -1;
		$params = array(array(Utility::utf8ToGb2312($arrParams['CateName']), SQLSRV_PARAM_IN),
						array($arrParams['TypeID'], SQLSRV_PARAM_IN),
						array($arrParams['KeyID'], SQLSRV_PARAM_IN),
						array($arrParams['Target'], SQLSRV_PARAM_IN),
						array($arrParams['ClassID'], SQLSRV_PARAM_IN),		
						array($arrParams['ParentClassID'], SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Class_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除道具分类
	 * @param $ClassID
	 * @return -1:删除失败,0:删除成功,-3:该类别下分类,-4:该类别下有道具,-5:该类别下有事件信息
	 */
	public function delSpClass($ClassID)
	{
		$iResult = 0;
		$params = array(array($ClassID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Class_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加道具
	 * @param $arrParams
	 * @return -1:失败,0:成功,-3:概率超过百分百
	 */
	public function addSpPublic($arrParams)
	{
		$params = array(array(Utility::utf8ToGb2312($arrParams['GoodsName']), SQLSRV_PARAM_IN),
						array($arrParams['SpNumber'], SQLSRV_PARAM_IN),
						array($arrParams['ResourceID'], SQLSRV_PARAM_IN),
						array($arrParams['ClassID'], SQLSRV_PARAM_IN),
						array($arrParams['ImgPath'], SQLSRV_PARAM_IN),		
						array($arrParams['ImgPath1'], SQLSRV_PARAM_IN),		
						array($arrParams['ImgPath2'], SQLSRV_PARAM_IN),						
						array($arrParams['Sex'], SQLSRV_PARAM_IN),
						array($arrParams['Level'], SQLSRV_PARAM_IN),
						array($arrParams['VipID'], SQLSRV_PARAM_IN),
						array($arrParams['KindID'], SQLSRV_PARAM_IN),
						array($arrParams['EffectiveType'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Unit']), SQLSRV_PARAM_IN),
						array($arrParams['iNumber'], SQLSRV_PARAM_IN),						
						array(Utility::utf8ToGb2312($arrParams['Intro']), SQLSRV_PARAM_IN),
						array($arrParams['SpID'], SQLSRV_PARAM_IN),
						array($arrParams['Place'], SQLSRV_PARAM_IN),
						array($arrParams['ServerID'], SQLSRV_PARAM_IN),
						array($arrParams['CustomField'], SQLSRV_PARAM_IN),
						array($arrParams['GiftProb'], SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StagePropertyPublic_Insert", $params);
		return $arrReturns;
	}
	/**
	 * 读取道具信息
	 * @param $SpID 道具ID
	 * @return array
	 */
	public function getSpPublicInfo($SpID)
	{
		$params = array(array($SpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StagePropertyPublic_SelectInfo", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['GoodsName']=Utility::gb2312ToUtf8($arrReturns['GoodsName']);
			$arrReturns['Unit']=Utility::gb2312ToUtf8($arrReturns['Unit']);
			$arrReturns['Intro']=Utility::gb2312ToUtf8($arrReturns['Intro']);
			$arrReturns['GiftProb']=round($arrReturns['GiftProb'],2);
		}
		return $arrReturns;
	}
	/**
	 * 读取指定类别下的道具
	 * @param $KeyID
	 * @param $TypeID --1:按分类ID查找,2:按道具ID查找,3:按名字搜索
	 * @return array
	 */
	public function getSpPublicList($KeyID,$TypeID)
	{
		$params = array(array($KeyID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_StagePropertyPublic_SelectList", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount=0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['GoodsName']);
				$iCount++;
			}
		}
		else 
			$arrReturns = null;
		return $arrReturns;
	}
	/**
	 * 读取事件
	 * @param $KeyID
	 * @param $TypeID --1:按分类ID查找,2:按事件ID查找
	 * @return array
	 */
	public function getEventList($KeyID,$TypeID)
	{
		$params = array(array($KeyID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_Event_SelectList", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount=0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['SpID']=Utility::gb2312ToUtf8($val['EvtID']);
				$arrReturns[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['EvtTitle']);
				$iCount++;
			}
		}
		return $arrReturns;
	}
	/**
	 * 读取商城道具信息
	 * @param $SpID 道具ID
	 * @return array
	 */
	public function getSpInfo($SpID)
	{
		$params = array(array($SpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StageProperty_SelectInfo", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['GoodsName'] = Utility::gb2312ToUtf8($arrReturns['GoodsName']);
			$arrReturns['Unit'] = Utility::gb2312ToUtf8($arrReturns['Unit']);
			$arrReturns['Intro'] = Utility::gb2312ToUtf8($arrReturns['Intro']);
			$arrReturns['StartTime'] = empty($arrReturns['StartTime']) ? '' : date('Y-m-d',strtotime($arrReturns['StartTime']));
			$arrReturns['EndTime'] = empty($arrReturns['EndTime']) ? '' : date('Y-m-d',strtotime($arrReturns['EndTime']));
			//道具使用场景
			$Place = '';
			foreach ($this->arrConfig['Place'] as $val)
			{					
				if($arrReturns['Place'] & $val['TypeID'])
					$Place .= $val['TypeName'].',';						
			}
			$arrReturns['Place'] = empty($Place) ? '' : substr($Place, 0,strlen($Place)-1);	
		}
		return $arrReturns;
	}
	/**
	 * 发布商城道具,如果不存在,则插入
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	public function setSpRelease($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['SpID'], SQLSRV_PARAM_IN),
						array($arrParams['Price'], SQLSRV_PARAM_IN),
						array($arrParams['Sex'], SQLSRV_PARAM_IN),
						array($arrParams['Level'], SQLSRV_PARAM_IN),
						array($arrParams['VipID'], SQLSRV_PARAM_IN),
						array($arrParams['IsRecommend'], SQLSRV_PARAM_IN),
						array($arrParams['SortID'], SQLSRV_PARAM_IN),
						array($arrParams['IconID'], SQLSRV_PARAM_IN),
						array($arrParams['PublicSpID'], SQLSRV_PARAM_IN),
						array($arrParams['StartTime'], SQLSRV_PARAM_IN),
						array($arrParams['EndTime'], SQLSRV_PARAM_IN),
						array($arrParams['MaxStockNum'], SQLSRV_PARAM_IN),
						array($arrParams['MaxBuyNum'], SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StageProperty_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 设置商城道具上架或下架
	 * @param $PublicSpID
	 * @return 0:成功,-1:失败
	 */
	public function setSpReleaseLocked($PublicSpID)
	{
		$iResult = -1;
		$params = array(array($PublicSpID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StageProperty_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除商城道具
	 * @param $PublicSpID
	 * $iResult=0:成功,-1:失败
	 */
	public function delSpRelease($PublicSpID)
	{
		$iResult = 1;
		$params = array(array($PublicSpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_StageProperty_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加礼包,如果不存在,则插入
	 * @param $SpID 礼包ID
	 * @param $SID 礼包里的道具或事件ID
	 * @param $Probability 概率
	 * @param $StartDate 生效日期
	 * @param $TypeID 1:服装,2:道具,9:事件
	 * @return -1:失败,0:成功
	 */
	public function addGiftPackage($SpID,$SID,$Probability,$StartDate,$TypeID)
	{
		$iResult = -1;
		$params = array(array($SpID, SQLSRV_PARAM_IN),
						array($SID, SQLSRV_PARAM_IN),
						array($Probability, SQLSRV_PARAM_IN),
						array($StartDate, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)										
						);
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_GiftPackage_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取礼包道具
	 * @param $SpID 礼包ID
	 * @return array
	 */
	public function getGiftPackage($SpID)
	{
		$arrSpList = null;
		$StartDate = '';
		$params = array(array($SpID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_GiftPackage_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount=0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['GoodsName']=Utility::gb2312ToUtf8($val['GoodsName']);
				$arrReturns[$iCount]['Probability']=round($val['Probability'],2);
				$iCount++;
			}
			//$StartDate = $arrReturns['StartDate'];
			//$arrSpList = $this->getSpPublicList($arrReturns['SpIDList'],2);			
		}
		//$arrResult = array('SpList'=>$arrSpList,'StartDate'=>$StartDate);
		return $arrReturns;
	}
	/**
	 * 删除礼包
	 * @param $ID 礼包ID
	 * @param $TypeID	1:删除礼包,2:删除礼包里的道具或事件
	 * @return -1:失败,0:成功
	 */
	public function delGiftPackage($ID,$TypeID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_GiftPackage_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加房间掉落道具,如果不存在,则插入
	 * @param $RoomID 房间ID
	 * @param $SpID 公库道具ID
	 * @param $Status 4:获胜发放
	 * @param $Probability 掉落概率
	 * @return 0:成功,-1:失败
	 */
	public function setPresentRoomSp($RoomID,$SpID,$Status,$Probability)
	{
		$iResult = -1;
		$params = array(array($RoomID, SQLSRV_PARAM_IN),
						array($SpID, SQLSRV_PARAM_IN),
						array($Status, SQLSRV_PARAM_IN),
						array($Probability, SQLSRV_PARAM_IN)			
						);	
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_PresentRoom_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取房间掉落道具
	 * @param $RoomID 房间ID
	 * @return array
	 */
	public function getPresentRoomSp($RoomID)
	{
		$params = array(array($RoomID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_PresentRoom_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount=0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['GoodsName'] = Utility::gb2312ToUtf8($val['GoodsName']);
				$arrReturns[$iCount]['Probability'] = round($val['Probability'],2);
				$iCount++;
			}		
		}
		return $arrReturns;
	}
	/**
	 * 删除房间掉落道具
	 * @param $ID 
	 * @param $TypeID 1:根据ID删除,2:根据房间ID删除
	 * @return 0:成功,-1:失败
	 */
	public function delPresentRoomSp($ID,$TypeID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_PresentRoom_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加事件
	 * @param unknown_type $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addEvent($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['EvtID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['EvtTitle']), SQLSRV_PARAM_IN),						
						array(Utility::utf8ToGb2312($arrParams['EvtRule']), SQLSRV_PARAM_IN),
						array($arrParams['ClassID'], SQLSRV_PARAM_IN),
						array($arrParams['SubClassID'], SQLSRV_PARAM_IN)						
						);
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Event_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 禁用/启用事件
	 * @param $EvtID
	 * @return 0:成功,-1:失败
	 */
	public function setEventLocked($EvtID)
	{
		$params = array(array($EvtID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Event_UpdateLocked", $params);
		return $arrReturns;
	}
	/**
	 * 删除事件
	 * @param $EvtID
	 * @return -1:失败,0:成功
	 */
	public function delEvent($EvtID)
	{
		$iResult = -1;
		$params = array(array($EvtID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_Event_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加事件属性
	 * @param unknown_type $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addEventDetail($arrParams)
	{
		$params = array(array($arrParams['EvtID'], SQLSRV_PARAM_IN),
						array($arrParams['ObjID'], SQLSRV_PARAM_IN),
						array($arrParams['iNumber'], SQLSRV_PARAM_IN),
						array($arrParams['Probability'], SQLSRV_PARAM_IN),
						array($arrParams['ClassID'], SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_EventDetail_Insert", $params);
		return $arrReturns;
	}
	/**
	 * 删除事件属性
	 * @param $ID
	 * @return -1:失败,0:成功
	 */
	public function delEventDetail($ID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objStagePropertyDB->fetchAssoc("Proc_EventDetail_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取事件属性
	 * @param $EvtID
	 * @return array
	 */
	public function getEventDetailList($EvtID)
	{
		$params = array(array($EvtID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_EventDetail_SelectList", $params);
		if(empty($arrReturns)) $arrReturns=null;
		return $arrReturns;
	}
	
	/**
	 * 根据道具名称搜索类似的道具
	 * @param $strGoodsName
	 */
	public function searchSpPublicInfo($strGoodsName)
	{
		$params = array(array(Utility::utf8ToGb2312($strGoodsName), SQLSRV_PARAM_IN),
						array(3, SQLSRV_PARAM_IN));
		//print_r($expression)
		$arrReturns = $this->objStagePropertyDB->fetchAllAssoc("Proc_StagePropertyPublic_SelectList", $params);
		
		return $arrReturns;
	}
	
	/**
	 * 获取道具服装的详细信息
	 * @param $spID 道具ID
	 * @author blj
	 */
	public function getSPDetailInfo($spID)
	{
		//先从缓存读取，如果缓存中没有数据，再从数据库读取
		/*$strSelectKey = $this->strSelectKey . $this->iRoleID . $spID . 'MallSPDetailInfo';
		$arrResults = $this->objMemcache->get($strSelectKey);
		if(!$arrResults)
	    {*/
			$params = array(array($spID, SQLSRV_PARAM_IN),
							array(2, SQLSRV_PARAM_IN));
			
			$arrResult = $this->objStagePropertyDB->fetchAssoc("Proc_StagePropertyPublic_SelectList", $params);
			
			//设置缓存，不压缩，缓存30分钟
			//$this->objMemcache->set($strSelectKey,$result,0,1800);
			return $arrResult;	
	    /*}
		return $arrResults;*/
	}	
	 
	
}