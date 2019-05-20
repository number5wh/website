<?php
// OW TO DC

define('CMD_WD_BUY_HAPPYBEAN', 100);					//充值欢乐豆
define('CMD_WD_BUY_VIP', 101);						//购买会员
define('CMD_WD_DAILY_SIGN', 102);								//每日签到
define('CMD_WD_RESET_ROLE_BANK_PWD', 103);								//重置银行密码
define('CMD_WD_QUERY_RECHARGE_CARD_STATE', 104);								//查询充值卡状态
define('CMD_WD_USE_RECHARGE_CARD', 105);								//使用充值卡
define('CMD_WD_CHECK_ROLE_SESSION', 106);								//检查用户Session
define('CMD_WD_QUERY_ROLE_GAME_SIGN_INFO', 107);								//查询角色游戏签到信息
define('CMD_WD_ROLE_GAME_SIGN', 108);								//角色游戏签到
define('CMD_WD_GET_CHARGE_INFO', 109);								// 获取充值信息
define('CMD_WD_GET_GAMECONFIG_INFO', 110);							// 获取游戏配置信息
define('CMD_WD_GET_SERVER_LIST', 111);								// 获取服务器列表
define('CMD_WD_GET_GAME_VERSION', 112);						// 获取游戏版本信息
define('CMD_WD_GET_ANDROID_VERSION', 113);							// 获取安卓版本信息
define('CMD_WD_GET_HOME_CAIJIN_MSG', 114);							// 获取官网彩金活动信息
define('CMD_WD_GET_YOUXIDUN_INFO', 115);							// 获取游戏盾信息

define('CMD_MA_GET_ROLE_BASE_INFO', 2);								//获取玩家信息

define('CMD_MD_GET_ROLE_BASE_INFO', 2);								//获取玩家信息
define('CMD_MD_QUERY_ROLE_BANK_INFO', 4);							//查询玩家银行数据

//获取服务器列表
//UINT32		iCurTime;				//当前时间
//UINT32		iServerType;			//服务器类型
function SendWDGetServerList($socket, $iCurTime, $iServerType)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);
    $in_stream->WeireULong($iServerType);
    
    $in_head =  MakeSendHead(CMD_WD_GET_SERVER_LIST, $in_stream->len, 0, REQ_OW, REQ_DC);
    
    $socket->request($in_head,$in_stream->data);
}
//获取游戏版本信息
//UINT32		iCurTime;				//当前时间
function SendWDGetGameVersion($socket, $iCurTime)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);
    $in_head =  MakeSendHead(CMD_WD_GET_GAME_VERSION, $in_stream->len, 0, REQ_OW, REQ_DC);
  
    $socket->request($in_head,$in_stream->data);
}

//获取安卓版本信息
//UINT32		iCurTime;				//当前时间
function SendWDGetAndroidVersion($socket, $iCurTime)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);


    $in_head =  MakeSendHead(CMD_WD_GET_ANDROID_VERSION, $in_stream->len, 0, REQ_OW, REQ_DC);
  
    $socket->request($in_head,$in_stream->data);

}



//获取游戏盾信息
//UINT32		iCurTime;				//当前时间
function SendWDGetYouXiDunInfo($socket, $iCurTime)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WD_GET_YOUXIDUN_INFO, $in_stream->len, 0, REQ_OW, REQ_DC);
    //$in_head_len = COMM_HEAD_LEN;

    //$in_len = $in_head_len + $in_stream->len;
    //$in = $in_head . $in_stream->data;
    //socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in_stream->data);
}

//检查用户Session
//UINT32		iRoleID;				//角色ID
//char		szSession[33];			//Session
function SendWDCheckRoleSession($socket, $iRoleID, $szSession)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iRoleID);
    $in_stream->WeireString($szSession, 33);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WD_CHECK_ROLE_SESSION, $in_stream->len, 0, REQ_OW, REQ_DC);
    /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}

//获取玩家信息
//UINT32		iRoleID;				//角色ID
function SendMAGetRoleBaseInfo(&$socket, $iRoleID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iRoleID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_MA_GET_ROLE_BASE_INFO, $in_stream->len, 0, REQ_OM, REQ_AI);
    $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_stream->data;
    //socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in);
}

//获取玩家信息
//UINT32		iRoleID;				//角色ID
function SendMDGetRoleBaseInfo(&$socket, $iRoleID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iRoleID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_MD_GET_ROLE_BASE_INFO, $in_stream->len, 0, REQ_OM, REQ_DC);
    $in_head_len = COMM_HEAD_LEN;

    //$in_len = $in_head_len + $in_stream->len;
    //$in = $in_head . $in_stream->data;
    $in = $in_stream->data;
    $socket->request($in_head,$in);
}

//查询玩家银行数据
//UINT32		iRoleID;				//角色ID
function SendMDQueryRoleBankInfo($socket, $iRoleID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iRoleID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_MD_QUERY_ROLE_BANK_INFO, $in_stream->len, 0, REQ_OM, REQ_DC);
    //$in_head_len = COMM_HEAD_LEN;

    //$in_len = $in_head_len + $in_stream->len;
    $in = $in_stream->data;
    //socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in);
}







