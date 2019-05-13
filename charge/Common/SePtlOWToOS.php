<?php
// OW TO OS
define('CMD_WO_GET_PAY_ORDER_ID', 1);			//��ȡ������
define('CMD_WO_CREATE_PAY_ORDER', 2);			//��������
define('CMD_WO_FIND_PAY_ORDER', 3);			//��ѯ����״̬
define('CMD_WO_SET_PAY_ORDER', 4);			//���ö���״̬
define('CMD_WO_SET_PAY_ORDER_TID', 5);		//���ö���ƽ̨���׺�
define('CMD_WO_GET_PAY_ORDER', 6);			//��ȡ�������
define('CMD_WO_ADD_PAY_ORDER_LOGS', 7);		//������
define('CMD_WO_GET_PAY_CONFIG', 8);			//查询充值配置
define('CMD_WO_UPDATE_PAY_CONFIG', 9);		//更新充值配置־
define('CMD_WO_INSERT_RECEIPT_DATA', 10);		//保存验证信息
define('CMD_WO_DELETE_RECEIPT_DATA', 11);		//删除验证信息
define('CMD_WO_UPDATE_RECEIPT_COUNT', 12);	//修改验证次数
define('CMD_WO_GET_RECEIPT_DATA', 13);		//获取验证信息
define('CMD_WO_CHECK_MOBILE_GET_CARD', 14);	//检查手机能否获取实卡
define('CMD_WO_MOBILE_GET_CARD', 15);			//手机号获取实卡
define('CMD_WO_LOCK_ORDER', 16);				//锁定订单号
define('CMD_WO_UNLOCK_ORDER', 17);			//解锁订单号

//��ȡ������
//char szMCHID[16];					//΢��֧��mchid
function SendWOGetPayOrderID($socket, $szMCHID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szMCHID, 16);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_GET_PAY_ORDER_ID, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */
	$socket->request($in_head,$in_stream->data);
}


//��������
//UINT32 iCardType;				//��ֵ����
//UINT32 iPayType;				//֧������ ���껶�ֶ�
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szOrderNo[64];			//������
//UINT32 iTotalFee;				//֧����λ��
//UINT32 iLoginID;				//��ɫID
function SendWOCreatePayOrder($socket, $iCardType, $iPayType, $szTransactionID, $szOrderNo, $iTotalFee, $iLoginID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCardType);
	$in_stream->WeireULong($iPayType);
	$in_stream->WeireString($szTransactionID, 64);
	$in_stream->WeireString($szOrderNo, 64);
	$in_stream->WeireULong($iTotalFee);
	$in_stream->WeireULong($iLoginID);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_CREATE_PAY_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */
	$socket->request($in_head,$in_stream->data);
}

//��ѯ����״̬
//UINT32 iCardType;				//��ֵ����
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szOrderNo[64];			//������
//UINT32 iStatus;					//״̬��
function SendWOFindPayOrder($socket, $iCardType, $szTransactionID, $szOrderNo, $iStatus) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCardType);
	$in_stream->WeireString($szTransactionID, 64);
	$in_stream->WeireString($szOrderNo, 64);
	$in_stream->WeireULong($iStatus);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_FIND_PAY_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */
	$socket->request($in_head,$in_stream->data);
}

//���ö���״̬
//UINT32 iCardType;				//��ֵ����
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szOrderNo[64];			//������
//UINT32 iStatus;					//״̬��
function SendWOSetPayOrderStatus($socket, $iCardType, $szTransactionID, $szOrderNo, $iStatus) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCardType);
	$in_stream->WeireString($szTransactionID, 64);
	$in_stream->WeireString($szOrderNo, 64);
	$in_stream->WeireULong($iStatus);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_SET_PAY_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}

//���ö���ƽ̨���׺�
//UINT32 iCardType;				//��ֵ����
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szOrderNo[64];			//������
function SendWOSetPayOrderTransactionID($socket, $iCardType, $szTransactionID, $szOrderNo) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iCardType);
	$in_stream->WeireString($szTransactionID, 64);
	$in_stream->WeireString($szOrderNo, 64);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_SET_PAY_ORDER_TID, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */
	$socket->request($in_head,$in_stream->data);
}

//��ȡ�������
//char	szOrderNo[64];			//������
//UINT32 iCardType;				//��ֵ����
function SendWOGetPayOrder($socket, $szOrderNo, $iCardType) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireString($szOrderNo, 64);
	$in_stream->WeireULong($iCardType);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_GET_PAY_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */

	$socket->request($in_head,$in_stream->data);
}

