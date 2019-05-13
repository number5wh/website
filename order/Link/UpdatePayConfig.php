<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';



/* echo "<br />OSUpdatePayConfig:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOUpdatePayConfig($socket, "PrivateAmount", 10000000000);
//SendWOUpdatePayConfig($socket, "PublicAmount", 10000000000);
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */

function OSUpdatePayConfig($column,$amount){
    $socket = getSocketInstance('OS');
    SendWOUpdatePayConfig($socket, $column,$amount);
    $out_data = $socket->response();
    $out_array = ProcessOWOperatePayOrderRet($out_data);
    if(!isset($out_array['iResult']))
        $out_array['iResult'] = -1;
    return $out_array;
    
}