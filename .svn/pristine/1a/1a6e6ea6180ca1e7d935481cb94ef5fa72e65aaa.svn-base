<?php
require_once ROOT_PATH . 'Common/MySqlHelp.class.php';
require_once ROOT_PATH . 'Common/DALCache.class.php';
/**
 * 操作操作类的基类
 */
class DALBase
{
	protected $arrConfig = null;
	
    protected $objMemcache = null;	
	protected $objMasterDB = null;
	protected $objUserDB = null;
	protected $objUserDataDB = null;
	protected $objPassPwdDB = null;
	protected $objBankDataDB = null;
	protected $objOperationLogsDB = null;
	protected $objDataChangeLogsDB = null;
	protected $objStagePropertyDB = null;
	protected $objMsgDB = null;
	protected $objGameFiveDB = null;
	protected $objSystemDB = null;
	protected $objMatchDB = null;
	protected $objDB = null;
	protected $objPassAccountDB = null;
	protected $objPassSecurityDB = null;
    protected $objPayLogsDB = null;
    protected $objCDAccountDB = null;
	//客户端登陆
	protected $strSelectKeyDown = "SelectKeyDown_";
	//后台
	protected $strSelectKey = "SelectKey_";
    protected $strSelectKeySys = "SelectKeySys_";
    protected $strSelectAllKeySys = "SelectAllKeySys_";
    protected $strSelectPageKeySys = "SelectPageKeySys_";

	//创建缓存实例
    public function __construct()
    {    
        $this->objMemcache = $this->getMemcacheObj();
    }
    
    public function initDBObj($iRoleID,$iMapType,$initDB=true)
    {
    	//连接数据库
    	if($initDB)
    	{

            $this->objDB = $this->objMasterDB = $this->getMasterDBObj();
            //var_dump($this->objDB);//exit;
	    	if($iMapType>0)
	    	{
	    		$strSessionName='DB'.$iRoleID.$iMapType;
	    		//if(isset($_SESSION[$strSessionName]) && !empty($_SESSION[$strSessionName]))
	    		//	$serverInfoObj=$_SESSION[$strSessionName];
	    		//else
	    		//{
	    			$serverInfoObj = $this->getDataBaseObj($iRoleID,$iMapType);		
	    			   			
	    		//	$_SESSION[$strSessionName]=$serverInfoObj;
	    		//}   	    			    			
	    		if($iMapType==1)
		    		$this->objDB = $this->objPassAccountDB = $this->getDBObj($serverInfoObj,$iRoleID.'Z');
		    	elseif($iMapType==2)
		    		$this->objDB = $this->objUserDB = $this->getDBObj($serverInfoObj,$iRoleID.'A');
		    	elseif($iMapType==3)
		    		$this->objDB = $this->objUserDataDB = $this->getDBObj($serverInfoObj,$iRoleID.'B');
		    	elseif($iMapType==4)
		    		$this->objDB = $this->objBankDataDB = $this->getDBObj($serverInfoObj,$iRoleID.'C');
		    	elseif($iMapType==5)
		    		$this->objDB = $this->objOperationLogsDB = $this->getDBObj($serverInfoObj,$iRoleID.'D');
		    	elseif($iMapType==6)
		    		$this->objDB = $this->objDataChangeLogsDB = $this->getDBObj($serverInfoObj,$iRoleID.'E');
		    	elseif($iMapType==7)
		    		$this->objDB = $this->objMsgDB = $this->getDBObj($serverInfoObj,$iRoleID.'F');
		    	elseif($iMapType==8)  
		    		$this->objDB = $this->objStagePropertyDB = $this->getDBObj($serverInfoObj,$iRoleID.'G');
		    	elseif($iMapType==9)    		
		    		$this->objDB = $this->objGameFiveDB = $this->getDBObj($serverInfoObj,$iRoleID.'H');
		    	elseif($iMapType==10)    		
		    		$this->objDB = $this->objMatchDB = $this->getDBObj($serverInfoObj,$iRoleID.'I');
		    	elseif($iMapType==13)    		
		    		$this->objDB = $this->objSystemDB = $this->getDBObj($serverInfoObj,$iRoleID.'J');
		    	elseif($iMapType==14)    		
		    		$this->objDB = $this->objPassSecurityDB = $this->getDBObj($serverInfoObj,$iRoleID.'K');
                elseif($iMapType == 15)
                    $this->objDB = $this->objPayLogsDB = $this->getDBObj($serverInfoObj,$iRoleID,'P');
                elseif($iMapType == 16)
                    $this->objDB = $this->objBankChangeLogsDB = $this->getDBObj($serverInfoObj,$iRoleID,'Q');
                elseif($iMapType == 17)
                    $this->objDB = $this->objSetOperationLogsDB = $this->getDBObj($serverInfoObj,$iRoleID,'R');
                elseif($iMapType == 18)
               	    $this->objDB = $this->objCDAccountDB = $this->getDBObj($serverInfoObj,$iRoleID,'S');
	    	}
    	}    	
    }
    