//������־
//UINT32 iPayResult;				//֧�����
//char	szPayInfo[256];			//֧����Ϣ
//char	szBillDate[16];			//��������
//char	szBargainorID[64];		//�����ʺ�
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szBillNo[64];			//������
//UINT32 iTotalFee;				//֧����λ��
//char szBurden[8];				//˰������
//char szCardType[64];				//֧������
//UINT32 iLoginID;				//��ɫID
function SendWOAddPayLogs($socket, $iPayResult, $szPayInfo, $szBillDate, $szBargainorID, $szTransactionID, $szBillNo, $iTotalFee, $szBurden, $szCardType, $iLoginID) 
{
	$in_stream = new PHPStream();
	$in_stream->WeireULong($iPayResult);
	$in_stream->WeireString($szPayInfo, 256);
	$in_stream->WeireString($szBillDate, 16);
	$in_stream->WeireString($szBargainorID, 64);
	$in_stream->WeireString($szTransactionID, 64);
	$in_stream->WeireString($szBillNo, 64);
	$in_stream->WeireULong($iTotalFee);
	$in_stream->WeireString($szBurden, 8);
	$in_stream->WeireString($szCardType, 64);
	$in_stream->WeireULong($iLoginID);

	//echo $in_stream->len;
	//echo $in_stream->data;

	$in_head =  MakeSendHead(CMD_WO_ADD_PAY_ORDER_LOGS, $in_stream->len, 0, REQ_OW, REQ_ORDER); 
	/* $in_head_len = COMM_HEAD_LEN;

	$in_len = $in_head_len + $in_stream->len;
	$in = $in_head . $in_stream->data;
	socket_write($socket, $in, $in_len); */

	$socket->request($in_head,$in_stream->data);
}


//查询充值配置
//UINT32 iCurTime;				//当前时间
function SendWOGetPayConfig($socket, $iCurTime)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_GET_PAY_CONFIG, $in_stream->len, 0, REQ_OW, REQ_ORDER);
    /* $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */

	$socket->request($in_head,$in_stream->data);
}

//更新充值配置
//char szColumn[64];					//字段
//INT64 iTotalMoney;					//金额
function SendWOUpdatePayConfig($socket, $szColumn, $iTotalMoney)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szColumn, 64);
    $in_stream->WeireINT64($iTotalMoney);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_UPDATE_PAY_CONFIG, $in_stream->len, 0, REQ_OW, REQ_ORDER);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */

	$socket->request($in_head,$in_stream->data);
}

//保存验证信息
//UINT32 iLoginID;				//角色ID
//UINT32 iPayType;				//支付类型
//char szReceiptData[1024*10];	//验证数据
function SendWOInsertReceiptData($socket, $iLoginID, $iPayType, $szReceiptData)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iLoginID);
    $in_stream->WeireULong($iPayType);
    $in_stream->WeireString($szReceiptData, 10240);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_INSERT_RECEIPT_DATA, $in_stream->len, 0, REQ_OW, REQ_ORDER);
    /* $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}

//删除验证信息
//UINT32 iLoginID;				//角色ID
//UINT32 iRID;					//数据ID
function SendWODeleteReceiptData($socket, $iLoginID, $iRID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iLoginID);
    $in_stream->WeireULong($iRID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_DELETE_RECEIPT_DATA, $in_stream->len, 0, REQ_OW, REQ_ORDER);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */

    $socket->request($in_head,$in_stream->data);
}

//修改验证次数
//UINT32 iLoginID;				//角色ID
//UINT32 iRID;					//数据ID
function SendWOUpdateReceiptData($socket, $iLoginID, $iRID)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iLoginID);
    $in_stream->WeireULong($iRID);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_UPDATE_RECEIPT_COUNT, $in_stream->len, 0, REQ_OW, REQ_ORDER);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */

    $socket->request($in_head,$in_stream->data);
}

//获取验证信息
//UINT32 iCurTime;				//当前时间
function SendWOGetReceiptData($socket, $iCurTime)
{
    $in_stream = new PHPStream();
    $in_stream->WeireULong($iCurTime);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_GET_RECEIPT_DATA, $in_stream->len, 0, REQ_OW, REQ_ORDER);
  /*   $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */

    $socket->request($in_head,$in_stream->data);
}



//锁定订单号
//char	szOrderNo[64];			//订单号
function SendWOLockOrder($socket, $szOrderNo)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szOrderNo, 64);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_LOCK_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER);
   /*  $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}

//解锁订单号
//char	szOrderNo[64];			//订单号
function SendWOUnLockOrder($socket, $szOrderNo)
{
    $in_stream = new PHPStream();
    $in_stream->WeireString($szOrderNo, 64);

    //echo $in_stream->len;
    //echo $in_stream->data;

    $in_head =  MakeSendHead(CMD_WO_UNLOCK_ORDER, $in_stream->len, 0, REQ_OW, REQ_ORDER);
    /* $in_head_len = COMM_HEAD_LEN;

    $in_len = $in_head_len + $in_stream->len;
    $in = $in_head . $in_stream->data;
    socket_write($socket, $in, $in_len); */
    $socket->request($in_head,$in_stream->data);
}
