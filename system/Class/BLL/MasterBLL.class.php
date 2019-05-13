<?php
require ROOT_PATH . 'Class/DAL/MasterDAL.class.php';
class MasterBLL
{
	private $objMasterDAL = NULL;
	public function __construct()
    {
        $this->objMasterDAL = new MasterDAL();
    }
	/**
	 * 管理员登陆
	 * @param $strUserName 用户名
	 * @param $strUserPwd  密码
	 * @return -1:密码错误,0:用户名不存在,1:成功
	 */
	public function chkAdminLogin($strUserName,$strUserPwd,$bindaccout)
	{
		return $this->objMasterDAL->chkAdminLogin($strUserName,$strUserPwd,$bindaccout);
	}


	public function getRoleSession($roleId){
	    return $this->objMasterDAL->getRoleSession($roleId);
    }


    public function setRoleSession($roleId,$sessionId){
        return $this->objMasterDAL->setRoleSession($roleId,$sessionId);
    }


	/**
	 * 服务器列表
	 * @param $ServerType 服务器类型
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getServerList($ServerType,$Locked)
	{
		return $this->objMasterDAL->getServerList($ServerType,$Locked);
	}
	
	/**
	 * 添加服务器配置
	 * @param $arrParams 
	 * @param $ServerType 服务器类型,0:数据库服务器,1、游戏服务器,2、登录服务器,3、下载服务器,4、版本服务器,5、银行服务器,6、中心服务器,7.大厅服务器
	 * @return 0:成功,-1:失败
	 */
	function AddServer($arrParams,$ServerType)
	{
		return $this->objMasterDAL->AddServer($arrParams,$ServerType);
	}
	/**
	 * 修改大厅服务器配置
	 * @return 0:成功,-1:失败
	 */
	function UpdateServer($arrParams)
	{		
		return $this->objMasterDAL->UpdateServer($arrParams);
	}
	/**
	 * 设置服务器禁用/启用状态
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return 0:成功,-1:失败
	 */
	function setServerLocked($ServerID,$ServerType)
	{
		return $this->objMasterDAL->setServerLocked($ServerID,$ServerType);
	}
	/**
	 * 读取指定服务器配置信息
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return array
	 */
	function getServerInfo($ServerID,$ServerType)
	{
		return $this->objMasterDAL->getServerInfo($ServerID,$ServerType);
	}
	/**
	 * 删除服务器配置信息
	 * @param $ServerID 服务器ID
	 * @param $ServerType 服务器类型
	 * @return 0:成功,-1:失败
	 */
	function delServer($ServerID,$ServerType)
	{
		return $this->objMasterDAL->delServer($ServerID,$ServerType);
	}
	/**
	 * 服务器列表
	 * @return array
	 
	function getGameServerList()
	{
		return $this->objMasterDAL->getGameServerList();
	}*/
	/**
	 * 读取指定游戏服务器配置信息
	 * @param $ServerID 服务器ID
	 * @return array
	 */
	function getGameServer($ServerID)
	{
		return $this->objMasterDAL->getGameServer($ServerID);
	}
	
	/**
	 * 读取指定机器人配置信息
	 * @param $NameID 机器人ID
	 * @return array
	 */
	function getRobotNamePool($NameID)
	{
	    return $this->objMasterDAL->getRobotNamePool($NameID);
	}
	/**
	 * 读取指定机器人账号信息
	 * @param $UserID 机器人ID
	 * @return array
	 */
	function getRobotUser($UserID)
	{
	    return $this->objMasterDAL->getRobotUser($UserID);
	}
	/**
	 * 读取指定机器人账号信息
	 * @param $UserID 机器人ID
	 * @return array
	 */
	function getRoomRobot($RoomID)
	{
	    return $this->objMasterDAL->getRoomRobot($RoomID);
	}
	/**
	 * 读取机器人ID列表
	 */
	function getRobotIDList()
	{
	    return $this->objMasterDAL->getRobotIDList();
	}
	/**
	 * 添加服务器配置
	 * @return 0:成功,-1:失败
	 */
	function addGameServer($arrParams)
	{
		return $this->objMasterDAL->addGameServer($arrParams);
	}
	