	/**
     * 返回数据库地址
     * @param $iRoleID	角色ID
     * @param $iMapType	数据库类型
     */
    private function getDataBaseObj($iRoleID,$iMapType)
    {
       // Utility::Log("system_error", "getDataBaseObj", "iRoleID:$iRoleID iMapType:$iMapType");
    	$dbConfig = array();
        //实例化SESSION        
        //$masterDBObj=$this->getMasterDBObj();
        $params = array(array($iRoleID,SQLSRV_PARAM_IN),
				        array($iMapType,SQLSRV_PARAM_IN)
				  );
        //Utility::Log("system_error", "getDataBaseObj", json_encode($params));
        $dbConfig = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_Select",$params);
        //Utility::Log("system_error", "getDataBaseObj", json_encode($dbConfig));
        if(is_array($dbConfig) && isset($dbConfig['ErrorDescribe']))
		{
			echo '对不起,数据库连接失败.';
            Utility::Log("system_error", "getDataBaseObj", "数据库连接失败");
			exit();
		}
		else
		{
			if(empty($dbConfig['LANServerIP']) || empty($dbConfig['ServerPort']))
			{
				echo '对不起,数据库连接失败.';
                Utility::Log("system_error", "getDataBaseObj", "数据库连接失败2222");
				exit();
			}
			$arrServerIP = explode(':',$dbConfig['ServerIP']);
	        $serverInfoObj = new MySqlServerInfo();
	        $serverInfoObj->strDBHost = $arrServerIP[0];
	        $serverInfoObj->strDBPort = $arrServerIP[1];
	        $serverInfoObj->strDBUser = $dbConfig['Login'];
	        $serverInfoObj->strDBPwd  = Utility::mcryptDecrypt($this->arrConfig, $dbConfig['Pass']);
	        $serverInfoObj->strDBName = $dbConfig['AppName'];
	        $serverInfoObj->strDBCharset = 'utf8';	     
	        //返回User数据库实例
	        return $serverInfoObj;//MySqlHelp::getInstance($serverInfoObj);
		}
    }
    
	/**
     * 返回数据库操作类 
	 *@author xlj
     */
    private function getDBObj($objServerInfo,$strKey)
    {
        //print_r($objServerInfo);exit;
        return MySqlHelp::getInstance($objServerInfo,$strKey);
    }	
    
	/**
     * 返回MasterDB数据库操作类 
     * @author xuluojion
     */
    private function getMasterDBObj()
    {
        /*$pass_en  = Utility::mcryptEncrypt('', '123456');
        var_dump($pass_en);exit;*/
        $arrSysConfig = unserialize(SYS_CONFIG);
        $objServerInfo = new MySqlServerInfo();
        $objServerInfo->strDBHost = $arrSysConfig['MasterDBCONFIG']['DBHOST'];
        $objServerInfo->strDBPort = $arrSysConfig['MasterDBCONFIG']['DBPORT'];
        $objServerInfo->strDBUser = $arrSysConfig['MasterDBCONFIG']['DBUSER'];
        $objServerInfo->strDBPwd  = Utility::mcryptDecrypt('', $arrSysConfig['MasterDBCONFIG']['DBPWD']);//$arrSysConfig['MasterDBCONFIG']['DBPWD'];//Utility::mcryptDecrypt('', $arrSysConfig['MasterDBCONFIG']['DBPWD']);//
        $objServerInfo->strDBName = $arrSysConfig['MasterDBCONFIG']['DBNAME'];
        $objServerInfo->strDBCharset = $arrSysConfig['MasterDBCONFIG']['DBCHARSET'];//print_r($objServerInfo);exit;

        return MySqlHelp::getMasterInstance($objServerInfo);
    }	
    
    /**
     * 得到缓存操作类
     */
    public function getMemcacheObj()
    {
        $arrSysConfig = unserialize(SYS_CONFIG);

        $objServerInfos = new MemcacheServerInfo();
        foreach ($arrSysConfig['MEMCACHED'] as $memCacheds)
        {
            $objServerInfos->addServer(key($memCacheds),current($memCacheds));
        }
        return DALCache::getInstance($objServerInfos);
    }


    /**
     * 添加分页数据到缓存
     */
    public function setPageData2Cache($strKeyMain,$strKeySlave,$arrValue,$iCompress=0, $iExpire=0)
    {
        $arrKeys = $this->objMemcache->get($strKeyMain);//SelectPageKey_GroupStructureDAL228
        if($arrKeys)
        {
            //加入新的key
            $arrKeys[] = $strKeySlave;
            $this->objMemcache->set($strKeyMain,$arrKeys,$iCompress,$iExpire);
        }
        else
        {
            //加入新的key
            $this->objMemcache->set($strKeyMain,array($strKeySlave),$iCompress,$iExpire);
        }
        //加入新的缓存
        $this->objMemcache->set($strKeySlave,$arrValue,$iCompress,$iExpire);
    }


    /**
     * 删除当前所有分页缓存
     *
     * @param mixed $strKeyMain
     * @return
     */
    public function delPageDataFromCache($strKeyMain)
    {
        $arrKeys = $this->objMemcache->get($strKeyMain);
        if($arrKeys)
        {
            foreach ($arrKeys as $strKey)
            {
                $this->objMemcache->delete($strKey);
                /*if(!$this->objMemcache->delete($strKey))
                {
                    return FALSE;
                }*/
            }
        }
        return $this->objMemcache->delete($strKeyMain);
    }
}
//启动session
//session_start();
