<?php
require_once __DIR__.'/DALBase.php';

include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SvrPtl/Socket.php';
include_once ROOT_PATH.'Common/SvrPtl/PHPStream.php';;
include_once ROOT_PATH.'Common/SePtlDCToOW.php';
include_once ROOT_PATH.'Common/SePtlOWToDC.php';

class SafetyServiceDAL extends DALBase
{
    private  $socketStr = "DC";
    
	public function __construct()
	{
		parent::__construct();	
		$this->arrConfig=unserialize(SYS_CONFIG);
	}	
	/*
	 * 获取活动信息
	 */
	public function DCGetHomeCaijinMsg(){
		$strSelectKeySys = $this->strSelectKeyDC ."CaijinMsgInfo";
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
		if(!$arrReturns){
	      $socket = getSocketInstance($this->socketStr);
          SendWDGetHomeCaijinMsg($socket, 0);
          $out_data = $socket->response();
          $out_array = ProcessDWGetHomeCaijinMsgRes($out_data);
          $arrReturns = $out_array;
          $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
		}
        return $arrReturns;
	}
}
?>