	/**
	 * 添加机器人信息
	 * @return 0:成功,-1:失败
	 */
	function addRobotNamePool($arrParams)
	{
	    return $this->objMasterDAL->addRobotNamePool($arrParams);
	}
	/**
	 * 添加机器人账号
	 * @return 0:成功,-1:失败
	 */
	function addRobotUser($arrParams)
	{
	    return $this->objMasterDAL->addRobotUser($arrParams);
	}
	/**
	 * 批量添加机器人账号
	 * @return 0:成功,-1:失败
	 */
	function addAllRobotUser($arrParams)
	{
	    return $this->objMasterDAL->addAllRobotUser($arrParams);
	}
	/**
	 * 添加机器人账号
	 * @return 0:成功,-1:失败
	 */
	function addRoomRobot($arrParams)
	{
	    return $this->objMasterDAL->addRoomRobot($arrParams);
	}
	/**
	 * 删除游戏服务器配置信息
	 * @param $ServerID 服务器ID
	 * @return 大于0:成功,0:失败,-2:数据库异常
	 
	function delGameServer($ServerID)
	{
		return $this->objMasterDAL->delGameServer($ServerID);
	}*/
	/**
	 * 游戏种类列表
	 * @param $ClassID 种类标识,0未分类类型,1牌类游戏,2骨牌游戏,3棋牌游戏,4休闲游戏
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getGameKindList($ClassID,$Locked)
	{
		return $this->objMasterDAL->getGameKindList($ClassID,$Locked);
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
		return $this->objMasterDAL->addGameKind($KindName,$KindID,$ProcessName,$ServerDLL,$ClassID,$CustomField,$PayTypeID,$SysBank,$RobotBank);
	}
	/**
	 * 删除游戏种类
	 * @param $KindID 
	 * @return 0:成功,-1:失败
	 */
	function delGameKind($KindID)
	{
		return $this->objMasterDAL->delGameKind($KindID);
	}
	/**
	 * 设置游戏种类禁用/启用
	 * @param $KindID	游戏种类ID
	 * $iResult=0:成功,-1:失败
	 */
	function setGameKindLocked($KindID)
	{
		return $this->objMasterDAL->setGameKindLocked($KindID);
	}
	/**
	 * 读取指定游戏种类信息
	 * @param $KindID 游戏种类ID
	 * @return array
	 */
	function getGameKindInfo($KindID)
	{
		return $this->objMasterDAL->getGameKindInfo($KindID);
	}
	/**
	 * 读取游戏级别
	 * @param $KindID 游戏种类ID
	 * @return array
	 */
	function getGameLevelList($KindID)
	{
		return $this->objMasterDAL->getGameLevelList($KindID);
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
		return $this->objMasterDAL->addGameLevel($ID,$KindID,$LevelType,$LevelID,$LevelName,$LBound,$CellAmount,$ClothesImage);
	}
	/**
	 * 删除游戏级别
	 * @param $ID 
	 * @return 0:成功,-1:失败
	 */
	function delGameLevel($ID)
	{
		return $this->objMasterDAL->delGameLevel($ID);
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
		return $this->objMasterDAL->addGameVersion($VerID,$VerType,$KindID,$FileName,$FileURL,$FileCategory,$ServerID,$Version,$LocalPath);
	}
	/**
	 * 删除游戏版本
	 * @param $VerID 
	 * @return 0:成功,-1:失败
	 */
	function delGameVersion($VerID)
	{
		return $this->objMasterDAL->delGameVersion($VerID);
	}
	/**
	 * 读取版本
	 * @param $VerType 版本类型(1:游戏种类版本,2:大厅版本,3:道具版本)
	 * @param $KindID 游戏种类ID,大厅ID=0
	 * @return array
	 */
	function getGameVersionList($VerType,$KindID)
	{	
		return $this->objMasterDAL->getGameVersionList($VerType,$KindID);
	}
	/**
	 * 读取版本
	 * @param $VerID 
	 * @return array
	 */
	function getGameVersion($VerID)
	{	
		return $this->objMasterDAL->getGameVersion($VerID);
	}
	/**
	 * 样式列表
	 * @param $Locked  是否锁定
	 * @return array
	 */
	function getStyleSheetList($Locked)
	{
		return $this->objMasterDAL->getStyleSheetList($Locked);
	}
	/**
	 * 桌子规格
	 * @param $TableSchemeID 
	 * @return array
	 */
	function getGameTableSchemeList($TableSchemeID)
	{
		return $this->objMasterDAL->getGameTableSchemeList($TableSchemeID);
	}
	/**
	 * 配置房间信息
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameRoom($arrParams)
	{	
		return $this->objMasterDAL->addGameRoom($arrParams);
	}	
	/**
	 * 删除房间信息
	 * @param $RoomID 
	 * @return 0:成功,-1:失败
	 */
	function delGameRoomInfo($RoomID)
	{
		return $this->objMasterDAL->delGameRoomInfo($RoomID);
	}
	/**
	 * 读取房间信息
	 * @param $RoomID  
	 * @return array
	 */
	function getGameRoomInfo($RoomID)
	{
		return $this->objMasterDAL->getGameRoomInfo($RoomID);
	}
	
