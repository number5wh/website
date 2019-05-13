<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';




/* echo "<br />OSCreatePayOrder:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOCreatePayOrder($socket, 1, 1, "", "20151127210630837004", 500 * 100, 800143);
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */


/**
 * 创建支付订单
 * @param $CardType int 充值类型 
 * @param $PayType int   支付类型
 * @param $TransactionID string 交易订单号
 * @param $OrderNo string 商户订单号
 * @param $TotalFee int 支付金额单位分
 * @param $LoginID int 角色ID
 * 
 * */

function OSCreatePayOrder($CardType,$PayType,$transaction_id,$sp_billno,$total_fee,$LoginID){
    $socket = getSocketInstance('OS');
    SendWOCreatePayOrder($socket,$CardType,$PayType,$transaction_id,$sp_billno,$total_fee,$LoginID);
    $out_data = $socket->response();
    $out_array = ProcessOWOperatePayOrderRet($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;    
}