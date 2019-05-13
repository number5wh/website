<?php
// OW TO AS
define('CMD_WA_CHECK_ACCOUNT',100);							//����ʺ��Ƿ��Ѵ��ڣ����ڣ�ע�ᣬ��ֵ��
define('CMD_WA_GET_ACCOUNT_INFO', 101);						//��ȡ�ʺ���Ϣ�������ڣ�ע��ɹ���չʾ���޸����룬�������룩
define('CMD_WA_REGISTER_ACCOUNT',102);						//ע���ʺ�
define('CMD_WA_RESET_PASSWORD', 103);							//��������
define('CMD_WA_CHANGE_PASSWORD',104);						//�޸�����
define('CMD_WA_BUY_HAPPYBEAN', 105);							//��ֵ���ֶ�
define('CMD_WA_BUY_VIP',106);								//�����Ա

define('CMD_WA_BIND_WECHAT', 105);							//绑定微信
define('CMD_WA_GET_SECCODE', 106);							//获取验证码
define('CMD_WA_MAKE_SECCODE', 107);							//生成验证码（用在：重置密码）
define('CMD_WA_UNBIND_WECHAT', 108);							//微信解绑
define('CMD_WA_CHECK_SECCODE', 109);							//校验验证码
define('CMD_WA_GET_ACCOUNT_INFO_BY_ID', 116);					//获取帐号信息
//����ʺ��Ƿ��Ѵ��ڣ����ڣ�ע�ᣬ��ֵ��
//char		szLoginCode[64];		//�ʺ�
function SendWACheckAccount(&$socket, $szLoginCode)
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szLoginCode, 64);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WA_CHECK_ACCOUNT, $in_stream->len, 0, REQ_OW, REQ_AI); 
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in_stream->data);


}

//��ȡ�ʺ���Ϣ�������ڣ�ע��ɹ���չʾ���޸����룬�������룩
//char		szLoginCode[64];		//�ʺ�
function SendWAGetAccountInfo(&$socket, $szLoginCode)
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szLoginCode, 64);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WA_GET_ACCOUNT_INFO, $in_stream->len, 0, REQ_OW, REQ_AI); 
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in_stream->data);
}

//����IP�������
//char		szLoginCode[64];		//�ʺ�
//char		szPassword[33];		//����md5
//char		szIP[17]; 		//ע���ַ 
//char		szRealName[64]; 	//��ʵ����
//char		IdCard[24]; 		//���֤
//char		szMobilePhone[12]; 	//�ֻ����
//char		szQQ[12]; 		//QQ�� 
function SendWARegisterAccount(&$socket, $szLoginCode, $szPassword, $szIP, $szRealName, $IdCard, $szMobilePhone, $szQQ)
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szLoginCode, 64);
	$in_stream->WeireString($szPassword, 33);
	$in_stream->WeireString($szIP, 17);
	$in_stream->WeireString($szRealName, 64);
	$in_stream->WeireString($IdCard, 24);
	$in_stream->WeireString($szMobilePhone, 12);
	$in_stream->WeireString($szQQ, 12);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WA_REGISTER_ACCOUNT, $in_stream->len, 0, REQ_OW, REQ_AI); 
//	$in_head_len = COMM_HEAD_LEN;
//
//	$in_len = $in_head_len + $in_stream->len;
//	$in = $in_head . $in_stream->data;
//	socket_write($socket, $in, $in_len);
    $socket->request($in_head,$in_stream->data);
}
//绑定微信
//char		szLoginCode[64];		//帐号
//char		szWeChatRoleID[64];		//帐号
function SendWABindWeChat(&$socket, $szLoginCode, $szWeChatRoleID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);
    $in_stream->WeireString($szWeChatRoleID, 64);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_BIND_WECHAT, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//修改密码
//char		szLoginCode[64];	//帐号
//char		szOldPassword[33];	//旧密码md5
//char		szNewPassword[33];	//新密码md5
function SendWAChangePassword(&$socket, $szLoginCode, $szOldPassword, $szNewPassword)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);
    $in_stream->WeireString($szOldPassword, 33);
    $in_stream->WeireString($szNewPassword, 33);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_CHANGE_PASSWORD, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//获取验证码
//char		szWeChatRoleID[64];		//微信id
function SendWAGetSeccode($socket, $szWeChatRoleID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szWeChatRoleID, 64);


    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_GET_SECCODE, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//生成验证码（用在：重置密码）
//char		szLoginCode[64];		//帐号
function SendWAMakeSecCode(&$socket, $szLoginCode)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_MAKE_SECCODE, $in_stream->len, 0, REQ_OW, REQ_AI);
   /* $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//重置密码
//char		szLoginCode[64];	//帐号
//UINT32		iCode;				//验证码
//char		szNewPassword[33];	//新密码md5
function SendWAResetPassword(&$socket, $szLoginCode, $iCode, $szNewPassword)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);
    $in_stream->WeireULong($iCode);
    $in_stream->WeireString($szNewPassword, 33);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_RESET_PASSWORD, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//微信解绑
//char		szLoginCode[64];	//帐号
//UINT32		iCode;				//验证码
function SendWAUnBindWeChat($socket, $szLoginCode, $iCode)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);
    $in_stream->WeireULong($iCode);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_UNBIND_WECHAT, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}

//校验验证码
//char		szLoginCode[64];	//帐号
//UINT32		iCode;				//验证码
function SendWACheckSeccode($socket, $szLoginCode, $iCode)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szLoginCode, 64);
    $in_stream->WeireULong($iCode);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_CHECK_SECCODE, $in_stream->len, 0, REQ_OW, REQ_AI);
    /*$in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len);*/
    $socket->request($in_head,$in_stream->data);
}
//获取帐号信息，（用在：注册成功后展示，修改密码，重置密码）
//char		szLoginCode[64];		//帐号
function SendWAGetAccountInfoByID($socket, $szLoginID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($szLoginID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WA_GET_ACCOUNT_INFO_BY_ID, $in_stream->len, 0, REQ_OW, REQ_AI);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}