	/**
	 * 读取全部房间信息
	 * @return array
	 */
	function getGameRoomInfoList()
	{
	    return $this->objMasterDAL->getGameRoomInfoList();
	}
	/**
	 * 房间列表
	 * @param $Key 
	 * @param $TypeID 条件匹配类型,1:按KindID查找
	 * @return array
	 */
	function getGameRoomList($Key,$TypeID)
	{
		return $this->objMasterDAL->getGameRoomList($Key,$TypeID);
	}
	/**
	 * 读取游戏节点
	 * @param $Locked 锁定状态，默认0：正常,1：锁定
	 * @return array
	 */
	function getGameTypeList($Locked)
	{
		return $this->objMasterDAL->getGameTypeList($Locked);
	}
	/**
	 * 读取标签
	 * @param $Locked 锁定状态，默认0：正常,1：锁定
	 * @return array
	 */
	function getTagClassList($Locked)
	{
		return $this->objMasterDAL->getTagClassList($Locked);
	}
	/**
	 * 添加游戏节点
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameType($arrParams)
	{
		return $this->objMasterDAL->addGameType($arrParams);
	}
	/**
	 * 删除游戏节点
	 * @param $iTypeID 
	 * @return 0:成功,-1:失败,-3:该节点下有子节点,不能删除
	 */
	function delGameType($iTypeID)
	{
		return $this->objMasterDAL->delGameType($iTypeID);
	}
	/**
	 * 读取游戏节点详细信息
	 * @param $TypeID 
	 * @return array
	 */
	function getGameTypeInfo($TypeID)
	{
		return $this->objMasterDAL->getGameTypeInfo($TypeID);
	}	
	/*
	 * 读取游戏父节点列表
	 * @return array
	 */
	function getParentNode()
	{
	    return $this->objMasterDAL->getParentNode();
	}
	/**
	 * MapType列表
	 * @return array
	 */
	function getMapTypeALL()
	{
		return $this->objMasterDAL->getMapTypeALL();
	}
	/**
	 * Map分库关联数据
	 * @return array
	 */
	function getMapListALL()
	{
		return $this->objMasterDAL->getMapListALL();
	}
	/**
	 * 添加MAP
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addMap($arrParams)
	{
		return $this->objMasterDAL->addMap($arrParams);
	}
	/**
	 * 读取MAP配置信息
	 * @return array
	 */
	function getMapInfo($ID)
	{
		return $this->objMasterDAL->getMapInfo($ID);
	}
	/**
	 * 读取游戏房间及游戏种类信息
	 * @param $LANServerIP 线路信息
	 * @return array
	 */
	function getRoomAndKindList($LANServerIP)
	{
		return $this->objMasterDAL->getRoomAndKindList($LANServerIP);
	}
	/**
	 * 统计指定服务器下的房间数量
	 * @param $ServerID 服务器ID
	 * @return array
	 */
	function getGameRoomCount($ServerID)
	{
		return $this->objMasterDAL->getGameRoomCount($ServerID);
	}
	
	/**
	 * 游戏桌子类型列表
	 */
	function getGameTableList($TableSchemeID)
	{
		return $this->objMasterDAL->getGameTableList($TableSchemeID);
	}
	/**
	 * 游戏任务列表
	 */
	function getGameTaskList($TaskID)
	{
	    return $this->objMasterDAL->getGameTaskList($TaskID);
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
		return $this->objMasterDAL->addGameTable($TableSchemeID, $SchemeName, $TableID, $LockBkID, $GestureID, $RunButtonID, $TableDataID, $ChairID);
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
	    return $this->objMasterDAL->addGameTask($TaskID,$KindID,$RoomType,$GameCount,$AwardMoney);
	}
	/**
	 * 删除桌子类型
	 * @return 0:成功,-1:失败
	 */
	function delGameTable($TableSchemeID)
	{
		return $this->objMasterDAL->delGameTable($TableSchemeID);
	}
	/**
	 * 删除游戏任务
	 * @return 0:成功,-1:失败
	 */
	function delGameTask($TaskID)
	{
	    return $this->objMasterDAL->delGameTask($TaskID);
	}
	/**
	 * 读取系统配置表
	 * @return array
	 */
	function getSysConfigInfo()
	{
		return $this->objMasterDAL->getSysConfigInfo();
	}
	/**
	 * 添加配置表
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addSysConfig($arrParams)
	{
		return $this->objMasterDAL->addSysConfig($arrParams);
	}
	/**
	 * 设置系统配置信息(系统银行更新失败回滚)
	 * @param $Capacity 回滚金额
	 * @param $TypeID 2:金币回滚,1:龙币回滚
	 * @return -3:回滚的金额必须小于现有容量,0:成功,-1:失败
	 */
	function setSysConfigCallbank($Capacity,$TypeID)
	{
		return $this->objMasterDAL->setSysConfigCallbank($Capacity,$TypeID);
	}
	/**
	 * 设置系统配置信息(系统银行扩容)
	 * @param $Capacity 扩容金额
	 * @param $TypeID 2:金币扩容,1:龙币扩容
	 * @return -2:金币/龙币扩容必须大于之前容量,0:成功,-1:失败
	 */
	function setSysConfig($Capacity,$TypeID)
	{
		return $this->objMasterDAL->setSysConfig($Capacity,$TypeID);
	}
	/**
	 * 添加黄钻等级
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addVipLevel($arrParams)
	{
		return $this->objMasterDAL->addVipLevel($arrParams);
	}
	/**
	 * 删除黄钻等级
	 * @param $VipID 
	 * @return 0:成功,-1:失败
	 */
	function delVipLevel($VipID)
	{
		return $this->objMasterDAL->delVipLevel($VipID);
	}
	
	/**
	 * 删除机器人信息
	 * @param $NameID
	 * @return 0:成功,-1:失败
	 */
	function delRobotName($NameID)
	{
	    return $this->objMasterDAL->delRobotName($NameID);
	}
	/**
	 * 整理机器人信息
	 * @return 0:成功,-1:失败
	 */
	function formatRobotName()
	{
	    return $this->objMasterDAL->formatRobotName();
	}
	
	/**
	 * 删除机器人账号
	 * @param $UserID
	 * @return 0:成功,-1:失败
	 */
	function delRobotUser($UserID)
	{
	    return $this->objMasterDAL->delRobotUser($UserID);
	}
	
