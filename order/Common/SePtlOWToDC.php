<?php
// OW TO DC
define('CMD_WD_BUY_HAPPYBEAN', 100);					//��ֵ���ֶ�
define('CMD_WD_BUY_VIP', 101);						//�����Ա
define('CMD_WD_DAILY_SIGN', 102);								//每日签到
define('CMD_WD_QUERY_RECHARGE_CARD_STATE', 104);						//查询充值卡状态
define('CMD_WD_USE_RECHARGE_CARD', 105);						//使用充值卡

//��ֵ���ֶ�
//UINT32 		iLoginID;		//�ʺ�ID
//UINT32 		iRMBMoney; 		//��ֵ������ң���λ��
function SendWDBuyHappyBean(&$socket, $iLoginID, $iRMBMoney, $iCardID)
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iLoginID);
	$in_stream->WeireULong($iRMBMoney);
    $in_stream->WeireULong($iCardID);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_BUY_HAPPYBEAN, $in_stream->len, 0, REQ_OW, REQ_DC); 
	/*$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//�����Ա
//UINT32 		iLoginID;		//�ʺ�ID
//UINT32 		iDays; 			//�����ʱ�䣬��λ��
function SendWDBuyVIP(&$socket, $iLoginID, $iDays)
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iLoginID);
	$in_stream->WeireULong($iDays);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WD_BUY_VIP, $in_stream->len, 0, REQ_OW, REQ_DC); 
	/*$in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}
//每日签到
//UINT32 		iLoginID;		//帐号ID
function SendWDDailySign($socket, $iLoginID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iLoginID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WD_DAILY_SIGN, $in_stream->len, 0, REQ_OW, REQ_DC);
   /* $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}



//查询充值卡状态
//char		szCardNo[20];				//卡号
function SendWDQueryRechargeCardState($socket, $szCardNo)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szCardNo, 20);

    $in_head =  MakeSendHead(CMD_WD_QUERY_RECHARGE_CARD_STATE, $in_stream->len, 0, REQ_OW, REQ_DC);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}


//使用充值卡
//char		szCardNo[20];				//卡号
//char		szCardPass[10];				//密码
//UINT32	iRoleID;					// 角色ID
function SendWDUseRechargeCard($socket, $szCardNo, $szPass, $iRoleID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szCardNo, 20);
    $in_stream->WeireString($szPass, 10);
    $in_stream->WeireULong($iRoleID);

    $in_head =  MakeSendHead(CMD_WD_USE_RECHARGE_CARD, $in_stream->len, 0, REQ_OW, REQ_DC);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}