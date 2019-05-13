<?php
require ROOT_PATH . 'Class/DAL/GameFiveDAL.class.php';
class GameFiveBLL
{
	private $objGameFiveDAL = NULL;
	public function __construct()
    {
        $this->objGameFiveDAL = new GameFiveDAL();
    }
	/**
     * 添加广告位
     * @param $arrParams
     * @return 0:成功,-1:失败
     */
	public function addAdPos($arrParams)
	{
		return $this->objGameFiveDAL->addAdPos($arrParams);
	}
	/**
     * 读取广告位(单条记录)
     * @param $arrParams
     * @return array
     */
	public function getAdPosInfo($PositionID)
	{
		return $this->objGameFiveDAL->getAdPosInfo($PositionID);
	}
	/**
     * 读取广告位(多条记录)
     * @param $arrParams
     * @return array
     */
	public function getAdPosList()
	{
		return $this->objGameFiveDAL->getAdPosList();
	}
	/**
	 * 删除广告位
	 * @return 0:成功,-1:失败,-3:该广告位含有广告
	 */
	public function delAdPos($PositionID)
	{
		return $this->objGameFiveDAL->delAdPos($PositionID);
	}
    /**
     * 添加广告
     * @param $arrParams
     * @return 0:成功,-1:失败
     */
	public function addAd($arrParams)
	{
		return $this->objGameFiveDAL->addAd($arrParams);
	}
	/**
     * 读取广告
     * @param $AdID
     * @return array
     */
	public function getAdInfo($AdID)
	{
		return $this->objGameFiveDAL->getAdInfo($AdID);
	}
	/**
	 * 设置广告禁用/启用状态
	 * @param $AdID 广告ID
	 * @return 0:成功,-1:失败
	 */
	public function setAdLocked($AdID)
	{
		return $this->objGameFiveDAL->setAdLocked($AdID);
	}
	/**
	 * 删除广告
	 * @param $AdID 广告ID
	 * @return 0:成功,-1:失败
	 */
	public function delAd($AdID)
	{
		return $this->objGameFiveDAL->delAd($AdID);
	}
	
	public function addNewsCategory($CateID, $CateName)
	{
		return $this->objGameFiveDAL->addNewsCategory($CateID, $CateName);
	}
	
	public function delNewsCategory($CateID)
	{
		return $this->objGameFiveDAL->delNewsCategory($CateID);
	}
	
	public function getNewsCategory($CateID)
	{
		return $this->objGameFiveDAL->getNewsCategory($CateID);
	}
	
	public function addNews($NewsID, $CateID, $NewsTitle, $NewsContent)
	{
		return $this->objGameFiveDAL->addNews($NewsID, $CateID, $NewsTitle, $NewsContent);
	}
	
	public function delNews($NewsID)
	{
		return $this->objGameFiveDAL->delNews($NewsID);
	}
	
	public function getNewsDetail($NewsID)
	{
		return $this->objGameFiveDAL->getNewsDetail($NewsID);
	}

}
?>