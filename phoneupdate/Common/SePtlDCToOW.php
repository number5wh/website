<?php
// DC TO OW

//获取登录服务器列表
//UINT32		iCount;				//数量
//SeGameServerInfoCacheToOW akServerInfo[1];
//UINT32 iServerID;
//UINT32 iServerType;
//UINT32 iServID;
//UINT32 bLocked;
//char szServerName[20];
//char szServerIP[256];
//char szLANServerIP[32];
//char szServerPort[16];
//char szIntro[256];
//char szLogin[32];
//char szPass[64];
//char szAppName[32];
//char szIP[128];
function ProcessDWGetServerListRes($out_data)
{
    $out_data_array = unpack('LiCount/', $out_data);
    for ($x=0; $x<$out_data_array['iCount']; $x++)
    {
        $out_data_server_info_array = unpack('x4/x'.($x*852).'/LiServerID/LiServerType/LiServID/LbLocked/a20szServerName/a256szServerIP/a32szLANServerIP/a16szServerPort/a256szIntro/a32szLogin/a64szPass/a32szAppName/a128szIP/', $out_data);
		fitStr($out_data_server_info_array['szServerName']);
		fitStr($out_data_server_info_array['szServerIP']);
		fitStr($out_data_server_info_array['szLANServerIP']);
		fitStr($out_data_server_info_array['szServerPort']);
		fitStr($out_data_server_info_array['szIntro']);
		fitStr($out_data_server_info_array['szLogin']);
		fitStr($out_data_server_info_array['szPass']);
		fitStr($out_data_server_info_array['szAppName']);
		fitStr($out_data_server_info_array['szIP']);
		
		$out_data_array["ServerInfoList"][$x] = $out_data_server_info_array;
    }
    $out_array = $out_data_array;
	return $out_array;
}


//获取游戏版本信息
//UINT32		iCount;				//数量
//SeGameVersionCacheToOW akGameVersion[1];
//UINT32 iVerID;
//UINT32 iVerType;
//UINT32 iKindID;
//UINT32 iServerID;
//UINT32 iFileCategory;
//UINT32 iVersion;
//UINT32 iLastUpdateTime;
//char szFileName[32];
//char szFileURL[50];
//char szLocalPath[50];
function ProcessDWGetGameVersionRes($out_data)
{
    $out_data_array = unpack('LiCount/', $out_data);

    for ($x=0; $x<$out_data_array['iCount']; $x++)
    {
        
		$out_data_game_version_info_array = unpack('x4/x'.($x*160).'/LiVerID/LiVerType/LiKindID/LiServerID/LiFileCategory/LiVersion/LiLastUpdateTime/a32szFileName/a50szFileURL/a50szLocalPath/', $out_data);
		fitstr($out_data_game_version_info_array['szFileName']);
		fitstr($out_data_game_version_info_array['szFileURL']);
		fitstr($out_data_game_version_info_array['szLocalPath']);
		$out_data_game_version_info_array['iLastUpdateTime'] = date("Y-m-d H:i:s",$out_data_game_version_info_array['iLastUpdateTime']);
        $out_data_array["GameVersionList"][$x] = $out_data_game_version_info_array;
    }
    
    $out_array = $out_data_array;

	return $out_array;
}


