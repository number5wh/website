<?php
include_once ROOT_PATH.'Common/Comm.php';
include_once ROOT_PATH.'Common/SvrPtl/MySocket.class.php';
include_once ROOT_PATH.'Common/SePtlOMToAS.php';
include_once ROOT_PATH.'Common/SePtlASToOM.php';
include_once ROOT_PATH.'Common/SePtlDCToOM.php';
include_once ROOT_PATH.'Common/SePtlOMToDC.php';

//echo "<br />DSLockRoleMonery:<br />";
//$socket = ConnectServer(DC_SERVER_IP, DC_SERVERPORT);
//SendMDLockRoleMonery($socket, 60005, 10000);
//$out_data = ReadData($socket);
//$out_array = ProcessDMRoleOperateAckRes($out_data);
//
//echo "<br />输出数据中心返回：<br />";
//print_r($out_array);

function DSLockRoleMonery($RoleID,$Monery){
    $socket = getSocketInstance('DC');
    SendMDLockRoleMonery($socket,$RoleID,$Monery);
    $out_data = $socket->response();
    $out_array = ProcessDMRoleOperateAckRes($out_data);
    return $out_array;
}


