<?php
require_once __DIR__.'/DALBase.php';

include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';
include_once ROOT_PATH.'Common/SePtlDCToOW.php';

class DataCenterDAL extends DALBase
{
    private  $socketStr = "DC";
    
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
	}	
	
	/**
	 * 获取充值信息
	 * 
	 * **/
	public function DCGetChargeInfo(){
	    $strSelectKeySys = $this->strSelectKeyDC ."ChargeInfo";
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
	    if(!$arrReturns)
	    {
    	    $socket = getSocketInstance($this->socketStr);
    	    SendWDGetChargeInfo($socket, 0);
    	    $out_data = $socket->response();
    	    $out_array = ProcessDWGetChargeInfoRes($out_data);
    	    if(isset($out_array['iCount'])&&$out_array['iCount']){
    	        $arrReturns = array();
    	        foreach ($out_array['ChargeInfoList'] as $key=>$val){
    	            $arrReturns[$val['szCardType']]['CardID'] = $val['iType'];
    	            $arrReturns[$val['szCardType']]['Rate'] = $val['iRate'];
    	        }
    	        //设置缓存，不压缩，缓存30分钟
    	        $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
    	        
    	    }
	    }
	    return $arrReturns;
	}
	
	/**
	 * 获取游戏配置信息
	 *
	 * **/
	
	public function DCGetGameConfigInfo(){
	    $strSelectKeySys = $this->strSelectKeyDC ."GameConfigInfo";
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
	    if(!$arrReturns){
	    
    	    $socket = getSocketInstance($this->socketStr);
    	    SendWDGetGameConfigInfo($socket, 0);
    	    $out_data = $socket->response();
    	    $out_array = ProcessDWGetGameConfigInfoRes($out_data);
	        if(isset($out_array['iCount'])&&$out_array['iCount']){
	            $arrReturns = array();
	            foreach ($out_array['GameConfigInfoList'] as $key=>$val){
	                $arrReturns[$val['iCfgType']] = $val['iCfgValue'];
	            }
	            //设置缓存，不压缩，缓存30分钟
	            $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
	             
	        }
	    }
	    return $arrReturns;
	}
}
?>