	/**
	 * 批量删除机器人账号
	 * @param $Type int 删除类型  1.按数量  2.按页码  3按房间号
	 * @param $value int 
	 * @return 0:成功,-1:失败
	 */
	function delAllRobotUser($Type,$Value)
	{
	    return $this->objMasterDAL->delAllRobotUser($Type,$Value);
	}
	/**
	 * 删除机器人账号
	 * @param $RoomID
	 * @return 0:成功,-1:失败
	 */
	function delRoomRobot($RoomID)
	{
	    return $this->objMasterDAL->delRoomRobot($RoomID);
	}
	/**
	 * 读取黄钻等级
	 * @return array
	 */
	function getVipLevel($VipID)
	{
		return $this->objMasterDAL->getVipLevel($VipID);
	}
	/**
	* 读取游戏配置
	* @return array
	*/
	function getGameConfig($TypeID)
	{
	    return $this->objMasterDAL->getGameConfig($TypeID);
	}
	
	/**
	 * 添加游戏配置
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addGameConfig($arrParams)
	{
	    return $this->objMasterDAL->addGameConfig($arrParams);
	}
	/**
	 * 删除游戏配置
	 * @param $TypeID
	 * @return 0:成功,-1:失败
	 */
	function delGameConfig($TypeID)
	{
	    return $this->objMasterDAL->delGameConfig($TypeID);
	}
	/**
	 * 添加角色等级
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addRoleLevel($arrParams)
	{
		return $this->objMasterDAL->addRoleLevel($arrParams);
	}
	/**
	 * 读取角色等级
	 * @return array
	 */
	function getRoleLevel($LvlID)
	{
		return $this->objMasterDAL->getRoleLevel($LvlID);
	}
	/**
	 * 删除角色等级
	 * @param $LvlID 
	 * @return 0:成功,-1:失败
	 */
	function delRoleLevel($LvlID)
	{
		return $this->objMasterDAL->delRoleLevel($LvlID);
	}
	/**
	 * 读取运势级别
	 * @return array
	 */
	function getLuckyList()
	{
		return $this->objMasterDAL->getLuckyList();
	}
	/**
	 * 读取运势级别
	 * @return array
	 */
	function getLuckyAll()
	{
		return $this->objMasterDAL->getLuckyAll();
	}
	/**
	 * 读取运势掉落概率
	 * @return array
	 */
	function getLuckyProbList($LuckyID)
	{
		return $this->objMasterDAL->getLuckyProbList($LuckyID);
	}
	/**
	 * 添加运势级别
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addLucky($arrParams)
	{
		return $this->objMasterDAL->addLucky($arrParams);
	}
	/**
	 * 删除运势级别
	 * @param $LuckyID 
	 * @return 0:成功,-1:失败
	 */
	function delLucky($LuckyID)
	{
		return $this->objMasterDAL->delLucky($LuckyID);
	}
	/**
	 * 添加运势掉落概率
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
	function addLuckyProb($arrParams)
	{
		return $this->objMasterDAL->addLuckyProb($arrParams);
	}
	/**
	 * 删除运势掉落概率
	 * @param $LuckyID 
	 * @return 0:成功,-1:失败
	 */
	function delLuckyProb($ID)
	{
		return $this->objMasterDAL->delLuckyProb($ID);
	}
	/**
	 * 读取敏感词
	 * @param $ID
	 * @return array
	 */
	function getSysConfineNameInfo($ID)
	{
		return $this->objMasterDAL->getSysConfineNameInfo($ID);
	}
	/**
	 * 添加敏感词
	 * @param 
	 * @return 0:成功,-1:失败
	 */
	function addSysConfineName($ID,$LoginName)
	{
		return $this->objMasterDAL->addSysConfineName($ID,$LoginName);
	}
	/**
	 * 删除敏感词
	 * @param 
	 * @return 0:成功,-1:失败
	 */
	function delSysConfineName($ID)
	{
		return $this->objMasterDAL->delSysConfineName($ID);
	}
	/**
	 * 读取外挂
	 * @param $ExtraProgramKey
	 * @return array
	 */
	function getExtraProgram($ExtraProgramKey)
	{
	    return $this->objMasterDAL->getExtraProgram($ExtraProgramKey);
	}
	/**
	 * 添加外挂
	 * @param
	 * @return 0:成功,-1:失败
	 */
	function addExtraProgram($OldExtraProgramKey,$ExtraProgramKey,$ExtraProgramName)
	{
	    return $this->objMasterDAL->addExtraProgram($OldExtraProgramKey,$ExtraProgramKey,$ExtraProgramName);
	}
	/**
	 * 删除外挂
	 * @param
	 * @return 0:成功,-1:失败
	 */
	function delExtraProgram($ExtraProgramKey)
	{
	    return $this->objMasterDAL->delExtraProgram($ExtraProgramKey);
	}
	/**
	 * 根据游戏编号、或者通行证帐号获取RoleID
	 * @param $keyID
	 * @param $type
	 */
	function getRoleIDByKeyID($keyID, $type)
	{
		return $this->objMasterDAL->getRoleIDByKeyID($keyID, $type);
	}
	
	/**
	 * @return 返回积分游戏列表
	 * @author blj
	 */
	public function getGameKind()
	{
		return $this->objMasterDAL->getGameKind();
	}
	/**
	 * 配置比赛规则信息(66人斗地主模式的比赛)
	 * @param $arrParams 参数
	 * @return 0:成功,-1:失败
	 */
	function addGameMatch1($arrParams)
	{	
		return $this->objMasterDAL->addGameMatch1($arrParams);
	}
	/**
	 * 返回比赛规则信息(66人斗地主模式的比赛)
	 * @param $MatchTypeID 赛事ID
	 * @author xlj
	 * @return array
	 */
	public function getGameMatchInfo1($MatchTypeID)
	{
		return $this->objMasterDAL->getGameMatchInfo1($MatchTypeID);
	}
	/**
	 * 返回所有赛事
	 * @author xlj
	 * @return array
	 */
	public function getGameMatchAll()
	{
		return $this->objMasterDAL->getGameMatchAll();
	}
	/**
	 * 删除玩家编号
	 * @param $LoginID
	 * @return 0:成功,-1:失败
	 */
	public function delLoginID($LoginID)
	{
		return $this->objMasterDAL->delLoginID($LoginID);
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
		return $this->objMasterDAL->addLoginID($LoginID,$Sales,$Pattern);
	}
	
