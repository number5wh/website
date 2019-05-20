<?php
require_once __DIR__.'/DALBase.php';

include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SvrPtl/Socket.php';
include_once ROOT_PATH.'Common/SvrPtl/PHPStream.php';
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
	 * 获取服务器列表
	 *@param $CurTime  int  当前时间
	 *@param  $ServerType int 服务器类型
	 *
	 * */
	public function DCGetServerList($CurTime,$ServerType){
	    $strSelectKeySys = $this->strSelectKeyDC ."ServerList_".$ServerType;
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
	    if(!$arrReturns)
	    {
            $socket = getSocketInstance($this->socketStr);
            SendWDGetServerList($socket, $CurTime,$ServerType);
            $out_data = $socket->response();       
            $out_array = ProcessDWGetServerListRes($out_data);
            $keyMap = array("iServerID"=>"ServerID","szServerName"=>"ServerName","szServerIP"=>"ServerIP","szServerPort"=>"ServerPort",
                "szIntro"=>"Intro","bLocked"=>"Locked","szAppName"=>"AppName","szLANServerIP"=>"LANServerIP","iServID"=>"ServID",
                "iServerType"=>"ServerType","szIP"=>"IP","szLogin"=>"Login","szPass"=>"Pass");
            $arrReturns = $this->arrListReplaceKey($out_array['ServerInfoList'], $keyMap);
	        if(isset($out_array['iCount'])&&$out_array['iCount']){
	            //设置缓存，不压缩，缓存30分钟
	            $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
	        }
	    }
	    return $arrReturns;
	}
	 
	/***
	 * 获取更新版本文件
	 * @param $CurTime int 当前时间
	 * 
	 * */
	public function DCGetGameVersion($CurTime){
	    $strSelectKeySys = $this->strSelectKeyDC ."GameVersion";
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
	    if(!$arrReturns)
	    {
	        $socket = getSocketInstance($this->socketStr);
    	    SendWDGetGameVersion($socket, $CurTime);
    	    $out_data = $socket->response();
    	    $out_array = ProcessDWGetGameVersionRes($out_data);

    	    $keyMap = array("iServerID"=>"ServerID","szFileName"=>"FileName","szFileURL"=>"FileURL","szLocalPath"=>"LocalPath",
    	        "iVerID"=>"VerID","iVerType"=>"VerType","iKindID"=>"KindID","iFileCategory"=>"FileCategory","iVersion"=>"Version",
    	        "iLastUpdateTime"=>"LastUpdateTime");
	        $arrReturns = $this->arrListReplaceKey($out_array['GameVersionList'], $keyMap);
	        if(isset($out_array['iCount'])&&$out_array['iCount']){
	            //设置缓存，不压缩，缓存30分钟
	            $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
	        }
	    }
	    return $arrReturns;
	}
	/***
	 * 获取安卓版本信息
	 * 
	 * */
	public function DCGetAndroidVersion(){
	    $strSelectKeySys = $this->strSelectKeyDC ."AndroidVersion";
	    $arrReturns = $this->objMemcache->get($strSelectKeySys);
	    if(!$arrReturns)
	    {
    	    $socket = getSocketInstance($this->socketStr);
    	    SendWDGetAndroidVersion($socket, time());
    	    $out_data = $socket->response();
    	    $out_array = ProcessDWGetAndroidVersionRes($out_data); 
    	    
            $keyMap = array("iLowVersion"=>"LowVersion","iHighVersion"=>"HighVersion","iLastUpdateTime"=>"LastUpdateTime","iServerID"=>"ServerID",
            "iVerID"=>"VerID","szFileName"=>"FileName","szFileURL"=>"FileURL",);
	        $arrReturns = $this->arrListReplaceKey($out_array['AndroidVersionList'], $keyMap);
	        if(isset($out_array['iCount'])&&$out_array['iCount']){
	            //设置缓存，不压缩，缓存30分钟
	            $this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);
	        }
	    }
	    return $arrReturns;
	}
	/**
	 * 获取游戏盾开关信息
	 */
	public function DCGetYouXiDunInfo(){
    	//echo "<br />DCGetYouXiDunInfo:<br />";
	       $socket = getSocketInstance($this->socketStr);
	       SendWDGetYouXiDunInfo($socket, time());
	       $out_data = $socket->response();
	       $out_array = ProcessDWGetYouXiDunInfoRes($out_data);
	
	       return $out_array;
	}
}
?>