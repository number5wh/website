<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';



/* echo "<br />OSSetPayOrderTransactionID:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOSetPayOrderTransactionID($socket, 1, "169003539420151127200254611311", "20151127201245448031");
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */



/**
 * 设置交易订单号
 * @param $CardType int 充值类型
 * @param $TransactionID string 交易订单号
 * @param $OrderNo string  商户定代号
 * 
 * @return iResult int;   0成功
 *
 * **/

function OSSetPayOrderTransactionID($CardType,$TransactionID,$OrderNo){
    $socket = getSocketInstance('OS');
    SendWOSetPayOrderTransactionID($socket,$CardType,$TransactionID,$OrderNo);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    return $out_array;
}