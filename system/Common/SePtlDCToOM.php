<?php
// DC TO OM

//查询玩家列表
//UINT32		iRoleCount;				//用户数量
//SeRoleInfoDCToOM	akRoleInfoToOM[1];	//用户信息
//UINT32 iRoleID;					//角色ID
//char szLoginName[64];			//角色名
//char szSignature[128];			//个性签名
//UINT32 iGender;					//性别 0男1女
//UINT32 iVipID;					//会员等级
//UINT32 iVipExpireTime;			//会员到期时间
//UINT32 iVipOpeningTime;			//会员开始时间
//UINT64 iHappyBeanMoney;			//金币总额
function ProcessDMQueryRoleListRes($out_data) {
	//echo "ProcessDMQueryRoleListRes: <br />";
	$out_data_array = unpack('LiRoleCount/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	for ($x = 0; $x < $out_data_array['iRoleCount']; $x++) {
		//echo "RoleInfo".($x + 1).":<br />";
		$out_data_Role_array = unpack('x4/x' . ($x * 220) . '/LiRoleID/a64szLoginName/a128szSignature/LiGender/LiVipID/LiVipExpireTime/LiVipOpeningTime/LiHappyBeanMoneyL32/LiHappyBeanMoneyH32/', $out_data);
		$out_data_Role_array['iHappyBeanMoney'] = MakeINT64Value($out_data_Role_array['iHappyBeanMoneyH32'], $out_data_Role_array['iHappyBeanMoneyL32']);
		$date1 = date('Y-m-d H:i:s', $out_data_Role_array['iVipExpireTime'] + 8 * 3600);
		//echo "会员到期：" . $date1 . "<br />";
		//print_r($out_data_Role_array);
		//echo "<br />";
		fitStr($out_data_Role_array['szLoginName']);
		fitStr($out_data_Role_array['szSignature']);
		$out_array["RoleInfoList"][$x] = $out_data_Role_array;
	}

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
function ProcessDMGetRoleBaseInfoRes($out_data) {
	//echo "ProcessDMGetRoleBaseInfoRes: <br />";
	$out_data_array = unpack('LiRoleID/a64szRealName/LiGender/LiVipID/a128szSignature/LiVipExpireTime/LiVipOpeningTime/LiRoomID/LiGameLock/LiClientType/LiLoginBindWeChat/LiBankBindWeChat/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	fitStr($out_data_array['szRealName']);
	fitStr($out_data_array['szSignature']);
	$out_array = $out_data_array;

	return $out_array;
}

//查询玩家房间数据返回
//UINT32		iCurPage;				//当前页码
//UINT32		iTotalPage;				//总页数
//UINT32		iGameCount;				//游戏数量
//SeRoleGameInfoToOM	kRoleGameInfoToOM[1];	//游戏信息
//UINT32 iKindID; 				//游戏类型 int 1000
//UINT32 iRoleID; 				//角色ID int 67
//UINT32 iRoomType; 				//房间类型 int 1
//UINT32 iWinCount; 				//胜利次数 int 3
//UINT32 iLostCount; 				//失败次数 int 5
//UINT32 iDrawCount; 				// 平局int 0
//UINT32 iFleeCount; 				//逃跑次数 int 0
//INT64 iTotalMoney; 			// 汇总金币string '-4430000' (length=8)
//INT64 iTotalScore; 			// 汇总积分int 0
//INT64 iScore; 					// 积分int 0
//INT64 iMoney; 					// 金币string '8650000' (length=7)
//UINT32 iLastSignTime          //上次签到时间
//UINT32 iContinuousSign          //连签次数
//UINT32 iPlayTimeLastDay          //昨天游戏时间
//UINT32 iPlayTimeDay          //今天游戏时间
//UINT32 iPlayCountLastDay          //昨天游戏次数
//UINT32 iPlayCountDay          //今天游戏次数
function ProcessDMQueryRoleGameInfoRes($out_data) {
	//echo "ProcessDMQueryRoleGameInfoRes: <br />";
	$out_data_array = unpack('LiCurPage/LiTotalPage/LiGameCount/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;
	for ($x = 0; $x < $out_data_array['iGameCount']; $x++) {
		//echo "RoomInfo".($x + 1).":<br />";
		$out_data_Role_array = unpack('x12/x' . ($x * 84) . '/LiKindID/LiRoleID/LiRoomType/LiWinCount/LiLostCount/LiDrawCount/LiFleeCount/LiTotalMoneyL32/LiTotalMoneyH32/LiTotalScoreL32/LiTotalScoreH32/LiScoreL32/LiScoreH32/LiMoneyL32/LiMoneyH32/LiLastSignTime/LiContinuousSign/LiPlayTimeLastDay/LiPlayTimeDay/LiPlayCountLastDay/LiPlayCountDay', $out_data);

		$out_data_Role_array['iTotalMoney'] = MakeINT64Value($out_data_Role_array['iTotalMoneyH32'], $out_data_Role_array['iTotalMoneyL32']);
		$out_data_Role_array['iTotalScore'] = MakeINT64Value($out_data_Role_array['iTotalScoreH32'], $out_data_Role_array['iTotalScoreL32']);
		$out_data_Role_array['iScore'] = MakeINT64Value($out_data_Role_array['iScoreH32'], $out_data_Role_array['iScoreL32']);
		$out_data_Role_array['iMoney'] = MakeINT64Value($out_data_Role_array['iMoneyH32'], $out_data_Role_array['iMoneyL32']);
		//print_r($out_data_Role_array);
		//echo "<br />";

		$out_array["RoomInfoList"][$x] = $out_data_Role_array;
	}

	return $out_array;
}

//查询玩家银行数据返回
//INT64 iGameWealth; 				//游戏财富['TotalMoney'],
//INT64 iMoney; 						//银行金币 string '1422' (length=4)
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
function ProcessDMQueryRoleBankInfoRes($out_data) {
	//echo "ProcessDMQueryRoleBankInfoRes: <br />";
	$out_data_array = unpack('LiGameWealthL32/LiGameWealthH32/LiMoneyL32/LiMoneyH32/LiFreeze/LiAddTime/LiFirstRechargeTime/LiTotalTimes/LiSuperPlayerLevel/LiChargeCount/LiTotalChargeMoneyL32/LiTotalChargeMoneyH32/LiTotalLockMoneyL32/LiTotalLockMoneyH32/LiBankDealBackCanGetCount/LiBankDealBackMoneyL32/LiBankDealBackMoneyH32/LiBankTotalGetBackMoneyL32/LiBankTotalGetBackMoneyH32/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";
	$out_data_array['iGameWealth'] = MakeINT64Value($out_data_array['iGameWealthH32'], $out_data_array['iGameWealthL32']);
	$out_data_array['iMoney'] = MakeINT64Value($out_data_array['iMoneyH32'], $out_data_array['iMoneyL32']);
	$out_data_array['iTotalChargeMoney'] = MakeINT64Value($out_data_array['iTotalChargeMoneyH32'], $out_data_array['iTotalChargeMoneyL32']);
	$out_data_array['iTotalLockMoney'] = MakeINT64Value($out_data_array['iTotalLockMoneyH32'], $out_data_array['iTotalLockMoneyL32']);
	$out_data_array['iBankDealBackMoney'] = MakeINT64Value($out_data_array['iBankDealBackMoneyH32'], $out_data_array['iBankDealBackMoneyL32']);
	$out_data_array['iBankTotalGetBackMoney'] = MakeINT64Value($out_data_array['iBankTotalGetBackMoneyH32'], $out_data_array['iBankTotalGetBackMoneyL32']);
	$out_array = $out_data_array;

	return $out_array;
}

//玩家操作返回
//重置银行密码，返回金币,补发金币，补发积分，补发黄钻，冻结财富，给玩家存款
//UINT32 iResult; 					//操作结果，0成功，1失败
function ProcessDMRoleOperateAckRes($out_data) {
	//echo "ProcessDMRoleOperateAckRes: <br />";
	$out_data_array = unpack('LiResult/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	return $out_array;
}

//查询在线玩家返回
//UINT32 iTotalCount; 				//总在线人数

function ProcessDMQueryOnlinePlayerRes($out_data) {
	//echo "ProcessDMQueryOnlinePlayerRes: <br />";
	$out_data_array = unpack('LiTotalCount/LiRoomCount/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	for ($x = 0; $x < $out_data_array['iRoomCount']; $x++) {
		//echo "RoomOnlineInfo".($x + 1).":<br />";
		$out_data_Room_array = unpack('x8/x' . ($x * 28) . '/LiRoomID/LiOnLineCount/LiRobotCount/LiUpdateTime/LiMobileCount/LiIOSCount/LiAndroidCount/', $out_data);
		//print_r($out_data_Room_array);

		$out_array["RoomOnlineInfoList"][$x] = $out_data_Room_array;
	}
	return $out_array;
}


///处理返回在线玩家
function ProcessDMQueryAllOnlinePlayerRes($out_data) {

    //print_r($out_data);
    //echo "ProcessDMQueryOnlinePlayerRes: <br />";
    $out_data_array = unpack('LiTotalCount/', $out_data);
    $out_array = $out_data_array;
    for ($x = 0; $x < $out_data_array['iTotalCount']; $x++) {
        $out_data_Role_array = unpack('x4/x' . ($x * 110) . '/LiUserId/a33szAccount/LiRoomId/LiKindId/a33szRoomName/LiGameMoney/LiBankMoney/LiTotalDespoit/LiTotalTransOut/LnRatio/LnControlTimeLong/LnControlTimeInterval/LnClientType', $out_data);
        $out_array["onlinelist"][$x] = $out_data_Role_array;
    }

    return $out_array;
}




//查询房间机器人数据返回
//UINT32		iCurPage;				//当前页码
//UINT32		iTotalPage;				//总页数
//UINT32		iRoomCount;				//房间数量
//SeRoomOnlineCountToOM	kRoomOnlineCountToOM[1];	//机器人信息
//UINT32								iRoomID;						//房间号码
//UINT32								iOnLineCount;					//在线人数
//UINT32								iRobotCount;					//机器人在线人数。
//INT64									iRobotWinMoney;					//机器人赢的钱
//UINT32								iUpdateTime;
function ProcessDMQueryRoomRobotInfoRes($out_data) {
	//echo "ProcessDMQueryRoomRobotInfoRes: <br />";
	$out_data_array = unpack('LiCurPage/LiTotalPage/LiRoomCount/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	for ($x = 0; $x < $out_data_array['iRoomCount']; $x++) {
		//echo "RoomRobotInfo".($x + 1).":<br />";
		$out_data_Role_array = unpack('x12/x' . ($x * 24) . '/LiRoomID/LiOnLineCount/LiRobotCount/LiRobotWinMoneyL32/LiRobotWinMoneyH32/LiUpdateTime/', $out_data);

		$out_data_Role_array['iRobotWinMoney'] = MakeINT64Value($out_data_Role_array['iRobotWinMoneyH32'], $out_data_Role_array['iRobotWinMoneyL32']);

		//print_r($out_data_Role_array);
		//echo "<br />";

		$out_array["RoomRobotInfoList"][$x] = $out_data_Role_array;
	}

	return $out_array;
}

//查询玩家总财富数据返回
//INT64 iRoleBankTotalMoney;			//玩家银行总财富
//INT64 iRoleGameTotalMoney;			//玩家游戏总财富
function ProcessDMQueryRoleTotalMoneyRes($out_data) {
	//echo "ProcessDMQueryRoleTotalMoneyRes: <br />";
	$out_data_array = unpack('LiRoleBankTotalMoneyL32/LiRoleBankTotalMoneyH32/LiRoleGameTotalMoneyL32/LiRoleGameTotalMoneyH32/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";
	$out_data_array['iRoleBankTotalMoney'] = MakeINT64Value($out_data_array['iRoleBankTotalMoneyH32'], $out_data_array['iRoleBankTotalMoneyL32']);
	$out_data_array['iRoleGameTotalMoney'] = MakeINT64Value($out_data_array['iRoleGameTotalMoneyH32'], $out_data_array['iRoleGameTotalMoneyL32']);
	$out_array = $out_data_array;

	return $out_array;
}
//系统银行操作返回
//UINT32 iResult; 					//操作结果，0成功，1失败
//INT64 iBalance;						//当前余额
//INT64 iLastBalance;					//上次余额
function ProcessDMSysBankOperateAckRes($out_data) {
	//echo "ProcessDMQueryRoleTotalMoneyRes: <br />";
	$out_data_array = unpack('LiResult/LiBalanceL32/LiBalanceH32/LiLastBalanceL32/LiLastBalanceH32/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_data_array['iBalance'] = MakeINT64Value($out_data_array['iBalanceH32'], $out_data_array['iBalanceL32']);
	$out_data_array['iLastBalance'] = MakeINT64Value($out_data_array['iLastBalanceH32'], $out_data_array['iLastBalanceL32']);

	$out_array = $out_data_array;

	return $out_array;
}

//系统银行转账返回
//UINT32 iResult; 					//操作结果，0成功，1失败
//INT64 iBalance;						//当前余额
//INT64 iLastBalance;					//上次余额
//INT64 iToBalance;						//目标银行当前余额
//INT64 iToLastBalance;					//目标银行上次余额
function ProcessDMSysBankDealAckRes($out_data) {
	//echo "ProcessDMQueryRoleTotalMoneyRes: <br />";
	$out_data_array = unpack('LiResult/LiBalanceL32/LiBalanceH32/LiLastBalanceL32/LiLastBalanceH32/LiToBalanceL32/LiToBalanceH32/LiToLastBalanceL32/LiToLastBalanceH32/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_data_array['iBalance'] = MakeINT64Value($out_data_array['iBalanceH32'], $out_data_array['iBalanceL32']);
	$out_data_array['iLastBalance'] = MakeINT64Value($out_data_array['iLastBalanceH32'], $out_data_array['iLastBalanceL32']);
	$out_data_array['iToBalance'] = MakeINT64Value($out_data_array['iToBalanceH32'], $out_data_array['iToBalanceL32']);
	$out_data_array['iToLastBalance'] = MakeINT64Value($out_data_array['iToLastBalanceH32'], $out_data_array['iToLastBalanceL32']);

	$out_array = $out_data_array;

	return $out_array;
}

//角色权限返回
//UINT32		iResult; 				//操作结果，0成功，1失败
//UINT32		iRoleID;				//角色ID
//UINT32		iUserRight;				//用户权限
//UINT32		iMasterRight;			//管理权限
function ProcessDMRoleRightAckRes($out_data) {
	//echo "ProcessDMRoleRightAckRes: <br />";
	$out_data_array = unpack('LiResult/LiRoleID/LiUserRight/LiMasterRight/LiSystemRight/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	return $out_array;
}

//查询房间在线玩家列表
//UINT32		iCurPage;				//当前页码
//UINT32		iTotalPage;				//总页数
//UINT32		iRoleCount;				//玩家数量
//SeRoomRoleInfo	kRoomRoleInfos[1];	//用户信息
// UINT32								iUserID;						//玩家id 4
// char								szUsername[17];					//玩家昵称 17
// char								szIP[17];							//登录ip 17
// INT64								iCurGold;						//在该游戏中的当前金币 8
// INT64								iCurScore;						//在该游戏中的当前成绩 8
// INT64								iTotalGold;						//在该游戏中的总金币 8
// INT64								iTotalScore;						//在该游戏中的总成绩 8
//char                              szMachineSerial[33]                                 //机器码 33
// INT64                                iBankGold                          //银行金币 8
function ProcessDMQueryRoomOnlinePlayersRes($out_data) {
	//echo "ProcessDMQueryRoleListRes: <br />";
	$out_data_array = unpack('LiCurPage/LiTotalPage/LiRoleCount/', $out_data);
	//echo "<br />";

	$out_array = $out_data_array;

	for ($x = 0; $x < $out_data_array['iRoleCount']; $x++) {
		//echo "RoleInfo".($x + 1).":<br />";
		$out_data_Role_array = unpack('x12/x' . ($x * 164) . '/LiUserID/a17szUsername/a17szIP/LiCurGoldL32/LiCurGoldH32/LiCurScoreL32/LiCurScoreH32/LiTotalGoldL32/LiTotalGoldH32/LiTotalScoreL32/LiTotalScoreH32/a33szMachineSerial/LiBankGoldL32/LiBankGoldH32/a17szRegIP/a12szMobile/a24szIdCard', $out_data);

		$out_data_Role_array['iCurGold'] = MakeINT64Value($out_data_Role_array['iCurGoldH32'], $out_data_Role_array['iCurGoldL32']);
		$out_data_Role_array['iCurScore'] = MakeINT64Value($out_data_Role_array['iCurScoreH32'], $out_data_Role_array['iCurScoreL32']);
		$out_data_Role_array['iTotalGold'] = MakeINT64Value($out_data_Role_array['iTotalGoldH32'], $out_data_Role_array['iTotalGoldL32']);
		$out_data_Role_array['iTotalScore'] = MakeINT64Value($out_data_Role_array['iTotalScoreH32'], $out_data_Role_array['iTotalScoreL32']);
		$out_data_Role_array['iBankGold'] = MakeINT64Value($out_data_Role_array['iBankGoldH32'], $out_data_Role_array['iBankGoldL32']);

		fitStr($out_data_Role_array['szMachineSerial']);
		fitStr($out_data_Role_array['szUsername']);
		fitStr($out_data_Role_array['szIP']);
		fitStr($out_data_Role_array['szRegIP']);
		fitStr($out_data_Role_array['szMobile']);
		fitStr($out_data_Role_array['szIdCard']);

		$out_array["RoleInfoList"][$x] = $out_data_Role_array;
	}

	//print_r($out_array);
	return $out_array;
}
//查询系统银行数据返回
//UINT32		iBankCount;				//银行数量
//SeSystemBankInfo	kSystemBankInfos[1];	//银行信息
//UINT32							iBankID;						//银行id
//INT64								iBalance;						//当前余额
//INT64								iLastBalance;					//上次余额
function ProcessDMQuerySystemBankDataRes($out_data) {
	//echo "ProcessDMQuerySystemBankDataRes: <br />";
	$out_data_array = unpack('LiBankCount/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;

	for ($x = 0; $x < $out_data_array['iBankCount']; $x++) {
		//echo "SystemBankInfo".($x + 1).":<br />";
		$out_data_Bank_array = unpack('x4/x' . ($x * 20) . '/LiBankID/LiBalanceL32/LiBalanceH32/LiLastBalanceL32/LiLastBalanceH32/', $out_data);

		$out_data_Bank_array['iBalance'] = MakeINT64Value($out_data_Bank_array['iBalanceH32'], $out_data_Bank_array['iBalanceL32']);
		$out_data_Bank_array['iLastBalance'] = MakeINT64Value($out_data_Bank_array['iLastBalanceH32'], $out_data_Bank_array['iLastBalanceL32']);

		//print_r($out_data_Bank_array);
		//echo "<br />";

		$out_array["SystemBankInfoList"][$x] = $out_data_Bank_array;
	}

	return $out_array;
}



// UINT32		iCount;		
// UINT32	    iRoleIdList[1];
function ProcessDMQuerySuperUserListRes($out_data) {
	$out_data_array = unpack('LiCount/', $out_data);

	for ($x = 0; $x < $out_data_array['iCount']; $x++) {
		$out_data_Bank_array = unpack('x4/x' . ($x * 4) . '/LiRoleID/', $out_data);
		$iRoleID = $out_data_Bank_array['iRoleID'];
		$out_array[$iRoleID] = 1;
	}
	return $out_array;
}


//设置房间概率返回
function ProcessDMSetRoomRate($out_data) {
    //$out_data_array =unpack('x4/x' . (1 * 4) . '/LiResult/', $out_data);
    print_r($out_data);
    //echo "<br />";

   // $out_array = $out_data_array;

    return $out_data;
}


function ProcessDMQueryRoomRate($out_data) {
    $out_data_array = unpack('LiCount/', $out_data);
    for ($x = 0; $x < $out_data_array['iCount']; $x++) {
        $out_data_Room_array = unpack('x4/x' . ($x * 16) . '/LnServerID/LnCtrlRatio/LnInitStorage/LnCurrentStorage', $out_data);
        //$nServerID = $out_data_Room_array['nServerID'];
        $out_array[$x]=$out_data_Room_array;
       //$out_array[nServerID] = 1;
    }
    return $out_array;
}