	/**
	 * 添加赛事
	 * @param $MatchName
	 * @param $TypeID
	 */
	public function addGameMatch($MatchTypeID,$MatchName,$TypeID,$MID)
	{
		return $this->objMasterDAL->addGameMatch($MatchTypeID,$MatchName,$TypeID,$MID);
	}
	
	/**
	 * 根据MatchTypeID获取比赛信息
	 * @author blj
	 * @param $Key
	 * @param $Type 1:按MatchTypeID搜索,2:按TypeID搜索
	 */
	public function getGameMatchByID($Key,$Type=1)
	{
		return $this->objMasterDAL->getGameMatchByID($Key,$Type);
	}
	
	/**
	 * 删除比赛信息
	 * @author blj
	 * @param  $MatchTypeID
	 */
	public function deleteGameMatch($MatchTypeID)
	{
		return $this->objMasterDAL->deleteGameMatch($MatchTypeID);
	}
	
	/**
	 * 根据比赛类型ID获取比赛奖品列表
	 * @author blj
	 * @param $MatchTypeID
	 */
	public function getGameMatchPrize($MatchTypeID)
	{
		return $this->objMasterDAL->getGameMatchPrize($MatchTypeID);
	}
	
	/**
	 * 根据比赛类型ID获取某等奖信息
	 * @author blj
	 * @param $MatchTypeID
	 * @param $Level
	 */
	public function getMatchPrizeByLevel($MatchTypeID,$Level)
	{
		return $this->objMasterDAL->getMatchPrizeByLevel($MatchTypeID,$Level);
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
	public function addGameMatchPrize($MatchTypeID,$RankStart,$RankEnd,$Level,$SpID,$iNumber,$prizeType,$prizeName='')
	{
		return $this->objMasterDAL->addGameMatchPrize($MatchTypeID,$RankStart,$RankEnd,$Level,$SpID,$iNumber,$prizeType,$prizeName);
	}
	
	/**
	 * 删除某个比赛的奖品信息
	 * @author blj
	 * @param $MatchTypeID
	 * @param $Level
	 */
	public function deleteGameMatchPrize($MatchTypeID,$Level)
	{
		return $this->objMasterDAL->deleteGameMatchPrize($MatchTypeID,$Level);
	}
	/**
	 * 读取比赛列表
	 * @author xlj
	 * @param $TypeID
	 * @param $Type
	 
	public function getGameMatchList($TypeID,$Type)
	{
		return $this->objMasterDAL->getGameMatchList($TypeID,$Type);
	}*/
	/**
	 * 返回奖品发放的名次范围
	 * @author xlj
	 * @param $MatchTypeID
	 * @return Array
	 */
	public function getGameMatchRankArea($MatchTypeID)
	{
		return $this->objMasterDAL->getGameMatchRankArea($MatchTypeID);
	}
	/**
	 * 返回比赛房间信息
	 * @author xlj
	 * @param $RoomID
	 * @return Array
	 */
	public function getGameRoomInfoMatchSetting($RoomID)
	{
		return $this->objMasterDAL->getGameRoomInfoMatchSetting($RoomID);
	}
	/**
	 * 读取部门列表
	 * @return array
	 */
	public function getDepartmentList()
	{		
		return $this->objMasterDAL->getDepartmentList();
	}
    /**
     * 读取角色列表
     * @return array
     */
    public function getRoleList()
    {
        return $this->objMasterDAL->getRoleList();
    }
	/**
	 * 添加管理员
	 * @param $arrParams
	 * @return -1:失败,0:成功
	 */
	public function setSysUser($arrParams)
	{		
		return $this->objMasterDAL->setSysUser($arrParams);
	}
	/**
	 * 重置管理员登陆密码
	 * @param $ID
	 * @return -1:失败,0:成功
	 */
	public function resetSysUserPwd($ID)
	{		
		return $this->objMasterDAL->resetSysUserPwd($ID);
	}
	/**
	 * 删除管理员
	 * @param $ID
	 * @return -1:失败,0:成功
	 */
	public function deleteSysUser($ID)
	{		
		return $this->objMasterDAL->deleteSysUser($ID);
	}
	/**
	 * 修改管理员状态
	 * @param   $ID
	 * 
	 * **/
	public function setSysUserStatus($ID,$Status){
	    return $this->objMasterDAL->setSysUserStatus($ID,$Status);
	}
	
	/**
	 * 修改密码
	 * @param $OldPwd
	 * @param $NewPwd
	 * @return 0:成功,-1:失败,-2:用户不存在,-3:原密码错误
	 */
	public function updateSysUserPwd($OldPwd,$NewPwd)
	{		
		return $this->objMasterDAL->updateSysUserPwd($OldPwd,$NewPwd);
	}
	/**
	 * 验证玩家昵称是否可用
	 * @param $LoginName
	 * @return array
	 */
	public function getSysConfineLoginName($LoginName)
	{
		return $this->objMasterDAL->getSysConfineLoginName($LoginName);
	}
	/** 插入通行证和角色关联表
	 * @param $arrParams
	 * @return -1:失败,0:成功
	 */
	public function addPassportRole($arrParams)
	{
		return $this->objMasterDAL->addPassportRole($arrParams);
	}
	/** 
	 * 玩家编号回滚
	 * @param $LoginID
	 * @return -1:失败,0:成功
	 */
	public function updateConfineLoginID($LoginID)
	{
		return $this->objMasterDAL->updateConfineLoginID($LoginID);
	}	
	/** 
	 * 批量修改服务器ＩＰ
	 * @param 
	 * @return -1:失败,0:成功
	 */
	public function updateServerIP($arrParams)
	{
		return $this->objMasterDAL->updateServerIP($arrParams);
	}
	public function getServerIP($ServerIP)
	{
		return $this->objMasterDAL->getServerIP($ServerIP);
	}
	/**
	 * 添加管理员玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function addUserPrivilege($arrParams)
	{
	    return $this->objMasterDAL->addUserPrivilege($arrParams);
	}
	/**
	 * 删除玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function delUserPrivilege($RoleID)
	{
	    return $this->objMasterDAL->delUserPrivilege($RoleID);
	}
	/**
	 * 查询玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function getUserPrivilege($RoleID)
	{
	    return $this->objMasterDAL->getUserPrivilege($RoleID);
	}
	/**
	 * 设置玩家权限
	 * @author zly
	 * @param $arrParams
	 */
	public function setUserPrivilege($RoleID, $UserRight, $MasterRight,$SystemRight)
	{
	    return $this->objMasterDAL->setUserPrivilege($RoleID, $UserRight, $MasterRight,$SystemRight);
	}
	/**
	 * 添加黑名单
	 * @param $arrParam
	 */
	function setSysBlack($arrParam)
	{
	    return $this->objMasterDAL->setSysBlack($arrParam);
	}
	
	/**
	 * 删除黑名单
	 * @param $Black
	 */
	function deleteSysWarn($WarnStr)
	{
	    return $this->objMasterDAL->deleteSysWarn($WarnStr);
	}
	/**
	 * 添加报警名单
	 * @param $arrParam
	 */
	function setSysWarn($arrParam)
	{
	    return $this->objMasterDAL->setSysWarn($arrParam);
	}
	
	/**
	 * 删除报警名单
	 * @param $Black
	 */
	function deleteSysBlack($LimitStr)
	{
	    return $this->objMasterDAL->deleteSysBlack($LimitStr);
	}
	/**
	 * 读取充值折扣
	 * @return array
	 */
	function getCardChargeRate($CardID)
	{
	    return $this->objMasterDAL->getCardChargeRate($CardID);
	}

    /**读取CardCharge
     *
     */
    function getCardChargeRateList(){
        return $this->objMasterDAL->getCardChargeRate(0);
    }

    /**读取所有北网的cardCharge()
     *
     */
    function getBeiwangCardRateList(){
        $list = $this->getCardChargeRateList();
        $other = array(1,2,33,34,35,36,37,38,39,40,41,42,43,50,51);
        $ret = array();
        foreach($list as $val){
            if(!in_array($val['CardID'],$other)){
                $ret[] = $val;
            }
        }
        return $ret;
    }

	/**
	 * 添加充值折扣
	 * @param $arrParams
	 * @return 0:成功,-1:失败
	 */
 	function addCardChargeRate($arrParams)
	{
	    return $this->objMasterDAL->addCardChargeRate($arrParams);
	}

    function addSuperUser($RoleID,$SuperLevel,$RealName){
        return $this->objMasterDAL->addSuperUser($RoleID,$SuperLevel,$RealName);
    }

    function getSuperUser($RoleID){
        return $this->objMasterDAL->getSuperUser($RoleID);
    }

    /**更新超级用户
     * @param $RoleID
     * @param $SuperLevel
     */
    function updateSuperUser($RoleID,$SuperLevel){
        return $this->objMasterDAL->updateSuperUser($RoleID,$SuperLevel);
    }

    function deleteSuperUser($RoleID){
        return $this->objMasterDAL->deleteSuperUser($RoleID);
    }

    /**
     * 批量生成
     */
    function createRechargeCard($money,$num){
        return $this->objMasterDAL->createRechargeCard($money,$num);
    }

    /**
     *
     */
    function updateRechargeCardState($state,$where,$type){
        return $this->objMasterDAL->updateRechargeCardState($state,$where,$type);
    }

    /**该接口无法删除已充值的数据
     * @param $where
     * @param $type 0 删除所有未充值的数据。
     * @return mixed
     */
    function deleteRechargeCard($where,$type){
        return $this->objMasterDAL->deleteRechargeCard($where,$type);
    }

    /**按state 分组统计数据
     *
     */
    function summaryRechargeCard($StartTime,$EndTime){
        return $this->objMasterDAL->summaryRechargeCard($StartTime,$EndTime);
    }

    /**新增更新文件
     * @param $LowVersion
     * @param $HighVersion
     * @param $FileName
     * @param $FileURL
     * @param $ServerID
     * @return mixed
     */
    function addAndroidVersion($LowVersion,$HighVersion,$FileName,$FileURL,$ServerID,$VerID){
        if($VerID == -1){
            return $this->objMasterDAL->addAndroidVersion($LowVersion,$HighVersion,$FileName,$FileURL,$ServerID);
        }else{
            return $this->objMasterDAL->updateAndroidVersion($VerID,$LowVersion,$HighVersion,$FileName,$FileURL,$ServerID);
        }

    }

    function getAndroidVersion($VerID){
        return $this->objMasterDAL->getAndroidVersion($VerID);
    }
    /***
     * 批量修改房间服务器
     * @param @Where string 条件
     * @param @ServerIP 外网IP
     * @param @ServPort 外网端口
     * 
     * **/
    function editServer($Where,$ServerIP,$IP){
        return $this->objMasterDAL->editServer($Where,$ServerIP,$IP);
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
        return $this->objMasterDAL->addGameDun($OldID,$ID,$GroupName,$GroupURL,$Type,$ParameterType);
    }
    
    /***
     * 添加或修改游戏签到设置信息
     * @param int @KindID  游戏种类
     * @param int @RoomType 房间种类
     * @param int @SignType 签到种类     0 按时间  1 按局数
     * @param int @SignValue  签到要达到的值
     * @param string @SignAward   签到奖励  用,分隔
     * @param int @PhoneExtra 手机端额外奖励
     * */
    function addGameSign($KindID,$RoomType,$SignType,$SignValue,$SignAward,$PhoneExtra){
        return $this->objMasterDAL->addGameSign($KindID,$RoomType,$SignType,$SignValue,$SignAward,$PhoneExtra);
    }
    /**
     * 游戏签到列表
     * @param int @KindID 游戏类型
     * @param int @RoomType 房间类型
     */
    function getGameSignList($KindID,$RoomType)
    {
        return $this->objMasterDAL->getGameSignList($KindID,$RoomType);
    }
    /**
     * 删除游戏签到信息
     * @param int $KindID 游戏类型
     * @param int $RoomType 房间类型
     * @return 0:成功,-1:失败
     */
    function delGameSign($KindID,$RoomType)
    {
        return $this->objMasterDAL->delGameSign($KindID,$RoomType);
    }
    /**
     * 删除游戏盾记录
     * @param int $ID 
     * @return 0:成功，-1：失败
     */
    function delGameDun($ID)
    {
        return $this->objMasterDAL->delGameDun($ID);
    }
    /**
     * 获取游戏盾信息
     * @param int $ID
     */
    function getGameDunInfo($ID)
    {
        return $this->objMasterDAL->getGameDunInfo($ID);
    }
    /**
     * 获取游戏盾列表
     */
    function selectGameDunList()
    {
        return $this->objMasterDAL->selectGameDunList();
    }
    /***
     * 获取号码归属地
     * @param string $tel 手机号
     * */
    public function getTelSegment($Tel){
        return $this->objMasterDAL->getTelSegment($Tel);
    }
    /***
     * 获取身份证归属地
     * @param string $tel 手机号
     * */
    public function getCardSegment($CardNo){
        return $this->objMasterDAL->getCardSegment($CardNo);
    }
    /***
     * 获取充值方式
     * @param string $CardType 卡号
     * */
    public function getCardNameByCardType($CardType){
    	return $this->objMasterDAL->getCardNameByCardType($CardType);
    }	/**
	 * 防攻击IP验证
	 * @IP
	 */
	public function CheckLoginIp($IP,$UserName,$SiteID,$LimitSeconds,$VisitCount)
	{
	    return $this->objMasterDAL->CheckLoginIp($IP,$UserName,$SiteID,$LimitSeconds,$VisitCount);
	}
	/**
	 * 获取游戏服务器记录条数
	 */
	public function getServerGameCount()
	{
	    return $this->objMasterDAL->getServerGameCount();
	}
	/**
	 * 获取游戏服务器分页
	 * @CurPage
	 * @PageSize 
	 */
	public function getServerGame($CurPage,$PageSize)
	{
	    return $this->objMasterDAL->getServerGame($CurPage,$PageSize);
	}
	/**
	 * 获取游戏服务器端口
	 * @ServerType 
	 * @LANServerIP
	 */
	public function getServerPort($ServerType,$LANServerIP)
	{
	    return $this->objMasterDAL->getServerPort($ServerType,$LANServerIP);
	}
	
	/*
	 * 获取游戏汇总数据
	 */
	public function getGameDataCount()
	{
	    return $this->objMasterDAL->getGameDataCount();
	}

	///
    function getWechatId($TypeID)
    {
        return $this->objMasterDAL->getWechatId($TypeID);
    }



    function delWechatUser($wechatID)
    {
        return $this->objMasterDAL->delWechatUser($wechatID);
    }


    function addWechatUser($arrParams){
        return $this->objMasterDAL->addWechatUser($arrParams);
    }

    function topWechatUser($arrParams){
        return $this->objMasterDAL->topWechatId($arrParams);
    }


    function  setColorTop($roleid,$color,$top){
	    return $this->objMasterDAL->setColorTop($roleid,$color,$top);
    }


    function getColorTop($roleid){
	    return $this->objMasterDAL->getColorTop($roleid);
    }


    function getReviewNum(){
	    return $this->objMasterDAL->getReviewNum();
    }

    function getMsgInfo($MsgId, $ClassId)
    {
        return $this->objMasterDAL->getMsgInfo($MsgId, $ClassId);
    }

    function getMsgInfoFront($ClassId) {
        return $this->objMasterDAL->getMsgInfoFront($ClassId);
    }


    function addMsgInfo($msg)
    {
        //$msgtitle,$msgcontent,$sortid,$starttime,$endtime
        return $this->objMasterDAL->addMsgInfo($msg);
    }

    function delMsgInfo($MsgID)
    {
        //$msgtitle,$msgcontent,$sortid,$starttime,$endtime
        return $this->objMasterDAL->delMsgInfo($MsgID);
    }


    function setMsgLocked($MsgID)
    {
        //$msgtitle,$msgcontent,$sortid,$starttime,$endtime
        return $this->objMasterDAL->setMsgLocked($MsgID);
    }


    function addOnlineDes($RoleId,$descript)
    {
        //$msgtitle,$msgcontent,$sortid,$starttime,$endtime
        return $this->objMasterDAL->addOnlineDes($RoleId,$descript);
    }

    //设置支付类别信息
    function setPayclass($classId, $className, $bank, $cardNo, $cardName, $descript = '') {
        return $this->objMasterDAL->setPayclass($classId, $className, $bank, $cardNo, $cardName, $descript);
    }

    //新增支付通道
    function addPaychannel($channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status) {
	    return $this->objMasterDAL->addPaychannel($channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status);
    }

    //编辑支付通道
    function editPaychannel($channelid, $channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status) {
        return $this->objMasterDAL->editPaychannel($channelid,$channelname,$mchid, $appid, $appkey, $config, $noticeurl, $descript, $status);
    }

    //删除支付通道
    function deletePaychannel($channelid) {
	    return $this->objMasterDAL->deletePaychannel($channelid);
    }

    //修改支付通道状态
    function changePaychannel($channelid,$status) {
        return $this->objMasterDAL->changePaychannel($channelid, $status);
    }

    //新增金额
    function addPayamount($amount) {
        return $this->objMasterDAL->addPayamount($amount);
    }

    function editPayamount($amountid, $amount) {
        return $this->objMasterDAL->editPayamount($amountid, $amount);
    }

    //删除金额
    function deletePayamount($amountid) {
        return $this->objMasterDAL->deletePayamount($amountid);
    }

    //编辑通道金额
    function editPayrelation($id, $classid, $amountid, $channelid){
        return $this->objMasterDAL->editPayrelation($id, $classid, $amountid, $channelid);
    }
    //新增通道金额
    function addPayrelation($classid, $amountid, $channelid) {
        return $this->objMasterDAL->addPayrelation($classid, $amountid, $channelid);
    }

    function deletePayrelation($id) {
        return $this->objMasterDAL->deletePayrelation($id);
    }

    //首页数据
    function getHomedata() {
	    return $this->objMasterDAL->getHomedata();
    }
    //折线图
    function getRegData() {
        return $this->objMasterDAL->getRegdata();
    }

    function getUserGameRank($roleId) {
        return $this->objMasterDAL->getUserGameRank($roleId);
    }

    //更新游戏任务
    function updateGameTask($roomId, $reqRound, $award, $taskname) {
        return $this->objMasterDAL->updateGameTask($roomId, $reqRound, $award, $taskname);
    }
    //上下架游戏任务
    function setGameTaskStatus($roomId, $status) {
        return $this->objMasterDAL->setGameTaskStatus($roomId, $status);
    }

    //新增角色
    function addRole($roleName, $descript) {
        return $this->objMasterDAL->addRole($roleName, $descript);
    }
    //修改角色
    function editRole($roleId, $roleName, $descript) {
        return $this->objMasterDAL->editRole($roleId, $roleName, $descript);
    }
    //删除角色
    function deleteRole($roleId) {
        return $this->objMasterDAL->deleteRole($roleId);
    }
    //更新菜单显示
    function showMenu($id, $status) {
        return $this->objMasterDAL->showMenu($id, $status);
    }
    //更新菜单排序
    function doOrder($id, $order) {
        return $this->objMasterDAL->doOrder($id, $order);
    }
    //新增子菜单
    function addSubMenu($pid, $name, $controller, $order) {
        return $this->objMasterDAL->addSubMenu($pid, $name, $controller, $order);
    }
    //新增菜单目录
    function addSubMenu2($name, $group, $groupName, $order) {
        return $this->objMasterDAL->addSubMenu2($name, $group, $groupName, $order);
    }
    //删除菜单
    function deleteMenu($menuId) {
        return $this->objMasterDAL->deleteMenu($menuId);
    }
    //修改菜单目录
    function editMenu1($menuId, $menuname) {
        return $this->objMasterDAL->editMenu1($menuId, $menuname);
    }
    //修改菜单
    function editMenu2($menuId,$menuname, $controller) {
        return $this->objMasterDAL->editMenu2($menuId,$menuname, $controller);
    }
    //更新玩家菜单
    function setRoleMenu($roleid, $menu) {
        return $this->objMasterDAL->setRoleMenu($roleid, $menu);
    }
    //根据id获取菜单
    function getMenuById($roleid) {
        return $this->objMasterDAL->getMenuById($roleid);
    }
    //修改账号角色
    function setUserRole($adminId, $roleId) {
        return $this->objMasterDAL->setUserRole($adminId, $roleId);
    }
}
?>