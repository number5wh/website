<?php
require_once __DIR__.'/DALBase.php';

class MasterDAL extends DALBase
{
	public function __construct()
	{
		parent::__construct();
		$this->arrConfig=unserialize(SYS_CONFIG);		
		parent::initDBObj(0,0,true);
	}	
	/**
	 * 管理员登陆
	 * @param $strUserName 用户名
	 * @param $strUserPwd  密码
	 * @return -1:密码错误,0:用户名不存在,1:成功
	 */
	function chkAdminLogin($strUserName,$strUserPwd,$bindaccout)
	{
		$params = array(array($strUserName, SQLSRV_PARAM_IN),
						array($strUserPwd, SQLSRV_PARAM_IN),		                
						array(Utility::getIP(), SQLSRV_PARAM_IN),
		                array($bindaccout, SQLSRV_PARAM_IN)
					   );
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_Select", $params);
		return $arrReturns;
	}



	function getRoleSession($roleid){
        $params = array(
            array($roleid, SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RoleSessionId_select", $params);
        return $arrReturns;
    }



    function setRoleSession($roleid,$sessionId){
        $params = array(
            array($roleid, SQLSRV_PARAM_IN),
            array($sessionId, SQLSRV_PARAM_IN)
        );
        $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RoleSessionId_insert", $params);
        return $arrReturns;
    }




	/**
	 * 服务器列表
	 * @param $ServerType 服务器类型
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getServerList($ServerType,$Locked)
	{
		$params = array(array($ServerType, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameServerInfo_SelectList", $params);		
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['ServerName'] = Utility::gb2312ToUtf8($val['ServerName']);
				$arrReturns[$iCount]['FixServerIP'] = substr($val['ServerIP'],0,20).'...';
				$iCount++;
			}
		}		
		if(empty($arrReturns)) $arrReturns=null;
		return $arrReturns;
	}	
	/**
	 * 添加服务器配置
	 * @param $ServerID	  服务器ID
	 * @param $ServerType 服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器,7.大厅服务器
	 * @param $ServerName 服务器名称
	 * @param $ServerIP 服务器外网IP
	 * @param $LANServerIP 服务器内网IP
	 * @param $ServerPort 游戏服务端调用端口
	 * @param $Intro 服务器描述
	 * @param $LoginName 登陆名
	 * @param $LoginPwd 登陆密码
	 * @param $AppName 应用名称
	 * @return 0:成功,-1:失败
	 */
	function AddServer($arrParams,$ServerType)
	{		
		$params = array(array($arrParams['ServerID'], SQLSRV_PARAM_IN),
						array($ServerType, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['ServerName']), SQLSRV_PARAM_IN),
						array($arrParams['ServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['LANServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['ServerPort'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Intro']), SQLSRV_PARAM_IN),
						array($arrParams['LoginName'], SQLSRV_PARAM_IN),
						array(Utility::mcryptEncrypt($this->arrConfig,$arrParams['LoginPwd']), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['AppName']), SQLSRV_PARAM_IN),
						array($arrParams['ServID'], SQLSRV_PARAM_IN),
						array($arrParams['IP'], SQLSRV_PARAM_IN,SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY),SQLSRV_SQLTYPE_VARBINARY(128))
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			//如果是登陆服务器，清除缓存(download站点设置缓存，客户端登陆的时候调用大厅版本更新)
			if($ServerType==2)
			{
				$strSelectKey = $this->strSelectKeyDown.'20GameServerInfoList';
				$this->objMemcache->delete($strSelectKey);
			}			
		}
		return $arrReturns;
	}
	/**
	 * 增加游戏盾
	 * @param OldID
	 * @param ID
	 * @param GroupName
	 * @param GroupURL
	 * @param Type
	 * @param ParameterType
	 */
	function addGameDun($OldID,$ID,$GroupName,$GroupURL,$Type,$ParameterType){
	   $params=array(array($OldID,SQLSRV_PARAM_IN),
	                 array($ID,SQLSRV_PARAM_IN),
	                 array($GroupName,SQLSRV_PARAM_IN),
	                 array($GroupURL,SQLSRV_PARAM_IN),
	                 array($Type,SQLSRV_PARAM_IN),
	                 array($ParameterType,SQLSRV_PARAM_IN)
	       );
	    $arrReturns=$this->objMasterDB->fetchAllAssoc("Proc_GameDunInfo_Insert",$params);
	    return $arrReturns;
	}
	/**
	 * 获取游戏盾列表
	 */
	function selectGameDunList(){
	    $arrReturns=$this->objMasterDB->fetchAllAssoc("Proc_GameDunInfo_SelectAll",'');
	    return $arrReturns;
	}
	/**
	 * 修改大厅服务器配置
	 * @param $ServerID	  服务器ID
	 * @param $ServerType 服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器,7.大厅服务器
	 * @param $ServerName 服务器名称
	 * @param $ServerIP 服务器外网IP
	 * @param $LANServerIP 服务器内网IP
	 * @param $ServerPort 游戏服务端调用端口
	 * @param $Intro 服务器描述
	 * @param $LoginName 登陆名
	 * @param $LoginPwd 登陆密码
	 * @param $AppName 应用名称
	 * @return 0:成功,-1:失败
	 */
	function UpdateServer($arrParams)
	{		
		$iResult = -1;
		$params = array(array($arrParams['ServerID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['ServerName']), SQLSRV_PARAM_IN),
						array($arrParams['ServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['LANServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['ServerPort'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['Intro']), SQLSRV_PARAM_IN),
						array($arrParams['LoginName'], SQLSRV_PARAM_IN),
						array(Utility::mcryptEncrypt($this->arrConfig,$arrParams['LoginPwd']), SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['AppName']), SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_Update", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}





    function delWechatUser($WechatID)
    {
        $iResult = -1;
        $params = array(array($WechatID, SQLSRV_PARAM_IN));
        $arrReturns = $this->objMasterDB->fetchAssoc("Proc_WeChatUser_Delete", $params);
        if(is_array($arrReturns) && count($arrReturns)>0)
            $iResult = $arrReturns['iResult'];
        return $iResult;
    }




	/**
	 * 删除服务器配置信息
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return 0:成功,-1:失败
	 */
	function delServer($ServerID,$ServerType)
	{
		$iResult = -1;
		$params = array(array($ServerID, SQLSRV_PARAM_IN),
						array($ServerType, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除游戏盾记录
	 * @param int $ID
	 * @return 0:成功，-1：失败
	 */
	function delGameDun($ID)
	{
	    $params = array(array($ID,SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameDun_Delete",$params);
	    return $arrReturns;
	}
	/**
	 * 获取游戏盾信息
	 * @param int $ID
	 */
	function getGameDunInfo($ID)
	{
	    $params = array(array($ID,SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameDunInfo_Select",$params);
	    return $arrReturns;
	}
	/**
	 * 游戏服务器列表
	 * @return array
	 
	function getGameServerList()
	{
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameServer_SelectList", '');
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['ServerName']=Utility::gb2312ToUtf8($val['ServerName']);
				$arrReturns[$iCount]['iCount']=$iCount+1;
				$iCount++;
			}
		}	
		if(empty($arrReturns)) $arrReturns = null;			
		return $arrReturns;
	}*/
	/**
	 * 读取指定游戏服务器配置信息
	 * @param $ServerID 服务器ID
	 * @return array
	 */
	function getGameServer($ServerID)
	{
		$params = array(array($ServerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServer_SelectByServerID", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['ServerName']=Utility::gb2312ToUtf8($arrReturns['ServerName']);
		}
		return $arrReturns;
	}
	
	/**
	 * 读取指定机器人配置信息
	 * @param $NameID 机器人ID
	 * @return array
	 */
	function getRobotNamePool($NameID)
	{
	    $params = array(array($NameID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotNamePool_Select", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	    {
	        $arrReturns['Name']=Utility::gb2312ToUtf8($arrReturns['Name']);
	        $arrReturns['Signature']=Utility::gb2312ToUtf8($arrReturns['Signature']);
	    }
	    return $arrReturns;
	}
	/**
	 * 读取指定机器人账号信息
	 * @param $UserID 机器人ID
	 * @return array
	 */
	function getRobotUser($UserID)
	{
	    $params = array(array($UserID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotUser_Select", $params);
	    
	    return $arrReturns;
	}
	/**
	 * 读取指定机器人账号信息
	 * @param $UserID 机器人ID
	 * @return array
	 */
	function getRoomRobot($RoomID)
	{
	    $params = array(array($RoomID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RoomRobot_Select", $params);
	     
	    return $arrReturns;
	}
	/**
	 * 读取机器人列表
	 */
	function getRobotIDList()
	{
	    $arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_RobotID_SelectAll",'');
	    return $arrReturns;
	}
	/**
	 * 添加服务器配置
	 * @param $ServID	  服务器ID
	 * @param $ServerName 服务器名称
	 * @param $ServerIP 服务器外网IP
	 * @param $ServerPort 游戏服务端调用端口
	 * @return 0:成功,-1:失败
	 */
	function addGameServer($arrParams)
	{	
		$iResult = -1;
		$params = array(array($arrParams['ServID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['ServerName']), SQLSRV_PARAM_IN),
						array($arrParams['ServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['ServerPort'], SQLSRV_PARAM_IN)
						);	
						
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServer_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	/**
	 * 添加游戏配置
	 * @param $NameID	  机器人ID
	 * @param $Name 服务器名称
	 * @param $Signature 个星签名
	 * @param $Sex 性别
	 * @return 0:成功,-1:失败
	 */
	function addRobotNamePool($arrParams)
	{
	    $iResult = -1;
	    $params = array(array($arrParams['NameID'], SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($arrParams['Name']), SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($arrParams['Signature']), SQLSRV_PARAM_IN),
	        array($arrParams['Sex'], SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotNamePool_Insert", $params,1);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 添加游戏配置
	 * @param $NameID	  机器人ID
	 * @param $Name 服务器名称
	 * @param $Signature 个星签名
	 * @param $Sex 性别
	 * @return 0:成功,-1:失败
	 */
	function addRobotUser($arrParams)
	{
	    $iResult = -1;
	    $params = array(array($arrParams['UserID'], SQLSRV_PARAM_IN),
	        array($arrParams['RoomID'], SQLSRV_PARAM_IN),
	        array($arrParams['ServiceTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MinTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MinPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MinReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['ServiceGender'], SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotUser_Insert", $params,1);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 批量添加游戏配置
	 * @param $NameID	  机器人ID
	 * @param $Name 服务器名称
	 * @param $Signature 个星签名
	 * @param $Sex 性别
	 * @return 0:成功,-1:失败
	 */
	function addAllRobotUser($arrParams)
	{
	    $iResult = -1;
	    $params = array(
	        array($arrParams['RoomID'], SQLSRV_PARAM_IN),
	        array($arrParams['ServiceTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MinTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MinPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MinReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['ServiceGender'], SQLSRV_PARAM_IN),
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotUser_UpdateAll", $params,1);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 添加房间机器人配置
	 * @param $NameID	  机器人ID
	 * @param $Name 服务器名称
	 * @param $Signature 个星签名
	 * @param $Sex 性别
	 * @return 0:成功,-1:失败
	 */
	function addRoomRobot($arrParams)
	{
	    $iResult = -1;
	    $params = array(
	        array($arrParams['RoomID'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxCount'], SQLSRV_PARAM_IN),
	        array($arrParams['RobotWinWeighted'], SQLSRV_PARAM_IN),
	        array($arrParams['RobotWinMoney'], SQLSRV_PARAM_IN),
	        array($arrParams['ServiceTables'],SQLSRV_PARAM_IN),
	        array($arrParams['AddWinPre'], SQLSRV_PARAM_IN),
	        array($arrParams['MinTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxTakeScore'], SQLSRV_PARAM_IN),
	        array($arrParams['MinPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxPlayDraw'], SQLSRV_PARAM_IN),
	        array($arrParams['MinReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxReposeTime'], SQLSRV_PARAM_IN),
	        array($arrParams['MinLeavePre'], SQLSRV_PARAM_IN),
	        array($arrParams['MaxLeavePre'], SQLSRV_PARAM_IN)
	      
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RoomRobot_Insert", $params,1);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 删除游戏服务器配置信息
	 * @param $ServerID 服务器ID
	 * @return 大于0:成功,0:失败,-2:数据库异常
	
	function delGameServer($ServerID)
	{
		$iResult = 0;
		$params = array(array($ServerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServer_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	} */
	/**
	 * 设置服务器禁用/启用状态
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return 0:成功,-1:失败
	 */
	function setServerLocked($ServerID,$ServerType)
	{
		$iResult = -1;
		$params = array(array($ServerID, SQLSRV_PARAM_IN),
						array($ServerType, SQLSRV_PARAM_IN)
						);			
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;		
	}
	/**
	 * 读取指定服务器配置信息
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return array
	 */
	function getServerInfo($ServerID,$ServerType)
	{
		$params = array(array($ServerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_SelectByServerID", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['ServerName']=Utility::gb2312ToUtf8($arrReturns['ServerName']);
			$arrReturns['Intro']=Utility::gb2312ToUtf8($arrReturns['Intro']);
			$arrReturns['AppName']=Utility::gb2312ToUtf8($arrReturns['AppName']);
			$arrReturns['Pass']=Utility::mcryptDecrypt($this->arrConfig,$arrReturns['Pass']);
			$arrReturns['ServPort']=0;
			if($ServerType==1)
			{
				//取外网端口
				$arrIPList = explode(',', $arrReturns['ServerIP']);
				if(is_array($arrIPList) && count($arrIPList)>0)
				{
					$arrIP = explode(':', $arrIPList[0]);
					if(is_array($arrIP) && count($arrIP)==2)
						$arrReturns['ServPort']=$arrIP[1];
				}					
				//去掉外网端口之后的外网IP
				$arrReturns['ServerIP']=preg_replace('/:\d+/i','',$arrReturns['ServerIP']);
			}
			
		}
		return $arrReturns;
	}
	/**
	 * 游戏种类列表
	 * @param $ClassID 种类标识,0未分类类型,1牌类游戏,2骨牌游戏,3棋牌游戏,4休闲游戏
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getGameKindList($ClassID,$Locked)
	{
		$params = array(array($ClassID, SQLSRV_PARAM_IN),
						array($Locked, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameKind_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{ 
			$iCount = 1;
			foreach($arrReturns as $key => $val)
			{
				$arrReturns[$key]['iCount'] = $iCount++;
				$arrReturns[$key]['KindName']=Utility::gb2312ToUtf8($val['KindName']);				
			}
		}			
		return $arrReturns;
	}
	/**
	 * 添加游戏种类
	 * @param $KindName	游戏种类名称
	 * @param $KindID	游戏种类ID
	 * @param $ProcessName	进程名称
	 * @param $ServerDLL	服务端动态库名称
	 * @param $ClassID	游戏种类所属分类ID
	 * @param $CustomField	自定义说明文字
	 * @param $PayTypeID 结算类型
	 * @return 0:成功,-1:失败
	 */
	function addGameKind($KindName,$KindID,$ProcessName,$ServerDLL,$ClassID,$CustomField,$PayTypeID,$SysBank,$RobotBank)
	{		
		$iResult = -1;
		$params = array(array(Utility::utf8ToGb2312($KindName), SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array($ProcessName, SQLSRV_PARAM_IN),
						array($ServerDLL, SQLSRV_PARAM_IN),
						array($ClassID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($CustomField), SQLSRV_PARAM_IN),
						array($PayTypeID, SQLSRV_PARAM_IN),
						array($SysBank, SQLSRV_PARAM_IN),
                         array($RobotBank, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameKind_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除游戏种类
	 * @param $KindID 
	 * @return 0:成功,-1:失败
	 */
	function delGameKind($KindID)
	{
		$iResult = -1;
		$params = array(array($KindID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameKind_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 设置游戏种类禁用/启用
	 * @param $KindID	游戏种类ID
	 * $iResult=0:成功,-1:失败
	 */
	function setGameKindLocked($KindID)
	{
		$iResult = -1;
		$params = array(array($KindID, SQLSRV_PARAM_IN));			
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameKind_UpdateLocked", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;		
	}
	/**
	 * 读取指定游戏种类信息
	 * @param $KindID 游戏种类ID
	 * @return array
	 */
	function getGameKindInfo($KindID)
	{
		$params = array(array($KindID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameKind_SelectByKindID", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['KindName']=Utility::gb2312ToUtf8($arrReturns['KindName']);
			$arrReturns['CustomField']=Utility::gb2312ToUtf8($arrReturns['CustomField']);
		}
		return $arrReturns;
	}
	/**
	 * 读取游戏级别
	 * @param $KindID 游戏种类ID
	 * @return array
	 */
	function getGameLevelList($KindID)
	{
		//先从缓存读取，如果缓存中没有数据，再从数据库读取
		$strSelectKeySys = $this->strSelectKeySys . $KindID . 'GameLevelList';
		$arrReturns = $this->objMemcache->get($strSelectKeySys);
		if(!$arrReturns)
	    {
			$params = array(array($KindID, SQLSRV_PARAM_IN));	
			$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameLevel_Select", $params);
			//设置缓存，不压缩，缓存30分钟
			$this->objMemcache->set($strSelectKeySys,$arrReturns,0,$this->arrConfig['CacheTime']);	
		}		
		return $arrReturns;
	}
	/**
	 * 添加游戏级别
	 * @param $KindID 游戏种类ID
	 * @param $LevelType 级别类型
	 * @param $LevelID 级别等级
	 * @param $LevelName 级别名称
	 * @param $LBound 级别对应下限值
	 * @param $CellAmount 对局额
	 * @return 0:成功,-1:失败
	 */
	function addGameLevel($ID,$KindID,$LevelType,$LevelID,$LevelName,$LBound,$CellAmount,$ClothesImage)
	{	
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array($LevelType, SQLSRV_PARAM_IN),
						array($LevelID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($LevelName, SQLSRV_PARAM_IN)),
						array($LBound, SQLSRV_PARAM_IN),
						array($CellAmount, SQLSRV_PARAM_IN),
		                array($ClothesImage,SQLSRV_PARAM_IN)		
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameLevel_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		if($iResult==0)
		{
			$strSelectKeySys = $this->strSelectKeySys . $KindID . 'GameLevelList';
			$this->objMemcache->delete($strSelectKeySys);
		}
		return $iResult;		
	}
	/**
	 * 删除游戏级别
	 * @param $ID 
	 * @return 0:成功,-1:失败
	 */
	function delGameLevel($ID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameLevel_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}	
	/**
	 * 添加版本
	 * @param $VerType 版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
	 * @param $KindID 游戏种类ID,大厅ID=0
	 * @param $FileName 文件名及扩展名。
	 * @param $FileURL 文件下载路径
	 * @param $FileCategory 安装类型,1解压,2覆盖,3运行
	 * @param $ServerID 服务器ID
	 * @param $Version 文件最新版本
	 * @return 0:成功,-1:失败
	 */
	function addGameVersion($VerID,$VerType,$KindID,$FileName,$FileURL,$FileCategory,$ServerID,$Version,$LocalPath)
	{	
		$iResult = -1;
		$params = array(array($VerID, SQLSRV_PARAM_IN),
						array($VerType, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($FileName), SQLSRV_PARAM_IN),
						array($FileURL, SQLSRV_PARAM_IN),
						array($FileCategory, SQLSRV_PARAM_IN),
						array($ServerID, SQLSRV_PARAM_IN),
						array($Version, SQLSRV_PARAM_IN),
						array($LocalPath, SQLSRV_PARAM_IN)		
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameVersion_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];

		return $iResult;		
	}
	/**
	 * 删除游戏版本
	 * @param $VerID 
	 * @return 0:成功,-1:失败
	 */
	function delGameVersion($VerID)
	{
		$iResult = -1;
		$params = array(array($VerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameVersion_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}	
	/**
	 * 读取版本
	 * @param $VerType 版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
	 * @param $KindID 游戏种类ID,大厅ID=0
	 * @return array
	 */
	function getGameVersionList($VerType,$KindID)
	{	
		$params = array(array($VerType, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameVersion_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['FileName']=Utility::gb2312ToUtf8($val['FileName']);			
				$iVersion = $val['Version'];
				$strVer = '';
				for($i=3;$i>=0;$i--)
				{
					$iVer=intval($iVersion/pow(256,$i));
					$iVersion=intval($iVersion-$iVer*pow(256,$i));
					$strVer .= $iVer . '.';
				}
				$arrReturns[$iCount]['Version']=empty($strVer) ? '' : substr($strVer, 0,strlen($strVer)-1);	
				$iCount++;
			}	
		}

		return $arrReturns;
	}
	/**
	 * 读取版本
	 * @param $VerID 
	 * @return array
	 */
	function getGameVersion($VerID)
	{	
		$params = array(array($VerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameVersion_SelectByVerID", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['FileName']=Utility::gb2312ToUtf8($arrReturns['FileName']);
			$arrReturns['FileURL']=str_replace("\\","\\\\",$arrReturns['FileURL']);
			$arrReturns['LocalPath']=str_replace("\\","\\\\",$arrReturns['LocalPath']);
			//$iVersion = $arrReturns['Version'];
			$arrReturns['Version'] = Utility::getVersion($arrReturns['Version']);//empty($strVer) ? '' : substr($strVer, 0,strlen($strVer)-1);			 
		}
		return $arrReturns;
	}
	/**
	 * 样式列表
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getStyleSheetList($Locked)
	{
		$params = array(array($Locked, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_StyleSheet_Select", $params);		
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount=0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['StyleName']=Utility::gb2312ToUtf8($val['StyleName']);
				$iCount++;
			}
		}		
		return $arrReturns;
	}
	/**
	 * 桌子规格
	 * @param $TableSchemeID 
	 * @return array
	 */
	function getGameTableSchemeList($TableSchemeID)
	{
		$params = array(array($TableSchemeID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameTableScheme_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['SchemeName']=Utility::gb2312ToUtf8($val['SchemeName']);
				$iCount++;
			}			
		}				
		return $arrReturns;
	}	
	/**
	 * 配置房间信息
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameRoom($arrParams)
	{	
		$params = array(array($arrParams['RoomID'],SQLSRV_PARAM_IN),
						array($arrParams['KindID'],SQLSRV_PARAM_IN),
						array($arrParams['RoomType'],SQLSRV_PARAM_IN),		
						array($arrParams['ServerID'],SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['RoomName']),SQLSRV_PARAM_IN),
						array($arrParams['MaxTableCount'],SQLSRV_PARAM_IN),
						array($arrParams['MaxPlayerCount'],SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['EnterPrompt']),SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['RulePrompt']),SQLSRV_PARAM_IN),
						array($arrParams['AllowLook'],SQLSRV_PARAM_IN),
						array($arrParams['StartMode'],SQLSRV_PARAM_IN),
						array($arrParams['StartForMinUser'],SQLSRV_PARAM_IN),
						array($arrParams['CanJoinWhenPlaying'],SQLSRV_PARAM_IN),
						array($arrParams['MaxLookUser'],SQLSRV_PARAM_IN),
						array($arrParams['AutoRun'],SQLSRV_PARAM_IN),
						array($arrParams['MaxSitTime'],SQLSRV_PARAM_IN),
						array($arrParams['MaxStartTime'],SQLSRV_PARAM_IN),
						array($arrParams['MaxFreeTime'],SQLSRV_PARAM_IN),		
						array(Utility::utf8ToGb2312($arrParams['CustomField']),SQLSRV_PARAM_IN),		
						array($arrParams['AllowChatOption'],SQLSRV_PARAM_IN),		
						array($arrParams['RoomWealthMin'],SQLSRV_PARAM_IN),
						array($arrParams['RoomNumMax1'],SQLSRV_PARAM_IN),
						array($arrParams['TableWealthMin'],SQLSRV_PARAM_IN),
						array($arrParams['RoomNumMax2'],SQLSRV_PARAM_IN),
						array($arrParams['PlayCountMax'],SQLSRV_PARAM_IN),						
						array($arrParams['FleeCountMax'],SQLSRV_PARAM_IN),
						array($arrParams['RoleLevelMin'],SQLSRV_PARAM_IN),
						array($arrParams['CellScoreType'],SQLSRV_PARAM_IN),
						array($arrParams['CellScore'],SQLSRV_PARAM_IN),
						array($arrParams['TableSchemeId'],SQLSRV_PARAM_IN),
						array($arrParams['ExpMoney'],SQLSRV_PARAM_IN),
						
						array($arrParams['MatchTypeID'],SQLSRV_PARAM_IN),
						array($arrParams['MatchModel'],SQLSRV_PARAM_IN),
						array($arrParams['SetFlag'],SQLSRV_PARAM_IN),
						array($arrParams['MaxMatchs'],SQLSRV_PARAM_IN),
						array($arrParams['MaxMatchNumber'],SQLSRV_PARAM_IN),
						array($arrParams['MatchStartDate'].' '.$arrParams['MatchStartTime'],SQLSRV_PARAM_IN),
						//array($arrParams['MatchStartTime'],SQLSRV_PARAM_IN),
						array($arrParams['MatchEndDate'].' '.$arrParams['MatchEndTime'],SQLSRV_PARAM_IN),	
						//array($arrParams['MatchEndTime'],SQLSRV_PARAM_IN),	
						array($arrParams['MatchTimeStatus'],SQLSRV_PARAM_IN),
						array($arrParams['GetPrizeType'],SQLSRV_PARAM_IN),	
						array($arrParams['GetStatus'],SQLSRV_PARAM_IN),
		                array($arrParams['LuckyEggTaxRate'],SQLSRV_PARAM_IN),
		                array($arrParams['RobotJoinWhenPlaying'],SQLSRV_PARAM_IN)
						);
		//var_dump($params);
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfo_Insert", $params);
		return $arrReturns;		
	}
	
	/**
	 * 删除房间信息
	 * @param $RoomID 
	 * @return 0:成功,-1:失败
	 */
	function delGameRoomInfo($RoomID)
	{
		$iResult = -1;
		$params = array(array($RoomID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfo_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	/**
	 * 读取房间信息
	 * @param $RoomID  
	 * @return array
	 */
	function getGameRoomInfo($RoomID)
	{
		$params = array(array($RoomID, SQLSRV_PARAM_IN));
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfo_Select", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{			
			$arrReturns['RoomName']=Utility::gb2312ToUtf8($arrReturns['RoomName']);
			$arrReturns['EnterPrompt']=Utility::gb2312ToUtf8($arrReturns['EnterPrompt']);
			$arrReturns['RulePrompt']=Utility::gb2312ToUtf8($arrReturns['RulePrompt']);
			$arrReturns['CustomField']=Utility::gb2312ToUtf8($arrReturns['CustomField']);
			foreach ($this->arrConfig['RoomType'] as $v)
			{
				//积分
				if(($v['TypeID'] & $arrReturns['RoomType'])==1)
				{
					$arrReturns['RoomNumMax1']=$arrReturns['RoomScoreMax'];
					$arrReturns['RoomNumMax2']=$arrReturns['TableScoreMax'];
					break;
				}
				//金币
				else if(($v['TypeID'] & $arrReturns['RoomType'])==2)
				{
					$arrReturns['RoomNumMax1']=$arrReturns['MoneyMaxInGame'];
					$arrReturns['RoomNumMax2']=$arrReturns['EJectMoney'];
					break;
				}
			}			
			if(!isset($arrReturns['RoomNumMax1'])) $arrReturns['RoomNumMax1']=0;
			if(!isset($arrReturns['RoomNumMax2'])) $arrReturns['RoomNumMax2']=0;
		}				
		return $arrReturns;
	}
	

	/**
	 * 读取全部房间信息
	 * @param 
	 * @return array
	 */
	function getGameRoomInfoList()
	{
	    $arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameRoomInfo_SelectAll",'');
	    if(is_array($arrReturns) && count($arrReturns)>0)
		{			
		    foreach ($arrReturns as $key => $val){
			     $arrReturns[$key]['RoomName']=Utility::gb2312ToUtf8($val['RoomName']);
		    }
		}	
	    return $arrReturns;
	}
	/**
	 * 房间列表
	 * @param $Key 
	 * @param $TypeID 条件匹配类型,1:按KindID查找,2:按RoomID查找,3:按MatchModel搜索,4:按MatchTypeID搜索
	 * @return array
	 */
	function getGameRoomList($Key,$TypeID)
	{
		$params = array(array($Key, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameRoomInfo_SelectList", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['RoomName']=Utility::gb2312ToUtf8($val['RoomName']);
				$iCount++;
			}			
		}
		else
			$arrReturns = null;		
		return $arrReturns;
	}	
	/**
	 * 读取游戏节点
	 * @param $Locked 锁定状态，默认0：正常,1：锁定
	 * @return array
	 */
	function getGameTypeList($Locked)
	{
		//先从缓存读取，如果缓存中没有数据，再从数据库读取
		$strSelectKeySys = $this->strSelectKeySys . 'GameTypeList';
		$arrResults = $this->objMemcache->get($strSelectKeySys);
		$arrResults=null;
		if(!$arrResults)
	    {
			$params = array(array($Locked, SQLSRV_PARAM_IN));
			$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameType_Select", $params);
			//设置缓存，不压缩，缓存30分钟
			$this->objMemcache->set($strSelectKeySys,$arrResults,0,$this->arrConfig['CacheTime']);
		}
		return $arrResults;
	}
	/**
	 * 读取标签
	 * @param $Locked 锁定状态，默认0：正常,1：锁定
	 * @return array
	 */
	function getTagClassList($Locked)
	{
		$params = array(array($Locked, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_TagClass_Select", $params);
		return $arrResults;
	}
	/**
	 * 添加游戏节点
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameType($arrParams)
	{	
		$iResult = -1;
		$params = array(array($arrParams['TypeID'],SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['NodeName']),SQLSRV_PARAM_IN),
						array($arrParams['NodeType'],SQLSRV_PARAM_IN),
						array($arrParams['StyleID'],SQLSRV_PARAM_IN),
						array($arrParams['Action'],SQLSRV_PARAM_IN),
						array($arrParams['SortID'],SQLSRV_PARAM_IN),						
						array($arrParams['Locked'],SQLSRV_PARAM_IN),
						array($arrParams['TagID'],SQLSRV_PARAM_IN),
						array($arrParams['KindID'],SQLSRV_PARAM_IN),
						array($arrParams['RoomID'],SQLSRV_PARAM_IN),
						array($arrParams['URL'],SQLSRV_PARAM_IN),
				        array($arrParams['ParentId'],SQLSRV_PARAM_IN),
						);
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameType_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{			
			$iResult = $arrReturns['iResult'];	
			if($iResult==0)	$this->delGameTypeListMemcache();	
		}
		return $iResult;		
	}
	/**
	 * 删除游戏节点
	 * @param $iTypeID 
	 * @return 0:成功,-1:失败,-3:该节点下有子节点,不能删除
	 */
	function delGameType($iTypeID)
	{
		$iResult = -1;
		$params = array(array($iTypeID,SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameType_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iResult = $arrReturns['iResult'];
			if($iResult==0)	$this->delGameTypeListMemcache();
		}
		return $iResult;			
	}
	/**
	 * 删除节点缓存
	 */
	function delGameTypeListMemcache()
	{
		$strSelectKeySys = $this->strSelectKeySys . 'GameTypeList';
		$this->objMemcache->delete($strSelectKeySys);
	}
	/**
	 * 读取游戏节点详细信息
	 * @param $TypeID 
	 * @return array
	 */
	function getGameTypeInfo($TypeID)
	{	
		$params = array(array($TypeID,SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameType_SelectInfo", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$arrReturns['NodeName']=Utility::gb2312ToUtf8($arrReturns['NodeName']);
			$arrReturns['ParentNode']=empty($arrReturns['ParentNode']) ? '根节点' : Utility::gb2312ToUtf8($arrReturns['ParentNode']);
		}
		return $arrReturns;		
	}	
	/**
	 * 读取游戏父节点列表
	 * @return array
	 */
	function getParentNode()
	{
	    $arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_ParentNode_Select",'');
	    for($i=0 ; $i<count($arrReturns) ; $i++)
	    {
	        $arrReturns[$i]['NodeName']=Utility::gb2312ToUtf8($arrReturns[$i]['NodeName']);
	    }
	    return $arrReturns;
	}
	/**
	 * MapType列表
	 * @return array
	 */
	function getMapTypeALL()
	{
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_MapTypeAndMap_Select", '');	
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['Name']=Utility::gb2312ToUtf8($val['Name']);
				$iCount++;
			}
		}			
		return $arrReturns;
	}	
	/**
	 * Map分库关联数据
	 * @return array
	 */
	function getMapListALL()
	{
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_MapListAndServer_Select", '');	
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['TypeName']=Utility::gb2312ToUtf8($val['TypeName']);
				$arrReturns[$iCount]['ServerName']=Utility::gb2312ToUtf8($val['ServerName']);
				$iCount++;
			}
		}				
		return $arrReturns;
	}
	/**
	 * 添加MAP
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addMap($arrParams)
	{
		$iResult = -1;
		$params = array(array(Utility::utf8ToGb2312($arrParams['Name']),SQLSRV_PARAM_IN),
						array($arrParams['MapID'],SQLSRV_PARAM_IN),
						array($arrParams['Hashlimit'],SQLSRV_PARAM_IN)
						);

		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_MapTypeAndMap_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0 && $arrReturns['iResult']==0 && $arrReturns['MapID']>0)
		{
			$iResult = $arrReturns['iResult'];
			$arrTypeName = explode(',', $arrParams['TypeName']);
			$arrServerID = explode(',', $arrParams['ServerID']);
			for ($i=0;$i<$arrParams['Hashlimit'];$i++)
			{				
				$params = array(array($arrReturns['MapID'],SQLSRV_PARAM_IN),
								array($i,SQLSRV_PARAM_IN),
								array(Utility::utf8ToGb2312($arrTypeName[$i]),SQLSRV_PARAM_IN),
								array($arrServerID[$i],SQLSRV_PARAM_IN)								
								);
				$arrRes = $this->objMasterDB->fetchAssoc("Proc_MapList_Insert", $params);	
				if(is_array($arrRes) && count($arrRes)>0 && $arrRes['iResult']<0)
				{
					$iResult = $arrRes['iResult'];
					break;
				}			
			}
		}
		return $iResult;	
	}
	/**
	 * 读取MAP配置信息
	 * @return array
	 */
	function getMapInfo($ID)
	{
		$params = array(array($ID,SQLSRV_PARAM_IN));
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_MapALL_SelectInfo", $params);	
		if(is_array($arrReturns) && count($arrReturns)>0)
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['Name']=Utility::gb2312ToUtf8($val['Name']);
				$arrReturns[$iCount]['TypeName']=Utility::gb2312ToUtf8($val['TypeName']);
				$iCount++;
			}
		}			
		return $arrReturns;
	}	
	/**
	 * 读取游戏房间及游戏种类信息
	 * @param $LANServerIP 线路信息
	 * @return array
	 */
	function getRoomAndKindList($LANServerIP)
	{
		$params = array(array($LANServerIP, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameRoomInfoAndGameKind_Select", $params);
		return $arrReturns;
	}
	/**
	 * 统计指定服务器下的房间数量
	 * @param $ServerID 服务器ID
	 * @return array
	 */
	function getGameRoomCount($ServerID)
	{
		$iCount = 0;
		$params = array(array($ServerID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfo_Count", $params);
		if(is_array($arrReturns) && count($arrReturns)>0) $iCount = $arrReturns['TotalNum'];
		return $iCount;
	}
	
	/**
	 * 游戏桌子类型列表
	 */
	function getGameTableList($TableSchemeID)
	{
		$params = array(array($TableSchemeID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameTableScheme_Select", $params);
		return $arrReturns;
	}

	/**
	 * 游戏任务列表
	 */
	function getGameTaskList($TaskID)
	{
	    $params = array(array($TaskID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_GameTask_Select", $params);
	    return $arrReturns;
	}	
	/**
	 * 添加或修改桌子类型
	 * @TableSchemeID	SMALLINT,	--主键ID
	 * @SchemeName		VARCHAR(50),--桌子名称
	 * @TableID		SMALLINT,	--桌子ID
	 * @LockBkID		SMALLINT,	--桌子锁图片的ID
	 * @GestureID		SMALLINT,	--准备好后玩家手势ID
	 * @RunButtonID	SMALLINT,	--启动按纽ID
	 * @TableDataID	SMALLINT,	--桌子数据文件ID
	 * @ChairID		SMALLINT	--椅子ID
	 * @return 0:成功,-1:失败
	 */
	function addGameTable($TableSchemeID, $SchemeName, $TableID, $LockBkID, $GestureID, $RunButtonID, $TableDataID, $ChairID)
	{
		$iResult = -1;
		$params = array(array($TableSchemeID, SQLSRV_PARAM_IN),
						array($SchemeName, SQLSRV_PARAM_IN),
						array($TableID, SQLSRV_PARAM_IN),
						array($LockBkID, SQLSRV_PARAM_IN),
						array($GestureID, SQLSRV_PARAM_IN),
						array($RunButtonID, SQLSRV_PARAM_IN),
						array($TableDataID, SQLSRV_PARAM_IN),
						array($ChairID, SQLSRV_PARAM_IN)
					);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameTableScheme_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加或修改游戏任务
	 * @TaskID	INT,	--主键ID
	 * @KindID		int,--游戏种类
	 * @RoomType		int,	--房间类型
	 * @GameCount		INT,	--游戏局数
	 * @AwardMoney		BIGINT,	--奖励金币数
	 * @return 0:成功,-1:失败
	 */
	function addGameTask($TaskID,$KindID,$RoomType,$GameCount,$AwardMoney)
	{
	    $iResult = -1;
		$params = array(array($TaskID, SQLSRV_PARAM_IN),
						array($KindID, SQLSRV_PARAM_IN),
						array($RoomType, SQLSRV_PARAM_IN),
						array($GameCount, SQLSRV_PARAM_IN),
						array($AwardMoney, SQLSRV_PARAM_IN)
					);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameTask_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除桌子类型
	 * @return 0:成功,-1:失败
	 */
	function delGameTable($TableSchemeID)
	{
		$iResult = -1;
		$params = array(array($TableSchemeID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameTableScheme_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除桌子类型
	 * @return 0:成功,-1:失败
	 */
	function delGameTask($TaskID)
	{
	    $iResult = -1;
	    $params = array(array($TaskID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameTask_Delete", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 读取系统配置表
	 * @return array
	 */
	function getSysConfigInfo()
	{
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_SysConfig_Select", '');
		if(is_array($arrResults) && count($arrResults)>0)
		{
			$arrResults['MaxAccPowerPer'] = round($arrResults['MaxAccPowerPer'],2);
			$arrResults['PowerPer'] = round($arrResults['PowerPer'],2);
		}
		return $arrResults;
	}
	/**
	 * 添加配置表
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addSysConfig($arrParams)
	{
		$params = array(array($arrParams['MaxRooms'], SQLSRV_PARAM_IN),
						array($arrParams['DaySalary'], SQLSRV_PARAM_IN),
						array($arrParams['BasePower'], SQLSRV_PARAM_IN),
						array($arrParams['MaxAccPowerPer'], SQLSRV_PARAM_IN),
						array($arrParams['PowerPer'], SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysConfig_Insert", $params);
		return $arrReturns;
	}
	/**
	 * 设置系统配置信息(系统银行更新失败回滚)
	 * @param $Capacity 回滚金额
	 * @param $TypeID 2:金币回滚,1:龙币回滚
	 * @return -3:回滚的金额必须小于现有容量,0:成功,-1:失败
	 */
	function setSysConfigCallbank($Capacity,$TypeID)
	{
		$params = array(array($Capacity, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysConfig_Callback", $params);
		return $arrReturns;
	}
	/**
	 * 设置系统配置信息(系统银行扩容)
	 * @param $Capacity 扩容金额
	 * @param $TypeID 2:金币扩容,1:龙币扩容
	 * @return -2:金币/龙币扩容必须大于之前容量,0:成功,-1:失败
	 */
	function setSysConfig($Capacity,$TypeID)
	{
		$params = array(array($Capacity, SQLSRV_PARAM_IN),
						array($TypeID, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysConfig_Update", $params);
		return $arrReturns;
	}
	/**
	 * 添加黄钻等级
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addVipLevel($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['VipID'], SQLSRV_PARAM_IN),
						array($arrParams['FwMoney'], SQLSRV_PARAM_IN),
						array($arrParams['Discount'], SQLSRV_PARAM_IN),
						array($arrParams['AdditivePower'], SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysVipLevel_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除黄钻等级
	 * @param $VipID 
	 * @return 0:成功,-1:失败
	 */
	function delVipLevel($VipID)
	{
		$iResult = -1;
		$params = array(array($VipID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysVipLevel_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除机器人信息
	 * @param $NameID
	 * @return 0:成功,-1:失败
	 */
	function delRobotName($NameID)
	{
	    $iResult = -1;
	    $params = array(array($NameID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotNamePool_Delete", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 整理机器人信息
	 * @param $NameID
	 * @return 0:成功,-1:失败
	 */
	function formatRobotName()
	{
	    $iResult = -1;
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotNamePool_Format", '');
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	
	/**
	 * 删除机器人账号
	 * @param $UserID
	 * @return 0:成功,-1:失败
	 */
	function delRobotUser($UserID)
	{
	    $iResult = -1;
	    $params = array(array($UserID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotUser_Delete", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 批量删除机器人账号
	 * @param $Type int 删除类型  1.按数量  2.按页码  3按房间号
	 * @param $value int 
	 * @return 0:成功,-1:失败
	 */
	function delAllRobotUser($Type,$Value)
	{
	    $iResult = -1;
	    $params = array(
	        array($Type, SQLSRV_PARAM_IN),
	        array($Value, SQLSRV_PARAM_IN)
	        
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RobotUser_DeleteAll", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 删除房间机器人
	 * @param $UserID
	 * @return 0:成功,-1:失败
	 */
	function delRoomRobot($RoomID)
	{
	    $iResult = -1;
	    $params = array(array($RoomID, SQLSRV_PARAM_IN));
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_RoomRobot_Delete", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 读取黄钻等级
	 * @return array
	 */
	function getVipLevel($VipID)
	{
		$params = array(array($VipID, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_SysVipLevel_Select", $params);
		if(is_array($arrResults) && count($arrResults)>0)
		{
			$iCount = 0;
			foreach ($arrResults as $val)
			{
				$arrResults[$iCount]['Discount'] = round($val['Discount'],2);
				$arrResults[$iCount]['AdditivePower'] = round($val['AdditivePower'],2);
				$iCount++;
			}
		}
		return $arrResults;
	}

	/**
	 * 添加游戏配置
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addGameConfig($arrParams)
	{
	    $iResult = -1;
	    $params = array(array($arrParams['TypeID'], SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($arrParams['Description']),SQLSRV_PARAM_IN),
	        array($arrParams['CfgValue'], SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameConfig_Insert", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 删除游戏配置
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function delGameConfig($TypeID)
	{
	    $iResult = -1;
	    $params = array(array($TypeID, SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameConfig_Delete", $params);
	    return $iResult['iResult'];
	}
	/**
	 * 读取游戏配置
	 * @return array
	 */
	function getGameConfig($TypeID)
	{
	    $params = array(array($TypeID, SQLSRV_PARAM_IN));
	    $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameConfig_Select", $params);
	    return $arrResults;
	}
	/**
	 * 添加角色等级
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addRoleLevel($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['LvlID'], SQLSRV_PARAM_IN),
						array($arrParams['LvlExperience'], SQLSRV_PARAM_IN),
						array($arrParams['MaxScoreDay'], SQLSRV_PARAM_IN),
						array($arrParams['LoginScore'], SQLSRV_PARAM_IN),
						array($arrParams['MaxScoreOnline'], SQLSRV_PARAM_IN),
						array($arrParams['MaxScoreOnlineHour'], SQLSRV_PARAM_IN),
						array($arrParams['MaxScoreGame'], SQLSRV_PARAM_IN),
						array($arrParams['MaxScoreTask'], SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysRoleLevel_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取角色等级
	 * @return array
	 */
	function getRoleLevel($LvlID)
	{
		$params = array(array($LvlID, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_SysRoleLevel_Select", $params);
		return $arrResults;
	}
	/**
	 * 删除角色等级
	 * @param $LvlID 
	 * @return 0:成功,-1:失败
	 */
	function delRoleLevel($LvlID)
	{
		$iResult = -1;
		$params = array(array($LvlID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysRoleLevel_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取运势级别
	 * @return array
	 */
	function getLuckyList()
	{
		$arrReturn = null;
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_SysLucky_Select", '');
		if(is_array($arrResults) && count($arrResults)>0)
		{
			$iCount=0;
			foreach ($arrResults as $val)
			{
				if(isset($arrLuckyID[$val['LuckyID']]))
				{
					$iNum = $arrLuckyID[$val['LuckyID']];
					$arrReturn[$iNum]['DropNum'] = $arrReturn[$iNum]['DropNum'].'<br>'.round($val['DropNum'],2);
					$arrReturn[$iNum]['Probability'] = $arrReturn[$iNum]['Probability'].'<br>'.round($val['Probability'],2);
					continue;
				}
				else
					$arrLuckyID[$val['LuckyID']]=$iCount;
				$arrReturn[$iCount]['LuckyName'] = Utility::gb2312ToUtf8($val['LuckyName']);
				$arrReturn[$iCount]['Probability'] = round($val['Probability'],2);
				$arrReturn[$iCount]['LuckyID'] = $val['LuckyID'];
				$arrReturn[$iCount]['DropNum'] = $val['DropNum'];
				$arrReturn[$iCount]['RndProb'] = round($val['RndProb'],2);
				$arrReturn[$iCount]['SpProb'] = round($val['SpProb'],2);
				$iCount++;
			}
		}
		return $arrReturn;
	}
	/**
	 * 读取运势级别
	 * @return array
	 */
	function getLuckyAll()
	{
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_SysLucky_SelectAll", '');
		if(is_array($arrResults) && count($arrResults)>0)
		{
			$iCount=0;
			foreach ($arrResults as $val)
			{	
				$arrResults[$iCount]['LuckyName'] = Utility::gb2312ToUtf8($val['LuckyName']);
				$iCount++;
			}
		}
		return $arrResults;
	}
	/**
	 * 读取运势掉落概率
	 * @return array
	 */
	function getLuckyProbList($LuckyID)
	{
		$params = array(array($LuckyID, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_SysLuckyProb_Select", $params);
		if(is_array($arrResults) && count($arrResults)>0)
		{
			$iCount=0;
			foreach ($arrResults as $val)
			{
				$arrResults[$iCount]['Probability'] = round($val['Probability'],2);
				$iCount++;
			}
		}
		return $arrResults;
	}
	/**
	 * 添加运势级别
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addLucky($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['LuckyID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['LuckyName']), SQLSRV_PARAM_IN),
						array($arrParams['RndProb'], SQLSRV_PARAM_IN),
						array($arrParams['SpProb'], SQLSRV_PARAM_IN)	
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysLucky_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除运势级别
	 * @param $LuckyID 
	 * @return 0:成功,-1:失败
	 */
	function delLucky($LuckyID)
	{
		$iResult = -1;
		$params = array(array($LuckyID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysLucky_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加运势掉落概率
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addLuckyProb($arrParams)
	{
		$params = array(array($arrParams['ID'], SQLSRV_PARAM_IN),
						array($arrParams['LuckyID'], SQLSRV_PARAM_IN),
						array($arrParams['DropNum'], SQLSRV_PARAM_IN),
						array($arrParams['Probability'], SQLSRV_PARAM_IN)						
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysLuckyProb_Insert", $params);		
		return $arrReturns;
	}
	/**
	 * 删除运势掉落概率
	 * @param $LuckyID 
	 * @return 0:成功,-1:失败
	 */
	function delLuckyProb($ID)
	{
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysLuckyProb_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取敏感词
	 * @param $ID
	 * @return array
	 */
	function getSysConfineNameInfo($ID)
	{
		$params = array(array($ID, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_SysConfineLoginName_SelectByID", $params);
		if(is_array($arrResults) && count($arrResults)>0)
			$arrResults['LoginName'] = Utility::gb2312ToUtf8($arrResults['LoginName']);
		return $arrResults;
	}	
	/**
	 * 添加敏感词
	 * @param 
	 * @return 0:成功,-1:失败
	 */
	function addSysConfineName($ID,$LoginName)
	{
		$iResult = -1;
		$params = array(array(intval($ID), SQLSRV_PARAM_IN),
						array(Utility::utf8toGb2312($LoginName), SQLSRV_PARAM_IN)				
						);
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysConfineLoginName_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 删除敏感词
	 * @param 
	 * @return 0:成功,-1:失败
	 */
	function delSysConfineName($ID)
	{
		$iResult = -1;
		$params = array(array(intval($ID), SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysConfineLoginName_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 读取外挂
	 * @param $ExtraProgramKey
	 * @return array
	 */
	function getExtraProgram($ExtraProgramKey)
	{
	    $params = array(
	        array(Utility::utf8ToGb2312($ExtraProgramKey), SQLSRV_PARAM_IN)
	        
	    );
	    $arrResults = $this->objMasterDB->fetchAssoc("Proc_ExtraProgram_SelectByKey", $params);
	    if(is_array($arrResults) && count($arrResults)>0){
	        $arrResults['ExtraProgramKey'] = Utility::gb2312ToUtf8($arrResults['ExtraProgramKey']);
	        $arrResults['ExtraProgramName'] = Utility::gb2312ToUtf8($arrResults['ExtraProgramName']);
	        
	    }
	    return $arrResults;
	}
	/**
	 * 添加外挂
	 * @param
	 * @return 0:成功,-1:失败
	 */
	function addExtraProgram($OldExtraProgramKey,$ExtraProgramKey,$ExtraProgramName)
	{
	    $iResult = -1;
	    $params = array(
	        array(Utility::utf8ToGb2312($OldExtraProgramKey), SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($ExtraProgramKey), SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($ExtraProgramName), SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_ExtraProgram_Insert", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 删除外挂
	 * @param
	 * @return 0:成功,-1:失败
	 */
	function delExtraProgram($ExtraProgramKey)
	{
	    $iResult = -1;
	    $params = array(
	        array(Utility::utf8ToGb2312($ExtraProgramKey), SQLSRV_PARAM_IN)
	        
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_ExtraProgram_Delete", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 根据游戏编号、或者通行证帐号获取RoleID
	 * @param $keyID
	 * @param $type
	 */
	function getRoleIDByKeyID($keyID, $type)
	{
		$params = array(array($keyID, SQLSRV_PARAM_IN),
						array($type, SQLSRV_PARAM_IN));
						
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_PassportRole_Select", $params);
		
		return $arrReturns;
	}
	
	/**
	 * @return 返回积分游戏列表
	 * @author blj
	 */
	public function getGameKind()
	{
		//先从缓存读取，如果缓存中没有数据，再从数据库读取
		/*$strSelectKey = $this->strSelectAllKeyBank . 'GameKind';
		$arrReturns = $this->objMemcache->get($strSelectKey);
			
		if(!$arrReturns)
	    {*/
			$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameKind_SelectUsing");
			$arrReturns = array();
			if(is_array($arrResults) && count($arrResults)>0)
			{
				$i = 0;
				foreach ($arrResults as $result)
				{
					if(($result['PayTypeID'] & 1) > 0)
					{
						$arrReturns[$i]['KindID'] = $result['KindID'];	
						$arrReturns[$i]['KindName'] = Utility::gb2312ToUtf8($result['KindName']);
					}
					$i++;
				}
			}
			//设置缓存，不压缩，缓存30分钟
			/*$this->objMemcache->set($strSelectKey,$arrReturns,0,1800);	
	    }*/
		return $arrReturns;
	}	
	/**
	 * 配置比赛规则信息(66人斗地主模式的比赛)
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameMatch1($arrParams)
	{	
		$iResult = -1;
		$params = array(array($arrParams['MatchTypeID'],SQLSRV_PARAM_IN),						
						array($arrParams['MaxNumber'],SQLSRV_PARAM_IN),
						array($arrParams['StopNumber'],SQLSRV_PARAM_IN),
						array($arrParams['TopNumber'],SQLSRV_PARAM_IN),
						array($arrParams['BaseScore1'],SQLSRV_PARAM_IN),
						array($arrParams['BaseBumber1'],SQLSRV_PARAM_IN),
						array($arrParams['BaseBumberIncrease'],SQLSRV_PARAM_IN),
						array($arrParams['GameOverNumber'],SQLSRV_PARAM_IN),
						array($arrParams['BaseScore2'],SQLSRV_PARAM_IN),
						array($arrParams['BaseBumber2'],SQLSRV_PARAM_IN),
						array($arrParams['GameRounds'],SQLSRV_PARAM_IN),
						array($arrParams['GameTimes'],SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['GameTitle']),SQLSRV_PARAM_IN),
						array($arrParams['PageUrl'],SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['MatchRule1']),SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['MatchRule2']),SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfoMatch_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0) $iResult = $arrReturns['iResult'];
		return $iResult;	
	}
	/**
	 * 返回比赛规则信息(66人斗地主模式的比赛)
	 * @param $MatchTypeID 赛事ID
	 * @author xlj
	 * @return array
	 */
	public function getGameMatchInfo1($MatchTypeID)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfoMatch_SelectInfo",$params);

		if(is_array($arrResults) && count($arrResults)>0)
		{
			$arrResults['GameTitle'] = Utility::gb2312ToUtf8($arrResults['GameTitle']);
			$arrResults['MatchRule1'] = Utility::gb2312ToUtf8($arrResults['MatchRule1']);
			$arrResults['MatchRule2'] = Utility::gb2312ToUtf8($arrResults['MatchRule2']);
		}
		if(empty($arrResults)) $arrResults = null;
		return $arrResults;
	}	
	/**
	 * 返回所有赛事
	 * @author xlj
	 * @return array
	 */
	public function getGameMatchAll()
	{
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameMatch_SelectAll",'');

		if(is_array($arrResults) && count($arrResults)>0)
		{
			$iCount = 0;
			foreach ($arrResults as $val)
			{
				$arrResults[$iCount]['MatchName'] = Utility::gb2312ToUtf8($val['MatchName']);
				$iCount++;	
			}
		}
		if(empty($arrResults)) $arrResults = null;
		return $arrResults;
	}	
	/**
	 * 删除玩家编号
	 * @param $LoginID
	 * @return 0:成功,-1:失败
	 */
	public function delLoginID($LoginID)
	{
		$iResult = -1;
		$params = array(array($LoginID, SQLSRV_PARAM_IN));	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_ConfineLoginID_Delete", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	/**
	 * 添加玩家编号
	 * @param $LoginID
	 * @param $Sales 1:不可自动分配,0:可自动分配
	 * @param $Pattern 格式
	 * @return 0:成功,-1:失败
	 */
	public function addLoginID($LoginID,$Sales,$Pattern)
	{
		$iResult = -1;
		$params = array(array($LoginID, SQLSRV_PARAM_IN),
						array($Sales, SQLSRV_PARAM_IN),
						array($Pattern, SQLSRV_PARAM_IN)
						);	
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_ConfineLoginID_Insert", $params);
		if(is_array($arrReturns) && count($arrReturns)>0)
			$iResult = $arrReturns['iResult'];
		return $iResult;
	}
	
	/**
	 * 添加赛事
	 * @param $MatchName
	 * @param $TypeID
	 */
	public function addGameMatch($MatchTypeID,$MatchName,$TypeID,$MID)
	{
		$params = array(array($MID,SQLSRV_PARAM_IN),
						array($MatchTypeID,SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($MatchName),SQLSRV_PARAM_IN),
						array($TypeID,SQLSRV_PARAM_IN));
						
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatch_Insert",$params);
		
		return $arrResults;
	}
	
	/**
	 * 根据MatchTypeID获取比赛信息
	 * @author blj
	 * @param $Key
	 * @param $Type 1:按MatchTypeID搜索,2:按TypeID搜索
	 */
	public function getGameMatchByID($Key,$Type)
	{
		$params = array(array($Key,SQLSRV_PARAM_IN),
						array($Type,SQLSRV_PARAM_IN));
		if($Type==1)
			$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatch_Select",$params);
		else 
		{
			$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameMatch_Select",$params);
			if(is_array($arrResults))
			{
				$iCount = 0;
				foreach ($arrResults as $val)
				{
					$arrResults[$iCount]['MatchName'] = Utility::gb2312ToUtf8($val['MatchName']);
					$iCount++;
				}
			}		
		}
		return $arrResults;
	}
	
	/**
	 * 删除比赛信息
	 * @author blj
	 * @param  $MatchTypeID
	 */
	public function deleteGameMatch($MatchTypeID)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN));
						
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatch_Delete",$params);
		
		return $arrResults;
	}
	
	/**
	 * 根据比赛类型ID获取比赛奖品列表
	 * @author blj
	 * @param $MatchTypeID
	 */
	public function getGameMatchPrize($MatchTypeID)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameMatchWinPrize_SelectAll",$params);
		return $arrResults;
	}
	
	/**
	 * 根据比赛类型ID获取某等奖信息
	 * @author blj
	 * @param $MatchTypeID
	 * @param $Level
	 */
	public function getMatchPrizeByLevel($MatchTypeID,$Level)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN),
						array($Level,SQLSRV_PARAM_IN));
						
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameMatchWinPrize_Select",$params);
		return $arrResults;
	}
	
	/**
	 * 添加某个比赛的奖品信息
	 * @author blj
	 * @param $MatchTypeID
	 * @param $RankStart
	 * @param $RankEnd
	 * @param $Level
	 * @param $SpID
	 * @param $iNumber
	 */
	public function addGameMatchPrize($MatchTypeID,$RankStart,$RankEnd,$Level,$SpID,$iNumber,$prizeType,$prizeName)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN),
						array($RankStart,SQLSRV_PARAM_IN),
						array($RankEnd,SQLSRV_PARAM_IN),
						array($Level,SQLSRV_PARAM_IN),
						array($SpID,SQLSRV_PARAM_IN),
						array($iNumber,SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($prizeName),SQLSRV_PARAM_IN),
						array($prizeType,SQLSRV_PARAM_IN));
				
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatchWinPrize_Insert",$params);
		return $arrResults;
	}
	
	/**
	 * 删除某个比赛的奖品信息
	 * @author blj
	 * @param $MatchTypeID
	 * @param $Level
	 */
	public function deleteGameMatchPrize($MatchTypeID,$Level)
	{
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN),
						array($Level,SQLSRV_PARAM_IN));
						
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatchWinPrize_Delete",$params);
		return $arrResults;
	}
	/**
	 * 读取比赛列表
	 * @author xlj
	 * @param $TypeID
	 * @param $Type
	 
	public function getGameMatchList($TypeID,$Type)
	{
		$params = array(array($TypeID,SQLSRV_PARAM_IN),
						array($Type,SQLSRV_PARAM_IN));
						
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameMatch_Select",$params);
		if(is_array($arrResults))
		{
			$iCount = 0;
			foreach ($arrResults as $val)
			{
				$arrResults[$iCount]['MatchName'] = Utility::gb2312ToUtf8($val['MatchName']);
				$iCount++;
			}
		}
		return $arrResults;
	}*/
	/**
	 * 返回奖品发放的名次范围
	 * @author xlj
	 * @param $MatchTypeID
	 * @return Array
	 */
	public function getGameMatchRankArea($MatchTypeID)
	{
		$arrRanks = null;
		$params = array(array($MatchTypeID,SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameMatchWinPrize_SelectRank",$params);
		if(is_array($arrResults))
		{
			for($i=$arrResults['RankStart'];$i<=$arrResults['RankEnd'];$i++)
			{
				$arrRanks[]=$i;
			}
		}
		return $arrRanks;
	}	
	/**
	 * 返回比赛房间信息
	 * @author xlj
	 * @param $RoomID
	 * @return Array
	 */
	public function getGameRoomInfoMatchSetting($RoomID)
	{
		$params = array(array($RoomID,SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameRoomInfoMatchSetting_Select",$params);
		return $arrResults;
	}
	/**
	 * 读取部门列表
	 * @return array
	 */
	public function getDepartmentList()
	{		
		$arrReturns = $this->objMasterDB->fetchAllAssoc("Proc_Department_Select", '');
		if(empty($arrReturns))
			$arrReturns = null;
		else 
		{
			$iCount = 0;
			foreach ($arrReturns as $val)
			{
				$arrReturns[$iCount]['DeptName'] = Utility::gb2312ToUtf8($val['DeptName']);
				$iCount++;
			}
		}
		return $arrReturns;
	}

    /**
     * 读取角色列表
     * @return array
     */
    public function getRoleList()
    {
        $arrReturns = $this->objMasterDB->fetchAllAssoc("P_AdminRole_GetRole", '');
        if(empty($arrReturns))
            $arrReturns = null;
        else
        {
            foreach ($arrReturns as &$arr) {
                $arr['RoleName'] = Utility::gb2312ToUtf8($arr['RoleName']);
            }
            unset($arr);
        }
        return $arrReturns;
    }

	/**
	 * 添加管理员
	 * @param $arrParams
	 * @return -1:失败,0:成功
	 */
	public function setSysUser($arrParams)
	{		
		$iResult = -1;
		$params = array(array($arrParams['DeptID'], SQLSRV_PARAM_IN),
						array(Utility::utf8ToGb2312($arrParams['RealName']), SQLSRV_PARAM_IN),
						array($arrParams['JobNumber'], SQLSRV_PARAM_IN),
						array($arrParams['UserName'], SQLSRV_PARAM_IN),
						array(md5($arrParams['UserPwd']), SQLSRV_PARAM_IN),
						array($arrParams['SysUserName'], SQLSRV_PARAM_IN),
		                array($arrParams['BindAccount'], SQLSRV_PARAM_IN)
					   );
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_Insert", $params);
		if(is_array($arrReturns)) $iResult = $arrReturns['iResult'];		
		return $iResult;
	}
	/**
	 * 重置管理员登陆密码
	 * @param $ID
	 * @return -1:失败,0:成功
	 */
	public function resetSysUserPwd($ID)
	{		
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN),
						array(md5(123456), SQLSRV_PARAM_IN)
					   );
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_ResetUserPwd", $params);
		if(is_array($arrReturns)) $iResult = $arrReturns['iResult'];		
		return $iResult;
	}
	/**
	 * 删除管理员
	 * @param $ID
	 * @param $date
	 * @return -1:失败,0:成功
	 */
	public function deleteSysUser($ID)
	{		
		$iResult = -1;
		$params = array(array($ID, SQLSRV_PARAM_IN)
		                );
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_Delete", $params);	
		return $arrReturns;
	}
	/**
	 * 设置管理员状态
	 * @param $ID;
	 * **/
	public function setSysUserStatus($ID,$Status){
	    $iResult = -1;
	    $params = array(
	        array($ID, SQLSRV_PARAM_IN),
	        array($Status, SQLSRV_PARAM_IN)	        
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_UpdateUserStatus", $params);
	    if(is_array($arrReturns)) 
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 修改密码
	 * @param $OldPwd
	 * @param $NewPwd
	 * @return 0:成功,-1:失败,-2:用户不存在,-3:原密码错误
	 */
	public function updateSysUserPwd($OldPwd,$NewPwd)
	{		
		$iResult = -1;
		$objSessioin = new Session($this->arrConfig['Session']['SessionLoginName']);
		$iAdminID = $objSessioin->get($this->arrConfig['SessionInfo']['AdminID']);
		
		$params = array(array($iAdminID, SQLSRV_PARAM_IN),
						array(md5($OldPwd), SQLSRV_PARAM_IN),
						array(md5($NewPwd), SQLSRV_PARAM_IN)
						);
		$arrReturns = $this->objMasterDB->fetchAssoc("Proc_SysAdmin_UpdateUserPwd", $params);
		if(is_array($arrReturns)) $iResult = $arrReturns['iResult'];		
		return $iResult;
	}
	/** 验证玩家昵称是否可用
	 * @param $LoginName
	 * @return array
	 */
	public function getSysConfineLoginName($LoginName)
	{
		$params = array(array($LoginName, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_SysConfineLoginName_Select", $params);
		if(empty($arrResults)) $arrResults = null;
		return $arrResults;
	}	
	/** 插入通行证和角色关联表
	 * @param $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addPassportRole($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['Passport'], SQLSRV_PARAM_IN),
						array($arrParams['MaxRoleID'], SQLSRV_PARAM_IN),
						array($arrParams['LoginCode'], SQLSRV_PARAM_IN),
						array($arrParams['LoginID'], SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_PassportRole_Insert", $params);
		if($arrResults) $iResult = $arrResults['iResult'];
		return $iResult;
	}	
	/** 
	 * 玩家编号回滚
	 * @param $LoginID
	 * @return -1:失败,0:成功
	 */
	public function updateConfineLoginID($LoginID)
	{
		$iResult = -1;
		$params = array(array($LoginID, SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN),
						array(0, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_ConfineLoginID_Update", $params);
		if($arrResults) $iResult = $arrResults['iResult'];
		return $iResult;
	}	
	/** 
	 * 批量修改服务器ＩＰ
	 * @param 
	 * @return -1:失败,0:成功
	 */
	public function updateServerIP($arrParams)
	{
		$iResult = -1;
		$params = array(array($arrParams['ServerID'], SQLSRV_PARAM_IN),
						array($arrParams['ServerIP'], SQLSRV_PARAM_IN),
						array($arrParams['IP'], SQLSRV_PARAM_IN,SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY),SQLSRV_SQLTYPE_VARBINARY(128))
						);
		$arrResults = $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_UpdateServerIP", $params);
		if($arrResults) $iResult = $arrResults['iResult'];
		return $iResult;
	}	
	
	public function getServerIP($ServerIP)
	{
		$params = array(array($ServerIP, SQLSRV_PARAM_IN));
		$arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GameServerInfo_SelectByServerIP", $params);
		return $arrResults;
	}	
	/**
	 * 添加玩管理员家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function addUserPrivilege($arrParams)
	{
	    $iResult = -1;
	    $params = array(array($arrParams['iRoleID'], SQLSRV_PARAM_IN),
	        array($arrParams['iUserRight'], SQLSRV_PARAM_IN),
	        array($arrParams['iMasterRight'], SQLSRV_PARAM_IN),
	        array($arrParams['iSystemRight'], SQLSRV_PARAM_IN),
	        array(Utility::utf8ToGb2312($arrParams['RoleName']), SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_UserPrivilege_Insert", $params);
	    if($arrReturns) $iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 删除玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function delUserPrivilege($RoleID)
	{
	    $iResult = -1;
	    $params = array(array($RoleID, SQLSRV_PARAM_IN));
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_UserPrivilege_Delete", $params);
	    if(is_array($arrResult) && count($arrResult)>0) $iResult=$arrResult['iResult'];
	    return $iResult;
	}
	/**
	 * 查询玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function getUserPrivilege($RoleID)
	{
	    $params = array(array($RoleID, SQLSRV_PARAM_IN));
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_UserPrivilege_Select", $params);
	    $arrResult['RoleName'] = Utility::gb2312ToUtf8($arrResult['RoleName']);
	    return $arrResult;
	}
	/**
	 * 查询玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function setUserPrivilege($RoleID, $UserRight, $MasterRight,$SystemRight)
	{
	    $params = array(
	        array($RoleID, SQLSRV_PARAM_IN),
	        array($UserRight, SQLSRV_PARAM_IN),
	        array($MasterRight, SQLSRV_PARAM_IN),
	        array($SystemRight, SQLSRV_PARAM_IN)
	        
	    );
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_UserPrivilege_Update", $params);
	    return $arrResult['iResult'];
	}



	/**
	 * 添加黑名单
	 * @param $arrParam
	 */
	public function setSysBlack($arrParam)
	{
	    $iResult = -1;
	    $params = array(array($arrParam['LimitStr'], SQLSRV_PARAM_IN),
	        array($arrParam['TypeID'], SQLSRV_PARAM_IN)
	    );
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_Blacklist_Insert", $params);
	    if(!empty($arrResult)) $iResult=$arrResult['iResult'];
	    return $iResult;
	}
	
	/**
	 * 删除黑名单
	 * @param $LimitStr
	 */
	public function deleteSysBlack($LimitStr)
	{
	    $iResult = -1;
	    $params = array(array($LimitStr, SQLSRV_PARAM_IN)
	    );
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_Blacklist_Delete", $params);
	    if(!empty($arrResult)) $iResult=$arrResult['iResult'];
	    return $iResult;
	}

	/**
	 * 添加报警名单
	 * @param $arrParam
	 */
	public function setSysWarn($arrParam)
	{
	    $iResult = -1;
	    $params = array(array($arrParam['WarnStr'], SQLSRV_PARAM_IN),
	        array($arrParam['TypeID'], SQLSRV_PARAM_IN)
	    );
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_Warnlist_Insert", $params);
	    if(!empty($arrResult)) $iResult=$arrResult['iResult'];
	    return $iResult;
	}
	
	/**
	 * 删除报警名单
	 * @param $LimitStr
	 */
	public function deleteSysWarn($WarnStr)
	{
	    $iResult = -1;
	    $params = array(array($WarnStr, SQLSRV_PARAM_IN)
	    );
	    $arrResult = $this->objMasterDB->fetchAssoc("Proc_Warnlist_Delete", $params);
	    if(!empty($arrResult)) $iResult=$arrResult['iResult'];
	    return $iResult;
	}
	/**
	 * 读取充值折扣
	 * @return array
	 */
	function getCardChargeRate($CardID)
	{
	    $params = array(array($CardID, SQLSRV_PARAM_IN));
	    $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_CardChargeRate_Select", $params);
	    return $arrResults;
	}
	/**
	 * 添加充值折扣
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addCardChargeRate($arrParams)
	{
	    $iResult = -1;
	    $params = array(array($arrParams['CardID'], SQLSRV_PARAM_IN),
	        array($arrParams['ChargeRate'], SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_CardChargeRate_Insert", $params);
	    if(is_array($arrReturns) && count($arrReturns)>0)
	        $iResult = $arrReturns['iResult'];
	    return $iResult;
	}

    /**添加超级用户修改日志
     * @param $RoleID
     * @param $SuperLevel
     */
    function addSuperUser($RoleID,$SuperLevel,$RealName){
        $params = array(array($RoleID, SQLSRV_PARAM_IN),
            array($SuperLevel, SQLSRV_PARAM_IN),
            array($RealName, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_SuperUser_Insert",$params);
        return $arrResults;
    }

    /**更新超级用户
     * @param $RoleID
     * @param $SuperLevel
     * @return mixed
     */
    function updateSuperUser($RoleID,$SuperLevel){
        $params = array(array($RoleID, SQLSRV_PARAM_IN),
            array($SuperLevel, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_SuperUser_Update",$params);
        return $arrResults;
    }

    /**删除用户
     * @param $RoleID
     * @return mixed
     */
    function deleteSuperUser($RoleID){
        $params = array(
            array($RoleID,SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_SuperUser_Delete",$params);
        return $arrResults;
    }

    /**获取商人用户信息
     * @param $RoleID
     * @return mixed
     */
    public function getSuperUser($RoleID){
        $params = array(array($RoleID,SQLSRV_PARAM_IN));
        $arrResult = $this->objMasterDB->fetchAssoc("P_SuperUser_Select",$params);
        if(is_array($arrResult)) $arrResult['RealName'] = Utility::gb2312ToUtf8($arrResult['RealName']);
        return $arrResult;
    }

    /**
     * @param $
     */
    public function createRechargeCard($money,$num){
        $params = array(
            array($money,SQLSRV_PARAM_IN),
            array($num,SQLSRV_PARAM_IN),
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_RechargeCard_Create",$params);
        return $arrResults;
    }

    /**
     * @param $state 修改状态
     * @param $where 条件，批量修改条件
     * @param $type  0 为 全部修改 忽略where条件
     */
    public function updateRechargeCardState($state,$where,$type){
        $params = array(
            array($state,SQLSRV_PARAM_IN),
            array($where,SQLSRV_PARAM_IN),
            array($type,SQLSRV_PARAM_IN),
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_RechargeCard_State_Update",$params);
        return $arrResults;
    }

    /**该接口无法删除已充值的数据
     * @param $where
     * @param $type 0 删除所有未充值的数据。
     * @return mixed
     */
    public function deleteRechargeCard($where,$type){
        $params = array(
            array($where,SQLSRV_PARAM_IN),
            array($type,SQLSRV_PARAM_IN),
        );
        $arrResults = $this->objMasterDB->fetchAssoc("P_RechargeCard_Delete",$params);
        return $arrResults;
    }

    /**
     * @return mixed
     */
    public function summaryRechargeCard($StartTime,$EndTime){
	    $params = array(
	        array($StartTime,SQLSRV_PARAM_IN),
	        array($EndTime,SQLSRV_PARAM_IN)
	    );
        $arrResults = $this->objMasterDB->fetchAllAssoc("P_RechargeCard_Select_GroupBy_State", $params);
        return $arrResults;
    }

    /**
     * @param $LowVersion
     * @param $HighVersion
     * @param $FileName
     * @param $FileURL
     * @param $ServerID
     */
    public function addAndroidVersion($LowVersion,$HighVersion,$FileName,$FileURL,$ServerID){
        $params = array(
            array($LowVersion,SQLSRV_PARAM_IN),
            array($HighVersion,SQLSRV_PARAM_IN),
            array($FileName,SQLSRV_PARAM_IN),
            array($FileURL,SQLSRV_PARAM_IN),
            array($ServerID,SQLSRV_PARAM_IN),
        );

        $iResult = $this->objMasterDB->fetchAssoc("P_AndroidVersionDiff_Insert",$params);
        return $iResult;
    }

    public function updateAndroidVersion($VerID,$LowVersion,$HighVersion,$FileName,$FileURL,$ServerID){
        $params = array(
            array($VerID,SQLSRV_PARAM_IN),
            array($LowVersion,SQLSRV_PARAM_IN),
            array($HighVersion,SQLSRV_PARAM_IN),
            array($FileName,SQLSRV_PARAM_IN),
            array($FileURL,SQLSRV_PARAM_IN),
            array($ServerID,SQLSRV_PARAM_IN),
        );

        $iResult = $this->objMasterDB->fetchAssoc("P_AndroidVersionDiff_Update",$params);
        return $iResult;
    }

    public function getAndroidVersion($VerID){
        $params = array(
            array($VerID,SQLSRV_PARAM_IN),
        );

        $arrResult =  $this->objMasterDB->fetchAssoc("P_AndroidVersionDiff_Select",$params);
        return $arrResult;
    }
    function editServer($Where,$ServerIP,$IP){
        $params = array(
            array($Where,SQLSRV_PARAM_IN),
            array($ServerIP,SQLSRV_PARAM_IN),
            array($IP, SQLSRV_PARAM_IN,SQLSRV_PHPTYPE_STREAM(SQLSRV_ENC_BINARY),SQLSRV_SQLTYPE_VARBINARY(128))
        );
        return $this->objMasterDB->fetchAssoc("Proc_GameServerInfo_Edit",$params);
    }
    function addGameSign($KindID,$RoomType,$SignType,$SignValue,$SignAward,$PhoneExtra){
        $params = array(
            array($KindID,SQLSRV_PARAM_IN),
            array($RoomType,SQLSRV_PARAM_IN),
            array($SignType, SQLSRV_PARAM_IN),
            array($SignValue,SQLSRV_PARAM_IN),
            array($SignAward,SQLSRV_PARAM_IN),
        	array($PhoneExtra,SQLSRV_PARAM_IN)
        );
        return $this->objMasterDB->fetchAssoc("Proc_GameSign_Insert",$params);
    }    
    function getGameSignList($KindID,$RoomType)
    {
         $params = array(
            array($KindID,SQLSRV_PARAM_IN),
            array($RoomType,SQLSRV_PARAM_IN)
        );
        return $this->objMasterDB->fetchAllAssoc("Proc_GameSign_Select",$params);
    }
    function delGameSign($KindID,$RoomType)
    {
        $params = array(
            array($KindID,SQLSRV_PARAM_IN),
            array($RoomType,SQLSRV_PARAM_IN)
        );
        $ret = $this->objMasterDB->fetchAssoc("Proc_GameSign_Delete",$params);
        return $ret['iResult'];
    }
    /***
     * 获取号码归属地
     * @param string $tel 手机号
     * */
    public function getTelSegment($Tel){
        $params = array(
            array($Tel,SQLSRV_PARAM_IN)
        );
        $ret = $this->objMasterDB->fetchAssoc("Proc_TelSegment_Select",$params);
        if($ret){
            $ret['MobileArea'] = Utility::gb2312ToUtf8($ret['MobileArea']);
            $ret['MobileType'] = Utility::gb2312ToUtf8($ret['MobileType']);
        }
        return $ret;
    }
    /***
     * 获取身份证归属地
     * @param string $tel 手机号
     * */
    public function getCardSegment($CardNo){
        $params = array(
            array($CardNo,SQLSRV_PARAM_IN)
        );
        $ret = $this->objMasterDB->fetchAssoc("Proc_CardSegment_Select",$params);
        if($ret){
            $ret['DQ'] = Utility::gb2312ToUtf8($ret['DQ']);
        }
        return $ret;
    }
    /***
     * 获取充值方式
     * @param string $CardType 卡号
     * */
    public function getCardNameByCardType($CardType){
    	$params = array(
    			array($CardType,SQLSRV_PARAM_IN)
    	);
    	$ret = $this->objMasterDB->fetchAssoc("Proc_CardChargeRate_SelectByType",$params);
   		if($ret){
   			$ret['CardName'] = Utility::gb2312ToUtf8($ret['CardName']);
   		}
    	return $ret;
    }
	/**
	 * 防攻击IP验证
	 * @IP
	 */
	public function CheckLoginIp($IP,$UserName,$SiteID,$LimitSeconds,$VisitCount)
	{
	    $iResult  = -1;
	    $params = array(array($IP, SQLSRV_PARAM_IN),
	        array($UserName, SQLSRV_PARAM_IN),	        
	        array($SiteID, SQLSRV_PARAM_IN),
	        array($LimitSeconds, SQLSRV_PARAM_IN),
	        array($VisitCount, SQLSRV_PARAM_IN)
	    );
	    $arrReturns = $this->objMasterDB->fetchAssoc("Proc_Login_IP_Check", $params);
	    if(!empty($arrReturns))	$iResult = $arrReturns['iResult'];
	    return $iResult;
	}
	/**
	 * 获取游戏服务器记录条数
	 */
	public function getServerGameCount()
	{
	    $arrReturns=$this->objMasterDB->fetchAllAssoc("Proc_getServerGameCount","");
        return count($arrReturns);
	}
	/**
	 * 获取游戏服务器分页
	 * @CurPage 当前页
	 * @PageSize 每页记录数目
	 */
	public function getServerGame($CurPage,$PageSize)
	{
	    $param = array(array($CurPage,SQLSRV_PARAM_IN),
	                   array($PageSize,SQLSRV_PARAM_IN)
	    );
	    $arrReturns=$this->objMasterDB->fetchAllAssoc("Proc_getServerGame",$param);
	    return $arrReturns;
	}
	/**
	 * 获取游戏服务器端口
	 * @ServerType
	 * @LANServerIP
	 */
	public function getServerPort($ServerType,$LANServerIP)
	{
	    $params = array(
	        array($ServerType,SQLSRV_PARAM_IN),
	        array($LANServerIP,SQLSRV_PARAM_IN)
	    );
	    return $this->objMasterDB->fetchAllAssoc("Proc_getServerPort", $params);
	}
	
	
	public function getGameDataCount()
	{
	    $arrReturns=$this->objMasterDB->fetchAllAssoc("proc_GameDataCount","");
	    return $arrReturns;
	}



    function addWechatUser($arrParams)
    {
        $iResult = -1;
        $params = array(array($arrParams['id'], SQLSRV_PARAM_IN),
            array($arrParams['TypeID'], SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['weixinname']), SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['noticetip']), SQLSRV_PARAM_IN)
        );

        $arrReturns = $this->objMasterDB->fetchAssoc("Proc_WechatUser_Insert", $params);
        if(is_array($arrReturns) && count($arrReturns)>0)
            $iResult = $arrReturns['iResult'];
        return $iResult;
    }


    function topWechatId($ShowWay)
    {
        $params = array(array($ShowWay, SQLSRV_PARAM_IN));
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_WechatUser_SetTop", $params);
        return $arrResults;
    }


    function getWechatId($TypeID)
    {
        $params = array(array($TypeID, SQLSRV_PARAM_IN));
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_WechatUser_SelectID", $params);
        return $arrResults;
    }


    function setColorTop($roleid,$iscolor,$istop){
        $iResult = 0;
        $params = array(
            array($roleid, SQLSRV_PARAM_IN),
            array($iscolor, SQLSRV_PARAM_IN),
            array($istop, SQLSRV_PARAM_IN),
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_OnlineListSet_update", $params);
        if(is_array($arrResults) && count($arrResults)>0)
            $iResult = $arrResults[0]['iResult'];
        return $iResult;
    }

    function getColorTop($roleid){
        $params = array(
            array($roleid, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_OnlineListSet_Select", $params);
        if(is_array($arrResults) && count($arrResults)>0)
        {
            $arrResults[0]['descript']=Utility::gb2312ToUtf8($arrResults[0]['descript']);
        }
        return $arrResults;
    }


    function getReviewNum(){
        $params = array();
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_getReviewNum", $params);
        return $arrResults;
    }


    function getMsgInfo($MsgId, $ClassId){
        $params = array(
            array($MsgId, SQLSRV_PARAM_IN),
            array($ClassId, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_MsgInfo_Get", $params);
        if(is_array($arrResults) && count($arrResults)>0)
        {
            $arrResults[0]['MsgTitle']=Utility::gb2312ToUtf8($arrResults[0]['MsgTitle']);
            $arrResults[0]['MsgContent']=Utility::gb2312ToUtf8($arrResults[0]['MsgContent']);
        }
        return $arrResults;
    }
    function getMsgInfoFront($ClassId){
        $params = array(
            array($ClassId, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_GetMsgInfo", $params);
        if(is_array($arrResults) && count($arrResults)>0)
        {
            $arrResults[0]['MsgContent']=Utility::gb2312ToUtf8($arrResults[0]['MsgContent']);
        }
        return $arrResults;
    }


    function addMsgInfo($arrParams){
        $iResult = -1;
        $params = array(
            array($arrParams['MsgID'], SQLSRV_PARAM_IN),
            array($arrParams['ClassId'], SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['MsgTitle']), SQLSRV_PARAM_IN),
            array($arrParams['StartTime'], SQLSRV_PARAM_IN),
            array($arrParams['EndTime'], SQLSRV_PARAM_IN),
            array($arrParams['SortID'], SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($arrParams['MsgContent']), SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_MsgInfo_Add", $params);
        if(is_array($arrResults) && count($arrResults)>0)
            $iResult = $arrResults['iResult'];
        return $iResult;
    }


    function delMsgInfo($MsgID){
        $params = array(
            array($MsgID, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_MsgInfo_Del", $params);
        return $arrResults;
    }


    function setMsgLocked($MsgID){
        $params = array(
            array($MsgID, SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_MsgInfo_Set", $params);
        return $arrResults;
    }


    function addOnlineDes($RoleId,$descript){
        $params = array(
            array($RoleId, SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript), SQLSRV_PARAM_IN)
        );
        $arrResults = $this->objMasterDB->fetchAllAssoc("Proc_OnlineDess_Add", $params);
        return $arrResults;
    }


    //设置支付类别信息
    function setPayclass($classId, $className, $bank, $cardNo, $cardName, $descript)
    {
        $params = array(
            array($classId,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($className),SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($bank),SQLSRV_PARAM_IN),
            array($cardNo,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($cardName),SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript),SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayClass_Update",$params);
        return $arrResult;
    }

    //新增支付通道
    function addPaychannel($channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status)
    {
        $params = array(
            array(Utility::utf8ToGb2312($channelname),SQLSRV_PARAM_IN),
            array($mchid,SQLSRV_PARAM_IN),
            array($appid,SQLSRV_PARAM_IN),
            array($appkey,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($config),SQLSRV_PARAM_IN),
            array($noticeurl,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript),SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayChannel_Insert",$params);
        return $arrResult;
    }

    //编辑支付通道
    function editPaychannel($channelid,$channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status)
    {
        $params = array(
            array($channelid,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($channelname),SQLSRV_PARAM_IN),
            array($mchid,SQLSRV_PARAM_IN),
            array($appid,SQLSRV_PARAM_IN),
            array($appkey,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($config),SQLSRV_PARAM_IN),
            array($noticeurl,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript),SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayChannel_Update",$params);
        return $arrResult;
    }

    //删除支付通道
    function deletePaychannel($channelid) {
        $params = array(
            array($channelid,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayChannel_del",$params);
        return $arrResult;
    }

    //设置支付通道状态
    function changePaychannel($channelid, $status) {
        $params = array(
            array($channelid,SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayChannel_Set",$params);
        return $arrResult;
    }

    //新增金额
    function addPayamount($amount) {
        $params = array(
            array($amount,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayAmount_Insert",$params);
        return $arrResult;
    }

    function editPayamount($amountid, $amount) {
        $params = array(
            array($amountid,SQLSRV_PARAM_IN),
            array($amount,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayAmount_Update",$params);
        return $arrResult;
    }

    //删除金额
    function deletePayamount($amountid) {
        $params = array(
            array($amountid,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayAmount_del",$params);
        return $arrResult;
    }

    //编辑通道金额
    function editPayrelation($id, $classid, $amountid, $channelid) {
        $params = array(
            array($id,SQLSRV_PARAM_IN),
            array($classid,SQLSRV_PARAM_IN),
            array($amountid,SQLSRV_PARAM_IN),
            array($channelid,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayRelation_Update",$params);
        return $arrResult;
    }

    //新增通道金额
    function addPayrelation($classid, $amountid, $channelid) {
        $params = array(
            array($classid,SQLSRV_PARAM_IN),
            array($amountid,SQLSRV_PARAM_IN),
            array($channelid,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayRelation_Insert",$params);
        return $arrResult;
    }


    //删除通道金额
    function deletePayrelation($id) {
        $params = array(
            array($id,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GamePayRelation_Delete",$params);
        return $arrResult;
    }

    //首页数据
    function getHomedata() {
        $params = array();
        $arrResults = $this->objMasterDB->fetchAllAssoc("P_IndexDashBord_Data", $params);
        return $arrResults;
    }
    function getRegdata() {
        $params = array();
        $arrResults = $this->objMasterDB->fetchAllAssoc("P_IndexDashBoard_RegData", $params);
        return $arrResults;
    }
    function getOrderdata() {
        $params = array();
        $arrResults = $this->objMasterDB->fetchAllAssoc("P_IndexDashBoard_OrderData", $params);
        return $arrResults;
    }

    function getUserGameRank($roleId) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_UserGameRankSelect",$params);
        foreach ($arrResult as  &$v) {
            $v['LoginName'] = Utility::gb2312ToUtf8($v['LoginName']);
        }
        unset($v);
        return $arrResult;
    }

    //获取邮件
    function getEmail($roleId) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_PayRecordInfo_Select",$params);
        return $arrResult;
    }

    //更新任务
    function updateGameTask($roomId, $reqRound, $award, $taskname) {
        $params = array(
            array($roomId,SQLSRV_PARAM_IN),
            array($reqRound,SQLSRV_PARAM_IN),
            array($award,SQLSRV_PARAM_IN),
            array($taskname,SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_GameActivity_Update",$params);
        return $arrResult;
    }

    //上下架任务
    function setGameTaskStatus($roomId, $status) {
        $params = array(
            array($roomId,SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc(" P_GameActivityStaus_Set",$params);
        return $arrResult;
    }

    //新增角色
    function addRole($roleName, $descript) {
        $params = array(
            array(Utility::utf8ToGb2312($roleName),SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript),SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_AdminRole_Insert",$params);
        return $arrResult;
    }

    //修改角色
    function editRole($roleId, $roleName, $descript) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($roleName),SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($descript),SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_AdminRole_Update",$params);
        return $arrResult;
    }

    //删除角色
    function deleteRole($roleId) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_AdminRole_Delete",$params);
        return $arrResult;
    }

    //更新菜单显示
    function showMenu($id, $status) {
        $params = array(
            array($id,SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_menu_SetStatus",$params);
        return $arrResult;
    }

    //更新菜单排序
    function doOrder($id, $order) {
        $params = array(
            array($id,SQLSRV_PARAM_IN),
            array($order,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_menu_SetOrder",$params);
        return $arrResult;
    }

    //新增子菜单
    function addSubMenu($pid, $name, $controller, $order) {
        $params = array(
            array(Utility::utf8ToGb2312($name),SQLSRV_PARAM_IN),
            array($pid,SQLSRV_PARAM_IN),
            array($controller,SQLSRV_PARAM_IN),
            array('',SQLSRV_PARAM_IN),
            array('',SQLSRV_PARAM_IN),
            array($order,SQLSRV_PARAM_IN),
            array(1,SQLSRV_PARAM_IN),

        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_Menu_Insert",$params);
        return $arrResult;
    }

    //新增菜单目录
    function addSubMenu2($name, $group, $groupName, $order) {
        $params = array(
            array(Utility::utf8ToGb2312($name),SQLSRV_PARAM_IN),
            array(0,SQLSRV_PARAM_IN),
            array('',SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($groupName),SQLSRV_PARAM_IN),
            array($group,SQLSRV_PARAM_IN),
            array($order,SQLSRV_PARAM_IN),
            array(1,SQLSRV_PARAM_IN),

        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_Menu_Insert",$params);
        return $arrResult;
    }

    //删除菜单
    function deleteMenu($menuId) {
        $params = array(
            array($menuId,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_Menu_Delete",$params);
        return $arrResult;
    }

    //修改菜单目录
    function editMenu1($menuId, $menuname) {
        $params = array(
            array($menuId,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($menuname),SQLSRV_PARAM_IN),
            array('',SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_Menu_Update",$params);
        return $arrResult;
    }

    //修改菜单
    function editMenu2($menuId,$menuname, $controller) {
        $params = array(
            array($menuId,SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($menuname),SQLSRV_PARAM_IN),
            array(Utility::utf8ToGb2312($controller),SQLSRV_PARAM_IN),
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_Menu_Update",$params);
        return $arrResult;
    }

    //修改玩家菜单
    function setRoleMenu($roleId,$menu) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN),
            array($menu,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_AdminRole_SetRule",$params);
        return $arrResult;
    }

    //根据id获取菜单
    function getMenuById($roleId) {
        $params = array(
            array($roleId,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("P_AdminRole_Select",$params);
        foreach ($arrResult as &$arr) {
            $arr['RoleName'] = Utility::gb2312ToUtf8($arr['RoleName']);
            $arr['Descript'] = Utility::gb2312ToUtf8($arr['Descript']);
        }
        unset($arr);
        return $arrResult;
    }

    //修改账号角色
    function setUserRole($adminId, $roleId) {
        $params = array(
            array($adminId,SQLSRV_PARAM_IN),
            array($roleId,SQLSRV_PARAM_IN)
        );

        $arrResult = $this->objMasterDB->fetchAllAssoc("Proc_SysConfig_UpdateRole",$params);
        return $arrResult;
    }



    function updateThirdOrder($orderid, $status) {
        $params = array(
            array($orderid,SQLSRV_PARAM_IN),
            array($status,SQLSRV_PARAM_IN)
        );
        $arrResult = $this->objMasterDB->fetchAllAssoc("P_UpdateAutoPayOrderStatus",$params);
        return $arrResult;
    }
}
?>