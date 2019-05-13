<?php
// OW TO DC
define('CMD_WD_BUY_HAPPYBEAN', 100);					//��ֵ���ֶ�
define('CMD_WD_BUY_VIP', 101);						//�����Ա
define('CMD_WD_DAILY_SIGN', 102);								//ÿ��ǩ��
define('CMD_WD_RESET_ROLE_BANK_PWD', 103);								//������������
define('CMD_WD_QUERY_RECHARGE_CARD_STATE', 104);								//��ѯ��ֵ��״̬
define('CMD_WD_USE_RECHARGE_CARD', 105);								//ʹ�ó�ֵ��
define('CMD_WD_CHECK_ROLE_SESSION', 106);								//����û�Session
define('CMD_WD_QUERY_ROLE_GAME_SIGN_INFO', 107);								//��ѯ��ɫ��Ϸǩ����Ϣ
define('CMD_WD_ROLE_GAME_SIGN', 108);								//��ɫ��Ϸǩ��
define('CMD_WD_GET_CHARGE_INFO', 109);								// ��ȡ��ֵ��Ϣ
define('CMD_WD_GET_GAMECONFIG_INFO', 110);							// ��ȡ��Ϸ������Ϣ
define('CMD_WD_GET_SERVER_LIST', 111);								// ��ȡ�������б�
define('CMD_WD_GET_GAME_VERSION', 112);						// ��ȡ��Ϸ�汾��Ϣ
define('CMD_WD_GET_ANDROID_VERSION', 113);							// ��ȡ��׿�汾��Ϣ
define('CMD_WD_GET_HOME_CAIJIN_MSG', 114);							// ��ȡ�����ʽ���Ϣ



//��ֵ���ֶ�
//UINT32 		iLoginID;		//�ʺ�ID
//UINT32 		iRMBMoney; 		//��ֵ������ң���λ��
//UINT32		iCardType;		//��ֵ������
function SendWDBuyHappybean($socket, $iLoginID, $iRMBMoney, $iCardType) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iLoginID);
	$in_stream->WeireULong($iRMBMoney);
	$in_stream->WeireULong($iCardType);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_BUY_HAPPYBEAN, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//�����Ա
//UINT32 		iLoginID;		//�ʺ�ID
//UINT32 		iDays; 			//�����ʱ�䣬��λ��
function SendWDBuyVIP($socket, $iLoginID, $iDays) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iLoginID);
	$in_stream->WeireULong($iDays);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_BUY_VIP, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//ÿ��ǩ��
//UINT32 		iLoginID;		//�ʺ�ID
function SendWDDailySign($socket, $iLoginID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iLoginID);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_DAILY_SIGN, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}


//������������
//UINT32		iRoleID;				//��ɫID
//char		szNewPwd[33];			//�µ���������
function SendWDResetRoleBankPwd($socket, $iRoleID, $szNewPwd) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iRoleID);
	$in_stream->WeireString($szNewPwd, 33);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_RESET_ROLE_BANK_PWD, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}


//��ѯ��ֵ��״̬
//char		cardNo[20];			//����
function SendWDQueryRechargeCardState($socket, $cardNo) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($cardNo, 20);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_QUERY_RECHARGE_CARD_STATE, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}


//������������
//char cardNo[20]; // ����
//char cardPass[10]; // ����
//UINT32 roleID;	//  ��ɫid
function SendWDUseRechargeCard($socket, $cardNo, $cardPass, $roleID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($cardNo, 20);
	$in_stream->WeireString($cardPass, 10);
	$in_stream->WeireULong($roleID);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_USE_RECHARGE_CARD, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//����û�Session
//UINT32		iRoleID;				//��ɫID
//char		szSession[33];			//Session
function SendWDCheckRoleSession($socket, $iRoleID, $szSession) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iRoleID);
	$in_stream->WeireString($szSession, 33);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_CHECK_ROLE_SESSION, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ѯ��ɫ��Ϸǩ����Ϣ
//UINT32		iRoleID;				//��ɫID
//UINT32		iKindID;				//����ID
//UINT32		iRoomType;				//��������
function SendWDQueryRoleGameSignInfo($socket, $iRoleID, $iKindID, $iRoomType) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iRoleID);
	$in_stream->WeireULong($iKindID);
	$in_stream->WeireULong($iRoomType);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_QUERY_ROLE_GAME_SIGN_INFO, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ɫ��Ϸǩ��
//UINT32		iRoleID;				//��ɫID
//UINT32		iKindID;				//����ID
//UINT32		iRoomType;				//��������
function SendWDRoleGameSign($socket, $iRoleID, $iKindID, $iRoomType) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iRoleID);
	$in_stream->WeireULong($iKindID);
	$in_stream->WeireULong($iRoomType);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_ROLE_GAME_SIGN, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ��ֵ��Ϣ
//UINT32		iCurTime;				//��ǰʱ��
function SendWDGetChargeInfo($socket, $iCurTime) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_CHARGE_INFO, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ��Ϸ������Ϣ
//UINT32		iCurTime;				//��ǰʱ��
function SendWDGetGameConfigInfo($socket, $iCurTime) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_GAMECONFIG_INFO, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ�������б�
//UINT32		iCurTime;				//��ǰʱ��
//UINT32		iServerType;			//����������
function SendWDGetServerList($socket, $iCurTime, $iServerType) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);
	$in_stream->WeireULong($iServerType);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_SERVER_LIST, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ��Ϸ�汾��Ϣ
//UINT32		iCurTime;				//��ǰʱ��
function SendWDGetGameVersion($socket, $iCurTime) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_GAME_VERSION, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ��׿�汾��Ϣ
//UINT32		iCurTime;				//��ǰʱ��
function SendWDGetAndroidVersion($socket, $iCurTime) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_ANDROID_VERSION, $in_stream->len, 0, REQ_OW, REQ_DC); 
	$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);
}

//��ȡ�����ʽ���Ϣ
//UINT32		iCurTime;				//��ǰʱ��
function SendWDGetHomeCaijinMsg($socket, $iCurTime) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCurTime);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_GET_HOME_CAIJIN_MSG, $in_stream->len, 0, REQ_OW, REQ_DC); 
    $socket->request($in_head,$in_stream->data);
}


