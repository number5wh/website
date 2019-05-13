<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';




/* echo "<br />OSGetPayConfig:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOGetPayConfig($socket, 0);
$out_data = ReadData($socket);
$out_array = ProcessOWGetPayConfigRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */


/**
 * 获取支付配置信息
 * 
 * **/

function OSGetPayConfig(){
    $socket = getSocketInstance('OS');
    SendWOGetPayConfig($socket, 0);
    $out_data = $socket->response();
    $out_array = ProcessOWGetPayConfigRet($out_data);
    return $out_array;
}