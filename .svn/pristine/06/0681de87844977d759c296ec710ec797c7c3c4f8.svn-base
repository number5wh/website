<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOWToOS.php';
include_once ROOT_PATH.'Common/SePtlOSToOW.php';


//锁定订单号
function OSLockOrder($RoleID,$szOrderNo){
    $socket = getSocketInstance('OS');
    SendWOLockOrder($socket,$szOrderNo);
    $out_data = $socket->response();
    $out_array = ProcessOWOperatePayOrderRet($out_data);
    return $out_array;
}

/* echo "<br />OSLockOrder:<br />";
$socket = ConnectServer(OS_SERVER_IP, OS_SERVERPORT);
SendWOLockOrder($socket, "20151129132621809039");
$out_data = ReadData($socket);
$out_array = ProcessOWOperatePayOrderRet($out_data);

echo "<br />����������������أ�<br />";
print_r($out_array); */