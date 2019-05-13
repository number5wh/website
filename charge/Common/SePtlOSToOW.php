<?php
// OS TO OW
//��ȡ�����ŷ���
//char	szOrderNo[64];			//������
function ProcessOWGetPayOrderIDRet($out_data)
{
	//echo "ProcessOWGetPayOrderIDRet: <br />";
	$out_data_array = unpack('a64szOrderNo/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;
	fitStr($out_array['szOrderNo']);
	
	return $out_array;
}

//��ȡ������ݷ���
//UINT32 iLoginID;				//��ɫID
//UINT32 iPayType;				//֧������
//UINT32 iCardType;				//��ֵ����
//UINT32 iStatus;					//״̬
//char	szTransactionID[64];	//ƽ̨���׺�
//char	szOrderNo[64];			//������
//UINT32 iTotalFee;				//֧����λ��
//UINT32 iUpdateTime;				//������ʱ��
function ProcessOWGetPayOrderRet($out_data)
{
	//echo "ProcessOWGetPayOrderRet: <br />";
	$out_data_array = unpack('LiResult/LiLoginID/LiPayType/LiCardType/LiStatus/a64szTransactionID/a64szOrderNo/LiTotalFee/LiUpdateTime/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;
	
	return $out_array;
}

//������������
//UINT32 iResult;				//��ѯ���0�ɹ���1ʧ��
function ProcessOWOperatePayOrderRet($out_data)
{
	//echo "ProcessOWOperatePayOrderRet: <br />";
	$out_data_array = unpack('LiResult/', $out_data);
	//print_r($out_data_array);
	//echo "<br />";

	$out_array = $out_data_array;
	
	return $out_array;
}


//查询充值配置返回
//UINT32	iPct;				//充值比例
//INT64	iPrivateMoney;		//私有账户累计
//INT64	iPublicMoney;		//共有账户累计
function ProcessOWGetPayConfigRet($out_data)
{
   // echo "ProcessOWGetPayConfigRet: <br />";
    $out_data_array = unpack('LiPct/LiPrivateMoneyL32/LiPrivateMoneyH32/LiPublicMoneyL32/LiPublicMoneyH32/', $out_data);
   // print_r($out_data_array);
   // echo "<br />";

    $out_data_array['iPrivateMoney'] = MakeINT64Value($out_data_array['iPrivateMoneyH32'], $out_data_array['iPrivateMoneyL32']);
    $out_data_array['iPublicMoney'] = MakeINT64Value($out_data_array['iPublicMoneyH32'], $out_data_array['iPublicMoneyL32']);

    $out_array = $out_data_array;

    return $out_array;
}


//获取验证信息返回
//UINT32	iReceiptCount;		//验证数据数量
//SeReceiptDataInfo akReceiptDataInfo[1];
//	UINT32	iRID;				//数据ID
//	UINT32	iLoginID;			//角色ID
//	UINT32	iPayType;			//支付类型
//	UINT32	iReqCount;			//请求次数
//	UINT32	iCreateTime;		//创建时间
//	char szReceiptData[1024*10];//验证数据
function ProcessOWGetReceiptDataRet($out_data)
{
   // echo "ProcessOWGetReceiptDataRet: <br />";
    $out_data_array = unpack('LiReceiptCount/', $out_data);
    //print_r($out_data_array);
    //echo "<br />";

    $out_array = $out_data_array;

    for ($x=0; $x<$out_data_array['iReceiptCount']; $x++)
    {
        //echo "ReceiptData".($x + 1).":<br />";
        $out_data_Receipt_array = unpack('x4/x'.($x*10260).'/LiRID/LiLoginID/LiPayType/LiReqCount/LiCreateTime/a10240szReceiptData/', $out_data);

		$date1=date('Y-m-d H:i:s', $out_data_Receipt_array['iCreateTime'] + 8 * 3600);
		/* print_r($out_data_Receipt_array);
		echo "<br />"; */
		fitStr($out_data_Receipt_array['szReceiptData']);

		$out_array["ReceiptDataList"][$x] = $out_data_Receipt_array;
}

	return $out_array;
}

