<?php
require ROOT_PATH . 'Class/DAL/StagePropertyDAL.class.php';
class StagePropertyBLL
{
	private $objStagePropertyDAL = NULL;
	public function __construct()
    {
        $this->objStagePropertyDAL = new StagePropertyDAL();
    }    
	
	/**
	 * 设置公库道具禁用/启用
	 * @param $SpID 道具ID
	 * @return -1:失败,0:成功
	 */
	public function setSpPublicLocked($SpID)
	{
		return $this->objStagePropertyDAL->setSpPublicLocked($SpID);
	}
	/**
	 * 删除公库道具
	 * @param $SpID 道具ID
	 * @return: 0:成功,-1:失败
	 */
	public function delSpPublic($SpID)
	{
		return $this->objStagePropertyDAL->delSpPublic($SpID);
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
		return $this->objStagePropertyDAL->getSpClass($TypeID,$SearchType,$Locked);
	}
	/**
	 * 设置道具分类禁用/启用
	 * @param $ClassID
	 * $iResult=0:失败,-2:数据库异常,大于0:成功
	 */
	public function setSpClassLocked($ClassID)
	{
		return $this->objStagePropertyDAL->setSpClassLocked($ClassID);
	}
	/**
	 * 添加道具分类
	 * @param $arrParams
	 * $iResult=-1:失败,0:成功
	 */
	public function addSpClass($arrParams)
	{
		return $this->objStagePropertyDAL->addSpClass($arrParams);
	}
	/**
	 * 删除道具分类
	 * @param $ClassID
	 * @return -1:删除失败,0:删除成功,-3:该类别下分类,-4:该类别下有道具,-5:该类别下有事件信息
	 */
	public function delSpClass($ClassID)
	{
		return $this->objStagePropertyDAL->delSpClass($ClassID);
	}
	/**
	 * 添加公库道具
	 * @param $arrParams
	 * @return -1:失败,0:成功,-3:概率超过百分百
	 */
	public function addSpPublic($arrParams)
	{
		return $this->objStagePropertyDAL->addSpPublic($arrParams);
	}
	/**
	 * 读取公库道具信息
	 * @param $SpID 道具ID
	 * @return array
	 */
	public function getSpPublicInfo($SpID)
	{
		return $this->objStagePropertyDAL->getSpPublicInfo($SpID);
	}
	/**
	 * 读取指定类别下的道具
	 * @param $KeyID
	 * @param $TypeID --1:按分类ID查找,2:按道具ID查找
	 * @return array
	 */
	public function getSpPublicList($KeyID,$TypeID)
	{
		return $this->objStagePropertyDAL->getSpPublicList($KeyID,$TypeID);
	}
	/**
	 * 读取事件
	 * @param $KeyID
	 * @param $TypeID --1:按分类ID查找,2:按事件ID查找
	 * @return array
	 */
	public function getEventList($KeyID,$TypeID)
	{
		return $this->objStagePropertyDAL->getEventList($KeyID,$TypeID);
	}
	/**
	 * 读取商城道具信息
	 * @param $SpID 道具ID
	 * @return array
	 */
	public function getSpInfo($SpID)
	{
		return $this->objStagePropertyDAL->getSpInfo($SpID);
	}
	/**
	 * 发布商城道具,如果不存在,则插入
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	public function setSpRelease($arrParams)
	{
		return $this->objStagePropertyDAL->setSpRelease($arrParams);
	}
	/**
	 * 设置商城道具上架或下架
	 * @param $PublicSpID
	 * @return 0:成功,-1:失败
	 */
	public function setSpReleaseLocked($PublicSpID)
	{
		return $this->objStagePropertyDAL->setSpReleaseLocked($PublicSpID);
	}
	/**
	 * 删除商城道具
	 * @param $PublicSpID
	 * $iResult=0:成功,-1:失败
	 */
	public function delSpRelease($PublicSpID)
	{
		return $this->objStagePropertyDAL->delSpRelease($PublicSpID);
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
		return $this->objStagePropertyDAL->addGiftPackage($SpID,$SID,$Probability,$StartDate,$TypeID);
	}
	/**
	 * 读取礼包道具
	 * @param $SpID 礼包ID
	 * @return array
	 */
	public function getGiftPackage($SpID)
	{
		return $this->objStagePropertyDAL->getGiftPackage($SpID);
	}
	/**
	 * 删除礼包
	 * @param $ID 礼包ID
	 * @param $TypeID	1:删除礼包,2:删除礼包里的道具或事件
	 * @return -1:失败,0:成功
	 */
	public function delGiftPackage($ID,$TypeID)
	{
		return $this->objStagePropertyDAL->delGiftPackage($ID,$TypeID);
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
		return $this->objStagePropertyDAL->setPresentRoomSp($RoomID,$SpID,$Status,$Probability);
	}
	/**
	 * 读取房间掉落道具
	 * @param $RoomID 房间ID
	 * @return array
	 */
	public function getPresentRoomSp($RoomID)
	{
		return $this->objStagePropertyDAL->getPresentRoomSp($RoomID);
	}
	/**
	 * 删除房间掉落道具
	 * @param $ID 
	 * @param $TypeID 1:根据ID删除,2:根据房间ID删除
	 * @return 0:成功,-1:失败
	 */
	public function delPresentRoomSp($ID,$TypeID)
	{
		return $this->objStagePropertyDAL->delPresentRoomSp($ID,$TypeID);
	}
	/**
	 * 添加事件
	 * @param unknown_type $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addEvent($arrParams)
	{
		return $this->objStagePropertyDAL->addEvent($arrParams);
	}
	/**
	 * 禁用/启用事件
	 * @param $EvtID
	 * @return 0:成功,-1:失败
	 */
	public function setEventLocked($EvtID)
	{
		return $this->objStagePropertyDAL->setEventLocked($EvtID);
	}
	/**
	 * 删除事件
	 * @param $EvtID
	 * @return -1:失败,0:成功
	 */
	public function delEvent($EvtID)
	{
		return $this->objStagePropertyDAL->delEvent($EvtID);
	}
	/**
	 * 添加事件属性
	 * @param unknown_type $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addEventDetail($arrParams)
	{
		return $this->objStagePropertyDAL->addEventDetail($arrParams);
	}
	/**
	 * 删除事件属性
	 * @param $ID
	 * @return -1:失败,0:成功
	 */
	public function delEventDetail($ID)
	{
		return  $this->objStagePropertyDAL->delEventDetail($ID);
	}
	/**
	 * 读取事件属性
	 * @param $EvtID
	 * @return array
	 */
	public function getEventDetailList($EvtID)
	{
		return $this->objStagePropertyDAL->getEventDetailList($EvtID);
	}
	
	/**
	 * 根据道具名称搜索类似的道具
	 * @param $strGoodsName
	 */
	public function searchSpPublicInfo($strGoodsName)
	{
		return $this->objStagePropertyDAL->searchSpPublicInfo($strGoodsName);
	}
	
	/**
	 * 获取道具服装的详细信息
	 * @author blj
	 * @param $spID 道具ID
	 */
	public function getSPDetailInfo($spID)
	{
		return $this->objStagePropertyDAL->getSPDetailInfo($spID);
	}

	
	
}
?>