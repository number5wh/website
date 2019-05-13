<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';


/* echo "<br />OSFindPayOrder:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOFindPayOrder($socket, 1, "123456", "20151127201245448031", 0);
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */



/**
 * 查询订单状态
 * @param $CardType int 充值类型
 * @param $TransactionID string 交易订单号
 * @param $OrderNo string  商户定代号
 * @param Status int 状态号
 * 
 * @return iResult int;  0存在
 * 
 * **/

function OSFindPayOrder($CardType,$TransactionID,$OrderNo,$Status){
    $socket = getSocketInstance('OS');
    SendWOFindPayOrder($socket,$CardType,$TransactionID,$OrderNo,$Status);
    $out_data = $socket->response();
    $out_array = ProcessDWOperateAckRes($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;
}