<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';



/* echo "<br />OSGetPayOrder:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
//UINT32 iPayResult;				//支付结果
//char	szPayInfo[256];			//支付信息
//char	szBillDate[16];			//交易日期
//char	szBargainorID[64];		//交易帐号
//char	szTransactionID[64];	//平台交易号
//char	szBillNo[64];			//订单号
//UINT32 iTotalFee;				//支付金额单位分
//char szBurden[8];				//税收类型
//char szCardType[64];				//支付类型
//UINT32 iLoginID;				//角色ID
SendWOAddPayLogs($socket, 1, "", "20151127", "123456798", "169003539420151127200254611311","20151127201245448031", 0.01*100,"1","3001_001",800143);
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */


/**
 * 插入支付回调日志
 * @param $PayResult int 支付结果
 * @param $PayInfo string 支付信息
 * @param $BillDate  string 交易日期
 * @param $BargainorID string 交易帐号
 * @param $TransactionID string 交易订单号
 * @param $BillNo  string 商户订单号
 * @param $TotalFee int 支付金额单位分
 * @param $Burden string 税收类型
 * @param $CardType string 支付类型
 * @param $LoginID int 角色ID
 * 
 * **/


function OSAddPayLogs($PayResult,$PayInfo,$BillDate,$BargainID,$TransactionID,$BillNo,$TotalFee,$Burden,$CardType,$LoginID){
    $socket = getSocketInstance('OS');
    SendWOAddPayLogs($socket,$PayResult,$PayInfo,$BillDate,$BargainID,$TransactionID,$BillNo,$TotalFee,$Burden,$CardType,$LoginID);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;
}