//获取安卓版本信息
//UINT32		iCount;				//数量
//SeAndroidVersionCacheToOW akAndroidVersion[1];
//UINT32 iLowVersion;
//UINT32 iHighVersion;
//UINT32 iLastUpdateTime;
//UINT32 iServerID;
//UINT32 iVerID;
//char szFileName[256];
//char szFileURL[256];
function ProcessDWGetAndroidVersionRes($out_data)
{
    $out_data_array = unpack('LiCount/', $out_data);

    for ($x=0; $x<$out_data_array['iCount']; $x++)
    {
        $out_data_android_version_info_array = unpack('x4/x'.($x*532).'/LiLowVersion/LiHighVersion/LiLastUpdateTime/LiServerID/LiVerID/a256szFileName/a256szFileURL/', $out_data);
        fitstr($out_data_android_version_info_array['szFileName']);
        fitstr($out_data_android_version_info_array['szFileURL']);
        $out_data_android_version_info_array['iLastUpdateTime'] = date("Y-m-d H:i:s",$out_data_android_version_info_array['iLastUpdateTime']);
        $out_data_array["AndroidVersionList"][$x] = $out_data_android_version_info_array;
    }

    $out_array = $out_data_array;

    return $out_array;
}
//玩家操作返回
//UINT32	iPcSwitch;		电脑端开关
//UINT32	iAppSwitch;		手机端开关
function ProcessDWGetYouXiDunInfoRes($out_data)
{
    //echo "ProcessDWGetYouXiDunInfoRes: <br />";
    $out_data_array = unpack('LiPcSwitch/LiAppSwitch/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    $out_array = $out_data_array;

    return $out_array;
}


function ProcessDWOperateAckRes($out_data)
{
    //echo "ProcessDWOperateAckRes: <br />";
    $out_data_array = unpack('LiResult/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    $out_array = $out_data_array;

    return $out_array;
}

//获取玩家信息返回
//char szLoginName[64]; 			// 角色名string 'cp8' (length=3)
//UINT32 iLoginID; 				//帐号ID int 609372
//char szMobilePhone[12]; 		//手机号码 string '0' (length=1)
//char IdCard[24]; 				//身份证 string '' (length=0)
//char QQ[12]; 					//QQ号 string '' (length=0)
//UINT32 iMoorMachine; 			//是否主机绑定 int 0
//char szMachineSerial[33];		//绑定机器码 string 'c7be5217d0bce458cb1cb026c7353dec' (length=32)
//UINT32 iLockStartTime; 			//开始时间 string '2015-08-03 11:01:48' (length=19)
//UINT32 iTitleID; 				//是否设置IP段锁定 int 1
//UINT32 iLockEndTime; 			//结束时间 string '2018-08-02 11:01:48' (length=19)
//UINT32 iLocked; 				//是否锁定 int 1
//UINT32 iLoginCount; 			//登录次数 int 31
//char szLastLoginIP[17]; 		//最后登录IP string '192.168.1.213' (length=13)
//UINT32 iLastLoginTime; 			//最后登录时间 string '2014-06-25 16:45:11' (length=19)
//char szRegIP[17]; 				//注册IP string '192.168.1.6' (length=11)
//UINT32 iAddTime; 				//注册时间 string '2013-10-16' (length=10)
//UINT32 iBlockStartTime;			//封号开始时间
//UINT32 iBlockEndTime;			//封号结束时间
//UINT32 iBlocked;				//是否封号
//char szPlayerName[10];			//真实姓名
//char szWeChat[50];				//微信ID oSBgrwHT-fVpLczITBPo3mAYCgb8
function ProcessAMGetRoleBaseInfoRes($out_data)
{
    //echo "ProcessAMGetRoleBaseInfoRes: <br />";
    $out_data_array = unpack('a64szLoginName/LiLoginID/a12szMobilePhone/a24IdCard/a12szQQ/LiMoorMachine/a33szMachineSerial/LiLockStartTime/LiTitleID/LiLockEndTime/LiLocked/LiLoginCount/a17szLastLoginIP/LiLastLoginTime/a17szRegIP/LiAddTime/LiBlockStartTime/LiBlockEndTime/LiBlocked/a10szPlayerName/a50szWeChat/', $out_data);
    //print_r($out_data_array);
    //print_r($out_data_array);
    //echo "<br />";
    fitStr($out_data_array['szLoginName']);
    fitStr($out_data_array['szMobilePhone']);
    fitStr($out_data_array['IdCard']);
    fitStr($out_data_array['szQQ']);
    fitStr($out_data_array['szMachineSerial']);
    fitStr($out_data_array['szLastLoginIP']);
    fitStr($out_data_array['szRegIP']);
    fitStr($out_data_array['szPlayerName']);
    fitStr($out_data_array['szWeChat']);
    $out_array = $out_data_array;

    return $out_array;
}

//获取玩家信息返回
//UINT32 iRoleID; 				//角色ID string '67' (length=2)
//char szRealName[64]; 			//角色名 string 'abc' (length=3)
//UINT32 iGender; 				//性别 0男1女 int 1
//UINT32 iVipID; 					//会员等级 int 0
//char szSignature[128]; 			//个性签名 string '' (length=0)
//UINT32 iVipExpireTime; 			//会员到期时间 string '2013-10-16 10:49:55' (length=19)
//UINT32 iVipOpeningTime; 		//会员开始时间 string '2013-10-16 10:49:55' (length=19)
//UINT32 iRoomName; 				// 当前登录状态 string '' (length=0)]
//UINT32 iGameLock; 				// 财富锁定状态
//UINT32 iClientType; 			// 客户端类型 0:pc 1:android 2:ios 3:wp
function ProcessDMGetRoleBaseInfoRes($out_data)
{
    //echo "ProcessDMGetRoleBaseInfoRes: <br />";
    $out_data_array = unpack('LiRoleID/a64szRealName/LiGender/LiVipID/a128szSignature/LiVipExpireTime/LiVipOpeningTime/LiRoomID/LiGameLock/LiClientType/LiLoginBindWeChat/LiBankBindWeChat/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    fitStr($out_data_array['szRealName']);
    fitStr($out_data_array['szSignature']);
    $out_array = $out_data_array;

    return $out_array;
}

//查询玩家银行数据返回
//INT64 iGameWealth; 				//游戏财富['TotalMoney'],
//INT64 iMoney; 						//银行欢乐豆 string '1422' (length=4)
//UINT32 iFreeze; 					//状态是否冻结 int 1
//UINT32 iAddTime; 					//开户时间 string '2015-08-05 20:45:15' (length=19)
//UINT32 iFirstRechargeTime; 			//首次充值 string '2014-02-19 10:30:15' (length=19)
//UINT32 iTotalTimes; 				//登录次数 int 1
//UINT32 iSuperPlayerLevel;			//超级会员等级
//UINT32 iChargeCount;				//充值次数
//INT64 iTotalChargeMoney;			//充值总数
//INT64 iTotalLockMoney;			//冻结总数
//UINT32 iBankDealBackCanGetCount;  //能量瓶数量
//INT64 iBankDealBackMoney;         //能量瓶余额
//INT64 iBankTotalGetBackMoney;     //领取过的能量瓶总数
function ProcessDMQueryRoleBankInfoRes($out_data)
{
    //echo "ProcessDMQueryRoleBankInfoRes: <br />";
    $out_data_array = unpack('LiGameWealthL32/LiGameWealthH32/LiMoneyL32/LiMoneyH32/LiFreeze/LiAddTime/LiFirstRechargeTime/LiTotalTimes/LiSuperPlayerLevel/LiChargeCount/LiTotalChargeMoneyL32/LiTotalChargeMoneyH32/LiTotalLockMoneyL32/LiTotalLockMoneyH32/LiBankDealBackCanGetCount/LiBankDealBackMoneyL32/LiBankDealBackMoneyH32/LiBankTotalGetBackMoneyL32/LiBankTotalGetBackMoneyH32/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";
    $out_data_array['iGameWealth'] = MakeINT64Value($out_data_array['iGameWealthH32'] , $out_data_array['iGameWealthL32']);
    $out_data_array['iMoney'] = MakeINT64Value($out_data_array['iMoneyH32'], $out_data_array['iMoneyL32']);
    $out_data_array['iTotalChargeMoney'] = MakeINT64Value($out_data_array['iTotalChargeMoneyH32'] , $out_data_array['iTotalChargeMoneyL32']);
    $out_data_array['iTotalLockMoney'] = MakeINT64Value($out_data_array['iTotalLockMoneyH32'], $out_data_array['iTotalLockMoneyL32']);
    $out_data_array['iBankDealBackMoney'] = MakeINT64Value($out_data_array['iBankDealBackMoneyH32'], $out_data_array['iBankDealBackMoneyL32']);
    $out_data_array['iBankTotalGetBackMoney'] = MakeINT64Value($out_data_array['iBankTotalGetBackMoneyH32'], $out_data_array['iBankTotalGetBackMoneyL32']);
    $out_array = $out_data_array;

    return $out_array;
}

