<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';


/* echo "<br />OSGetPayOrderID:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
//SendWOGetPayOrderID($socket, "1267992501");
SendWOGetPayOrderID($socket, "");
$out_data = ReadData($socket);
$out_array = ProcessOWGetPayOrderIDRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */


/**
 * 获取微信支付订单号
 * @param MCHID string 微信支付mchid
 * 
 * @return OrderNo string 商户订单号
 * */
function OSGetPayOrderID($MCHID){
    $socket = getSocketInstance('OS');
    SendWOGetPayOrderID($socket, $MCHID);
    $out_data = $socket->response();
    $out_array = ProcessOWGetPayOrderIDRet($out_data);
    if($out_array == false)
        $out_array['iResult'] = -1;
    else
        $out_array['iResult'] = 0;
    
    return $out_array;
}