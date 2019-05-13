<?php
require ROOT_PATH . 'Class/DAL/DataCenterDAL.class.php';
class DataCenterBLL
{
    private $objDataCenterDAL = NULL;
    
    public function __construct()
    {
        $this->objDataCenterDAL = new DataCenterDAL();
    }
    /**
     * 
     * 根据CardType获取CardID
     * 
     * */ 
    public function getCardID($CardType){
       $ChargeInfo = $this->objDataCenterDAL->DCGetChargeInfo();
       if(!isset($ChargeInfo[$CardType]))
           return -1;
       else 
           return $ChargeInfo[$CardType]['CardID'];
    }
    
    /**
    * 获取游戏配置
    * */
    public function getGameConfig(){
       return $this->objDataCenterDAL->DCGetGameConfigInfo();
    }
    /**
    * 根据CardType获取CardID
    * 
    * */
    public function getCardChargeRateByType($CardType){
       $ChargeInfo = $this->objDataCenterDAL->DCGetChargeInfo();
       if(!isset($ChargeInfo[$CardType]))
           return -1;
       else 
           return $ChargeInfo[$CardType]['Rate'];
       
    }
}
?>